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
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2016 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Element;

use PhpOffice\Common\XMLWriter;
use PhpOffice\PhpWord\Element\Cell as CellElement;
use PhpOffice\PhpWord\Element\Row as RowElement;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Element\Table as TableElement;
use PhpOffice\PhpWord\Element\TextBreak as TextBreakElement;
use PhpOffice\PhpWord\Style\Table as TableStyle;
use PhpOffice\PhpWord\Style\Cell as CellStyle;
use PhpOffice\PhpWord\Style\Row as RowStyle;
use PhpOffice\PhpWord\Writer\Word2007\Style\Cell as CellStyleWriter;
use PhpOffice\PhpWord\Writer\Word2007\Style\Row as RowStyleWriter;
use PhpOffice\PhpWord\Writer\Word2007\Style\Table as TableStyleWriter;
use PhpOffice\PhpWord\Writer\Word2007\Element\TextBreak as TextBreakWriter;

/**
 * Table element writer
 *
 * @since 0.10.0
 */
class Table extends AbstractElement
{
    /**
     * Write element.
     *
     * @return void
     */
    public function write()
    {
        $xmlWriter = $this->getXmlWriter();
        $element = $this->getElement();
        if (!$element instanceof TableElement) {
            return;
        }

        $rows = $element->getRows();
        $rowCount = count($rows);

        if ($rowCount > 0) {
            // table margin top
            $this->writeMarginTop($xmlWriter, $element);

            $xmlWriter->startElement('w:tbl');

            // Write columns
            $this->writeColumns($xmlWriter, $element);

            // Write style
            $styleWriter = new TableStyleWriter($xmlWriter, $element->getStyle());
            $styleWriter->setWidth($element->getWidth());
            $styleWriter->write();

            // Write rows
            for ($i = 0; $i < $rowCount; $i++) {
                $this->writeRow($xmlWriter, $rows[$i]);
            }

            $xmlWriter->endElement(); // w:tbl

            // table margin bottom
            $this->writeMarginBottom($xmlWriter, $element);
        }
    }

    /**
     * Write column.
     *
     * @param \PhpOffice\Common\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Element\Table $element
     * @return void
     */
    private function writeColumns(XMLWriter $xmlWriter, TableElement $element)
    {
        $cellWidths = $this->getCellWidths($element);
        $xmlWriter->startElement('w:tblGrid');
        foreach ($cellWidths as $width) {
            $xmlWriter->startElement('w:gridCol');
            if ($width !== null) {
                $xmlWriter->writeAttribute('w:w', $width);
                $xmlWriter->writeAttribute('w:type', 'dxa');
            }
            $xmlWriter->endElement();
        }
        $xmlWriter->endElement(); // w:tblGrid
    }

    /**
     * @param TableElement $element
     * @return array
     */
    private function getCellWidths(TableElement $element)
    {
        $this->updateCellWidths($element);

        if ($element->hasDifferentCellWidths()) {
            return $this->getDifferentCellWidths($element);
        }

        $rows = $element->getRows();
        $rowCount = count($rows);

        $cellWidths = array();
        for ($i = 0; $i < $rowCount; $i++) {
            $row = $rows[$i];
            $cells = $row->getCells();
            if (count($cells) <= count($cellWidths)) {
                continue;
            }
            $cellWidths = array();
            foreach ($cells as $cell) {
                $cellWidths[] = $cell->getWidth();
            }
        }

        return $cellWidths;
    }

    /**
     * @param TableElement $element
     * @return array
     */
    private function getDifferentCellWidths(TableElement $element)
    {
        $cellWidths = array();
        $rowCellWidths = array();

        $rows = $element->getRows();
        foreach ($rows as $row) {
            $rowWidth = 0;
            $cells = $row->getCells();
            foreach ($cells as $cell) {
                $cellWidth = $cell->getWidth();
                $rowWidth += $cellWidth;

                $rowCellWidths[] = ceil($rowWidth);
            }
        }
        $rowCellWidths = array_unique($rowCellWidths);
        sort($rowCellWidths);

        $prevCellWidth = 0;
        foreach ($rowCellWidths as $rowCellWidth) {
            if ($rowCellWidth > $prevCellWidth) {
                $cellWidths[] = abs($rowCellWidth - $prevCellWidth);
                $prevCellWidth = $rowCellWidth;
            }
        }

        $countCellWidths = count($cellWidths);

        // set grid spans
        foreach ($rows as $row) {
            $index = 0;
            $cells = $row->getCells();
            foreach ($cells as $cell) {
                $cellWidth = ceil($cell->getWidth());
                $gridSpan = 0;
                $totalColumnWidth = 0;

                for ($i = $index; $i < $countCellWidths; $i++) {
                    $totalColumnWidth += $cellWidths[$i];

                    if ($totalColumnWidth <= $cellWidth) {
                        $gridSpan++;
                    }
                    else {
                        $index = $i++;
                        break;
                    }
                }

                if ($gridSpan > 1) {
                    $cell->getStyle()->setGridSpan($gridSpan);
                }
            }
        }

        return $cellWidths;
    }

