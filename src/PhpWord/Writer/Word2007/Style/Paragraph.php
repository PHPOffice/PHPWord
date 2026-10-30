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

namespace PhpOffice\PhpWord\Writer\Word2007\Style;

use InvalidArgumentException;
use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Style\Paragraph as ParagraphStyle;
use PhpOffice\PhpWord\Writer\Word2007\Element\ParagraphAlignment;

/**
 * Paragraph style writer.
 *
 * @since 0.10.0
 */
class Paragraph extends AbstractStyle
{
    /**
     * The children of w:pPr that a caller may hand over to this writer.
     *
     * They are the children that a writer other than this one knows how to write,
     * and the only ones a caller has a reason to write itself.
     *
     * @var string[]
     */
    private const HANDED_OVER_CHILDREN = ['numPr', 'tabs', 'ind'];

    /**
     * Without w:pPr.
     *
     * @var bool
     */
    private $withoutPPR = false;

    /**
     * Is inline in element.
     *
     * @var bool
     */
    private $isInline = false;

    /**
     * Outline level of a heading style, written as w:outlineLvl.
     *
     * @var null|int
     */
    private $outlineLevel;

    /**
     * A child of w:pPr written in place of the one in the style.
     *
     * @var array<string, callable>
     */
    private $children = [];

    /**
     * Write style.
     */
    public function write(): void
    {
        $xmlWriter = $this->getXmlWriter();

        $isStyleName = $this->isInline && null !== $this->style && is_string($this->style);
        if ($isStyleName) {
            if (!$this->withoutPPR) {
                $xmlWriter->startElement('w:pPr');
            }
            $xmlWriter->startElement('w:pStyle');
            $xmlWriter->writeAttribute('w:val', $this->style);
            $xmlWriter->endElement();

            // The properties a caller has taken over come after the name of the
            // style, which is where the schema names them.
            $this->writeNumbering($xmlWriter);
            $this->writeTabs($xmlWriter);
            $this->writeIndentation($xmlWriter);

            if (!$this->withoutPPR) {
                $xmlWriter->endElement();
            }
        } else {
            $this->writeStyle();
        }
    }

    /**
     * Write full style.
     */
    private function writeStyle(): void
    {
        $style = $this->getStyle();
        if (!$style instanceof ParagraphStyle) {
            return;
        }
        $xmlWriter = $this->getXmlWriter();
        $styles = $style->getStyleValues();

        if (!$this->withoutPPR) {
            $xmlWriter->startElement('w:pPr');
        }

        // The children of `w:pPr` come in the order the schema names them. The schema
        // names them in a sequence and not in a set, so a reader that walks the
        // sequence stops at the first child it did not expect: `w:keepNext` belongs
        // before `w:widowControl`, `w:numPr` belongs before `w:pBdr`, `w:tabs`
        // belongs before `w:spacing`, and `w:jc` belongs after `w:ind`. A paragraph
        // whose properties are written in any other order is a paragraph such a
        // reader cannot read.
        //
        // The order below is the one in ECMA-376, in `CT_PPrBase`.

        // Style name
        if ($this->isInline === true) {
            $xmlWriter->writeElementIf($styles['name'] !== null, 'w:pStyle', 'w:val', $styles['name']);
        }

        // Pagination
        $xmlWriter->writeElementIf($styles['pagination']['keepNext'] === true, 'w:keepNext', 'w:val', '1');
        $xmlWriter->writeElementIf($styles['pagination']['keepLines'] === true, 'w:keepLines', 'w:val', '1');
        $xmlWriter->writeElementIf($styles['pagination']['pageBreak'] === true, 'w:pageBreakBefore', 'w:val', '1');
        $xmlWriter->writeElementIf($styles['pagination']['widowControl'] === false, 'w:widowControl', 'w:val', '0');

        // Numbering
        $outlineLevel = $this->writeNumbering($xmlWriter, $styles['numbering']);

        // Border
        if ($style->hasBorder()) {
            $xmlWriter->startElement('w:pBdr');

            $styleWriter = new MarginBorder($xmlWriter);
            $styleWriter->setSizes($style->getBorderSize());
            $styleWriter->setStyles($style->getBorderStyle());
            $styleWriter->setColors($style->getBorderColor());
            $styleWriter->write();

            $xmlWriter->endElement();
        }

        // Shading
        $this->writeChildStyle($xmlWriter, 'Shading', $styles['shading']);

        // Tabs
        $this->writeTabs($xmlWriter, $styles['tabs']);

        // Hyphenation
        $xmlWriter->writeElementIf($styles['suppressAutoHyphens'] === true, 'w:suppressAutoHyphens');

        // Right to left
        $xmlWriter->writeElementIf($styles['bidi'] === true, 'w:bidi');

        // Spacing
        $this->writeChildStyle($xmlWriter, 'Spacing', $styles['spacing']);

        // Indentation
        $this->writeIndentation($xmlWriter, $styles['indentation']);

        //Paragraph contextualSpacing
        $xmlWriter->writeElementIf($styles['contextualSpacing'] === true, 'w:contextualSpacing');

        // Paragraph alignment
        if ('' !== $styles['alignment']) {
            $paragraphAlignment = new ParagraphAlignment($styles['alignment']);
            $xmlWriter->startElement($paragraphAlignment->getName());
            foreach ($paragraphAlignment->getAttributes() as $attributeName => $attributeValue) {
                $xmlWriter->writeAttribute($attributeName, $attributeValue);
            }
            $xmlWriter->endElement();
        }

        //Paragraph textAlignment
        $xmlWriter->writeElementIf($styles['textAlignment'] !== null, 'w:textAlignment', 'w:val', $styles['textAlignment']);

        // Outline level
        if ($this->outlineLevel !== null) {
            $outlineLevel = $this->outlineLevel;
        }
        $xmlWriter->writeElementIf($outlineLevel !== null, 'w:outlineLvl', 'w:val', $outlineLevel);

        if (!$this->withoutPPR) {
            $xmlWriter->endElement(); // w:pPr
        }
    }

