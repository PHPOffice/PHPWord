<?php

/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Reader\ODText;

use DateTime;
use DOMElement;
use DOMNodeList;
use PhpOffice\Math\Reader\MathML;
use PhpOffice\PhpWord\Element\AbstractContainer;
use PhpOffice\PhpWord\Element\Cell;
use PhpOffice\PhpWord\Element\Row;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\TrackChange;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Shared\XMLReader;

/**
 * Content reader.
 *
 * @since 0.10.0
 */
class Content extends AbstractPart
{
    /** @var ?Section */
    private $section;

    /** LibreOffice takes a larger count of columns or rows, repeated or spanned, as 1 (`xmltbli.cxx`) */
    private const MAX_COLUMNS = 256;
    private const MAX_ROWS = 8192;

    /** @var ?Cell The cell being read, which takes the content in place of the section */
    private $cell;

    /** @var array<string, float> The width in twips of each column style */
    private $columnWidths = [];

    /**
     * Read content.xml.
     */
    public function read(PhpWord $phpWord): void
    {
        $xmlReader = new XMLReader();
        $xmlReader->getDomFromZip($this->docFile, $this->xmlFile);

        $this->columnWidths = [];
        foreach ($xmlReader->getElements('office:automatic-styles/style:style[@style:family="table-column"]') as $style) {
            $width = $this->toTwip((string) $xmlReader->getAttribute('style:column-width', $style, 'style:table-column-properties'));
            if ($width !== null) {
                $this->columnWidths[$style->getAttribute('style:name')] = $width;
            }
        }

        $nodes = $xmlReader->getElements('office:body/office:text/*');
        $this->section = null;
        $this->processNodes($nodes, $xmlReader, $phpWord);
        $this->section = null;
    }

    /** @param DOMNodeList<DOMElement> $nodes */
    public function processNodes(DOMNodeList $nodes, XMLReader $xmlReader, PhpWord $phpWord): void
    {
        if ($nodes->length > 0) {
            foreach ($nodes as $node) {
                // $styleName = $xmlReader->getAttribute('text:style-name', $node);
                switch ($node->nodeName) {
                    case 'text:h': // Heading
                        $depth = $xmlReader->getAttribute('text:outline-level', $node);
                        $this->getContainer($phpWord)->addTitle($node->nodeValue, $depth);

                        break;
                    case 'text:p': // Paragraph
                        $styleName = $xmlReader->getAttribute('text:style-name', $node);
                        if (substr((string) $styleName, 0, 2) === 'SB') {
                            break;
                        }
                        $element = $xmlReader->getElement('draw:frame/draw:object', $node);
                        if ($element) {
                            $mathFile = str_replace('./', '', $element->getAttribute('xlink:href')) . '/content.xml';

                            $xmlReaderObject = new XMLReader();
                            $mathElement = $xmlReaderObject->getDomFromZip($this->docFile, $mathFile);
                            if ($mathElement) {
                                $mathXML = $mathElement->saveXML($mathElement);

                                if (is_string($mathXML)) {
                                    $reader = new MathML();
                                    $math = $reader->read($mathXML);

                                    $this->getContainer($phpWord)->addFormula($math);
                                }
                            }
                        } else {
                            $children = $node->childNodes;
                            $spans = false;
                            /** @var DOMElement $child */
                            foreach ($children as $child) {
                                switch ($child->nodeName) {
                                    case 'text:change-start':
                                        $changeId = $child->getAttribute('text:change-id');
                                        if (isset($trackedChanges[$changeId])) {
                                            $changed = $trackedChanges[$changeId];
                                        }

                                        break;
                                    case 'text:change-end':
                                        unset($changed);

                                        break;
                                    case 'text:change':
                                        $changeId = $child->getAttribute('text:change-id');
                                        if (isset($trackedChanges[$changeId])) {
                                            $changed = $trackedChanges[$changeId];
                                        }

                                        break;
                                    case 'text:span':
                                        $spans = true;

                                        break;
                                }
                            }

                            if ($spans) {
                                $element = $this->getContainer($phpWord)->addTextRun();
                                foreach ($children as $child) {
                                    switch ($child->nodeName) {
                                        case 'text:span':
                                            /** @var DOMElement $child2 */
                                            foreach ($child->childNodes as $child2) {
                                                switch ($child2->nodeName) {
                                                    case '#text':
                                                        $element->addText($child2->nodeValue);

                                                        break;
                                                    case 'text:tab':
                                                        $element->addText("\t");

                                                        break;
                                                    case 'text:s':
                                                        $spaces = (int) $child2->getAttribute('text:c') ?: 1;
                                                        $element->addText(str_repeat(' ', $spaces));

                                                        break;
                                                }
                                            }

                                            break;
                                    }
                                }
                            } else {
                                $element = $this->getContainer($phpWord)->addText($node->nodeValue);
                            }
                            if (isset($changed) && is_array($changed)) {
                                $element->setTrackChange($changed['changed']);
                                if (isset($changed['textNodes'])) {
                                    foreach ($changed['textNodes'] as $changedNode) {
                                        $element = $this->getContainer($phpWord)->addText($changedNode->nodeValue);
                                        $element->setTrackChange($changed['changed']);
                                    }
                                }
                            }
                        }

                        break;
                    case 'text:list': // List
                        $listItems = $xmlReader->getElements('text:list-item/text:p', $node);
                        foreach ($listItems as $listItem) {
                            // $listStyleName = $xmlReader->getAttribute('text:style-name', $listItem);
                            $this->getContainer($phpWord)->addListItem($listItem->nodeValue, 0);
                        }

                        break;
                    case 'text:tracked-changes':
                        $changedRegions = $xmlReader->getElements('text:changed-region', $node);
                        foreach ($changedRegions as $changedRegion) {
                            $type = ($changedRegion->firstChild->nodeName == 'text:insertion') ? TrackChange::INSERTED : TrackChange::DELETED;
                            $creatorNode = $xmlReader->getElements('office:change-info/dc:creator', $changedRegion->firstChild);
                            $author = $creatorNode[0]->nodeValue;
                            $dateNode = $xmlReader->getElements('office:change-info/dc:date', $changedRegion->firstChild);
                            $date = $dateNode[0]->nodeValue;
                            $date = preg_replace('/\.\d+$/', '', $date);
                            $date = DateTime::createFromFormat('Y-m-d\TH:i:s', $date);
                            $changed = new TrackChange($type, $author, $date);
                            $textNodes = $xmlReader->getElements('text:deletion/text:p', $changedRegion);
                            $trackedChanges[$changedRegion->getAttribute('text:id')] = ['changed' => $changed, 'textNodes' => $textNodes];
                        }

                        break;
                    case 'table:table':
                        $this->readTableNode($xmlReader, $node, $phpWord);

                        break;
                    case 'text:section': // Section
                        // $sectionStyleName = $xmlReader->getAttribute('text:style-name', $listItem);
                        if ($this->cell === null) {
                            $this->section = $phpWord->addSection();
                        }
                        /** @var DOMNodeList<DOMElement> $children */
                        $children = $node->childNodes;
                        $this->processNodes($children, $xmlReader, $phpWord);

                        break;
                }
            }
        }
    }