    /**
     * @param TableElement $element
     * @return bool
     */
    private function updateCellWidths(TableElement $element)
    {
        $tableMarginRight = null;
        $tableStyle = $element->getStyle();
        if ($tableStyle instanceof TableStyle) {
            $tableMarginRight = $tableStyle->getMarginRight();
        }

        if ($tableMarginRight === null) {
            return false;
        }

        $tableMarginRight = $this->calcMarginRight($element, $tableStyle);
        if ($tableMarginRight === 0) {
            return false;
        }

        $rows = $element->getRows();
        foreach ($rows as $row) {
            $cells = $row->getCells();
            $countCells = count($cells);
            $tableWidthPart = $tableMarginRight / $countCells;
            foreach ($cells as $cell) {
                $cell->setWidth($cell->getWidth() - $tableWidthPart);
            }
        }

        return true;
    }

    /**
     * @param TableElement $element
     * @return int|null
     */
    private function getWidthByCells(TableElement $element)
    {
        $tableWidth = null;

        $rows = $element->getRows();
        foreach ($rows as $row) {
            $width = 0;
            $cells = $row->getCells();
            foreach ($cells as $cell) {
                $width += $cell->getWidth();
            }
            if ($width > $tableWidth) {
                $tableWidth = $width;
            }
        }

        return $tableWidth;
    }

    /**
     * @param TableElement $element
     * @param TableStyle $tableStyle
     * @return float|int
     */
    private function calcMarginRight(TableElement $element, TableStyle $tableStyle)
    {
        $pageInnerWidth = 0;
        $parentElement = $element->getParentContainerElement();
        if ($parentElement instanceof Section) {
            $sectionStyle = $parentElement->getStyle();
            if ($sectionStyle) {
                $pageSizeW = $sectionStyle->getPageSizeW();
                $pageInnerWidth = $pageSizeW - $sectionStyle->getMarginLeft() - $sectionStyle->getMarginRight();
            }
        }

        $tableWidth = $element->getWidth();
        if ($tableWidth === null) {
            $tableWidth = $this->getWidthByCells($element);
        }

        $tableMarginLeft = $tableStyle->getMarginLeft();
        $tableMarginRight = $tableStyle->getMarginRight();
        $pageMarginRight = abs($pageInnerWidth - ($tableMarginLeft + $tableWidth));
        if ($pageMarginRight >= $tableMarginRight) {
            return 0;
        }

        $tableMarginRight -= $pageMarginRight;

        return $tableMarginRight;
    }

    /**
     * Write row.
     *
     * @param \PhpOffice\Common\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Element\Row $row
     * @return void
     */
    private function writeRow(XMLWriter $xmlWriter, RowElement $row)
    {
        $xmlWriter->startElement('w:tr');

        // Write style
        $rowStyle = $row->getStyle();
        if ($rowStyle instanceof RowStyle) {
            $styleWriter = new RowStyleWriter($xmlWriter, $rowStyle);
            $styleWriter->setHeight($row->getHeight());
            $styleWriter->write();
        }

        // Write cells
        foreach ($row->getCells() as $cell) {
            $this->writeCell($xmlWriter, $cell);
        }

        $xmlWriter->endElement(); // w:tr
    }

    /**
     * Write cell.
     *
     * @param \PhpOffice\Common\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Element\Cell $cell
     * @return void
     */
    private function writeCell(XMLWriter $xmlWriter, CellElement $cell)
    {

        $xmlWriter->startElement('w:tc');

        // Write style
        $cellStyle = $cell->getStyle();
        if ($cellStyle instanceof CellStyle) {
            $styleWriter = new CellStyleWriter($xmlWriter, $cellStyle);
            $styleWriter->setWidth($cell->getWidth());
            $styleWriter->write();
        }

        // Write content
        $containerWriter = new Container($xmlWriter, $cell);
        $containerWriter->write();

        $xmlWriter->endElement(); // w:tc
    }

    /**
     * @param XMLWriter $xmlWriter
     * @param TableElement $element
     */
    private function writeMarginTop(XMLWriter $xmlWriter, TableElement $element)
    {
        $style = $element->getStyle();
        if ($style instanceof TableStyle) {
            $marginTop = $style->getMarginTop();
            if ($marginTop !== null) {
                $paragraphStyle = [
                    'lineHeight' => $marginTop, // twips
                    'spaceBefore' => 0,
                    'spaceAfter' => $marginTop, // twips
                    'spacing' => 0
                ];
                $this->writeTextBreak($xmlWriter, $paragraphStyle);
            }
        }
    }

    /**
     * @param XMLWriter $xmlWriter
     * @param TableElement $element
     */
    private function writeMarginBottom(XMLWriter $xmlWriter, TableElement $element)
    {
        $style = $element->getStyle();
        if ($style instanceof TableStyle) {
            $marginBottom = $style->getMarginBottom();
            if ($marginBottom !== null) {
                $paragraphStyle = [
                    'lineHeight' => $marginBottom, // twips
                    'spaceBefore' => $marginBottom, // twips
                    'spaceAfter' => 0,
                    'spacing' => 0
                ];
                $this->writeTextBreak($xmlWriter, $paragraphStyle);
            }
        }
    }

    /**
     * @param $xmlWriter
     * @param $paragraphStyle
     */
    private function writeTextBreak($xmlWriter, $paragraphStyle)
    {
        $textBreak = new TextBreakElement(null, $paragraphStyle);
        $textBreakWriter = new TextBreakWriter($xmlWriter, $textBreak);
        $textBreakWriter->write();
    }
}