    /**
     * Write tabs.
     *
     * @param Style\Tab[] $tabs
     */
    private function writeTabs(XMLWriter $xmlWriter, $tabs = []): void
    {
        if (isset($this->children['tabs'])) {
            call_user_func($this->children['tabs']);

            return;
        }

        if (!empty($tabs)) {
            $xmlWriter->startElement('w:tabs');
            foreach ($tabs as $tab) {
                $styleWriter = new Tab($xmlWriter, $tab);
                $styleWriter->write();
            }
            $xmlWriter->endElement();
        }
    }

    /**
     * Write indentation.
     *
     * @param null|Style\Indentation $indentation
     */
    private function writeIndentation(XMLWriter $xmlWriter, $indentation = null): void
    {
        if (isset($this->children['ind'])) {
            call_user_func($this->children['ind']);

            return;
        }

        $this->writeChildStyle($xmlWriter, 'Indentation', $indentation);
    }

    /**
     * Write numbering.
     *
     * @param null|array $numbering
     *
     * @return null|int the outline level the numbering implies, if any
     */
    private function writeNumbering(XMLWriter $xmlWriter, $numbering = null): ?int
    {
        if (isset($this->children['numPr'])) {
            call_user_func($this->children['numPr']);

            return null;
        }

        if ($numbering === null) {
            return null;
        }

        $numStyle = $numbering['style'];
        $numLevel = $numbering['level'];

        /** @var Style\Numbering $numbering */
        $numbering = Style::getStyle($numStyle);
        if ($numStyle !== null && $numbering !== null) {
            $xmlWriter->startElement('w:numPr');
            $xmlWriter->startElement('w:ilvl');
            $xmlWriter->writeAttribute('w:val', $numLevel);
            $xmlWriter->endElement(); // w:ilvl
            $xmlWriter->startElement('w:numId');
            $xmlWriter->writeAttribute('w:val', $numbering->getIndex());
            $xmlWriter->endElement(); // w:numId
            $xmlWriter->endElement(); // w:numPr

            return $numLevel;
        }

        return null;
    }

    /**
     * Set the outline level of a heading style.
     */
    public function setOutlineLevel(?int $value): void
    {
        $this->outlineLevel = $value;
    }

    /**
     * Set without w:pPr.
     *
     * @param bool $value
     */
    public function setWithoutPPR($value): void
    {
        $this->withoutPPR = $value;
    }

    /**
     * Write a child of w:pPr in place of the one in the style.
     *
     * A writer that writes part of a w:pPr hands its part to the paragraph style
     * writer, which writes it where the schema names it. The two then agree on the
     * order, because there is only one order.
     *
     * Only `numPr`, `tabs` and `ind` can be handed over; a writer that hands over
     * any other child would have its part dropped, and a writer that is not told
     * about a child writes the one of the style.
     *
     * @param string $name The name of the element, as the schema names it
     * @param callable $writer Writes the element
     */
    public function setChild(string $name, callable $writer): void
    {
        if (!in_array($name, self::HANDED_OVER_CHILDREN, true)) {
            throw new InvalidArgumentException(sprintf(
                'The child "%s" of w:pPr cannot be handed over to the paragraph style writer;'
                    . ' only "%s" can.',
                $name,
                implode('", "', self::HANDED_OVER_CHILDREN)
            ));
        }

        $this->children[$name] = $writer;
    }

    /**
     * Set is inline.
     *
     * @param bool $value
     */
    public function setIsInline($value): void
    {
        $this->isInline = $value;
    }
}