    /**
     * Read a table: its rows, with the header rows as header rows, and its cells, with their spans.
     */
    private function readTableNode(XMLReader $xmlReader, DOMElement $node, PhpWord $phpWord): void
    {
        $widths = [];
        $columns = 'table:table-column|table:table-columns/table:table-column|table:table-header-columns/table:table-column|table:table-column-group//table:table-column';
        foreach ($xmlReader->getElements($columns, $node) as $column) {
            $width = $this->columnWidths[$column->getAttribute('table:style-name')] ?? null;
            $widths = array_pad($widths, count($widths) + $this->repeat($column, 'table:number-columns-repeated', self::MAX_COLUMNS), $width);
        }

        $table = $this->getContainer($phpWord)->addTable();
        $spans = [];
        $this->readTableRows($xmlReader, $node, $phpWord, $table, false, $widths, $spans);
    }

    /**
     * Read the rows of a table, or of a group of its rows.
     *
     * @param array<int, ?float> $widths the width of each column
     * @param array<int, array{int, int}> $spans the rows left and the columns of each cell spanning rows, by its column
     */
    private function readTableRows(XMLReader $xmlReader, DOMElement $node, PhpWord $phpWord, Table $table, bool $header, array $widths, array &$spans): void
    {
        /** @var DOMElement $child */
        foreach ($node->childNodes as $child) {
            switch ($child->nodeName) {
                case 'table:table-header-rows':
                    $this->readTableRows($xmlReader, $child, $phpWord, $table, true, $widths, $spans);

                    break;
                case 'table:table-rows':
                case 'table:table-row-group':
                    $this->readTableRows($xmlReader, $child, $phpWord, $table, $header, $widths, $spans);

                    break;
                case 'table:table-row':
                    for ($repeat = $this->repeat($child, 'table:number-rows-repeated', self::MAX_ROWS); $repeat > 0; --$repeat) {
                        $this->readTableRow($xmlReader, $child, $phpWord, $table->addRow(null, $header ? ['tblHeader' => true] : null), $widths, $spans);
                    }

                    break;
            }
        }
    }

    /**
     * Read the cells of a row onto the grid of the table, as LibreOffice lays them out: a covered cell
     * only marks a place, each cell takes the next column no cell spanning rows above it holds, and
     * each cell spanning rows is continued in the rows it spans.
     *
     * @param array<int, ?float> $widths
     * @param array<int, array{int, int}> $spans
     */
    private function readTableRow(XMLReader $xmlReader, DOMElement $node, PhpWord $phpWord, Row $row, array $widths, array &$spans): void
    {
        $column = 0;
        /** @var DOMElement $child */
        foreach ($node->childNodes as $child) {
            if ($child->nodeName !== 'table:table-cell') {
                continue;
            }
            for ($repeat = $this->repeat($child, 'table:number-columns-repeated', self::MAX_COLUMNS); $repeat > 0; --$repeat) {
                $column = $this->continueSpans($row, $widths, $spans, $column, false);
                $columns = $this->repeat($child, 'table:number-columns-spanned', self::MAX_COLUMNS);
                // A cell spans no further than the last column
                if ($widths !== []) {
                    $columns = max(1, min($columns, count($widths) - $column));
                }
                $rows = $this->repeat($child, 'table:number-rows-spanned', self::MAX_ROWS);
                $style = ['gridSpan' => $columns > 1 ? $columns : null];
                if ($rows > 1) {
                    $style['vMerge'] = 'restart';
                    $spans[$column] = [$rows - 1, $columns];
                }
                $cell = $row->addCell($this->width($widths, $column, $columns), $style);

                $parent = $this->cell;
                $this->cell = $cell;
                /** @var DOMNodeList<DOMElement> $children */
                $children = $child->childNodes;
                $this->processNodes($children, $xmlReader, $phpWord);
                $this->cell = $parent;

                $column += $columns;
            }
        }
        $this->continueSpans($row, $widths, $spans, $column, true);
    }

    /**
     * Continue the cells spanning rows from the given column: those in the next columns, or all the rest.
     *
     * @param array<int, ?float> $widths
     * @param array<int, array{int, int}> $spans
     */
    private function continueSpans(Row $row, array $widths, array &$spans, int $column, bool $all): int
    {
        ksort($spans);
        foreach ($spans as $start => [$rows, $columns]) {
            if ($start < $column) {
                continue;
            }
            if ($start > $column && !$all) {
                break;
            }
            $row->addCell($this->width($widths, $start, $columns), ['vMerge' => 'continue', 'gridSpan' => $columns > 1 ? $columns : null]);
            if ($rows > 1) {
                $spans[$start] = [$rows - 1, $columns];
            } else {
                unset($spans[$start]);
            }
            $column = $start + $columns;
        }

        return $column;
    }

    /**
     * The width in twips of the columns a cell spans, if all of them have one.
     *
     * @param array<int, ?float> $widths
     */
    private function width(array $widths, int $column, int $columns): ?int
    {
        $spanned = array_slice($widths, $column, $columns);
        if (count($spanned) !== $columns || in_array(null, $spanned, true)) {
            return null;
        }

        // OOXML takes whole twips
        return (int) round(array_sum($spanned));
    }

    /**
     * A count of an element: 1 if it is missing, or above the limit, as LibreOffice takes it.
     */
    private function repeat(DOMElement $node, string $attribute, int $limit): int
    {
        $count = (int) $node->getAttribute($attribute);

        return $count >= 1 && $count <= $limit ? $count : 1;
    }

    /**
     * A length of ODF in twips.
     */
    private function toTwip(string $length): ?float
    {
        if (preg_match('/^(\d+\.?\d*|\.\d+)(cm|mm|in|pt|pc|px)$/', $length) !== 1) {
            return null;
        }
        // The converter does not read a length without its leading zero
        $point = Converter::cssToPoint((string) preg_replace('/^\./', '0.', $length));

        return $point === null ? null : Converter::pointToTwip($point);
    }

    private function getContainer(PhpWord $phpWord): AbstractContainer
    {
        return $this->cell ?? $this->getSection($phpWord);
    }

    private function getSection(PhpWord $phpWord): Section
    {
        $section = $this->section;
        if ($section === null) {
            $section = $this->section = $phpWord->addSection();
        }

        return $section;
    }
}
