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

use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\Style\Table as TableStyle;
use PhpOffice\PhpWord\Writer\Word2007\Element\TableAlignment;

/**
 * Table style writer.
 *
 * @since 0.10.0
 */
class Table extends AbstractStyle
{
    /**
     * @var int Table width
     */
    private $width;

    /**
     * Whether the properties belong to a table in a document or to a table style.
     *
     * The two are written by the same writer and they are not the same thing.
     * `CT_Tbl` is `tblPr, tblGrid, rows` and has room for the properties of the
     * table alone, while `CT_Style` has a `w:tcPr` and a `w:tblStylePr` of its own.
     *
     * @var bool
     */
    private $isInline = false;

    /**
     * Write style.
     */
    public function write(): void
    {
        $style = $this->getStyle();
        $xmlWriter = $this->getXmlWriter();

        if ($style instanceof TableStyle) {
            $this->writeStyle($xmlWriter, $style);
        } elseif (is_string($style)) {
            $xmlWriter->startElement('w:tblPr');
            $xmlWriter->startElement('w:tblStyle');
            $xmlWriter->writeAttribute('w:val', $style);
            $xmlWriter->endElement();
            if (null !== $this->width) {
                $this->writeTblWidth($xmlWriter, 'w:tblW', TblWidth::PERCENT, $this->width);
            }
            $xmlWriter->endElement();
        }
    }

    /**
     * Write full style.
     */
    private function writeStyle(XMLWriter $xmlWriter, TableStyle $style): void
    {
        // w:tblPr
        $xmlWriter->startElement('w:tblPr');

        // Table alignment
        if ('' !== $style->getAlignment()) {
            $tableAlignment = new TableAlignment($style->getAlignment());
            $xmlWriter->startElement($tableAlignment->getName());
            foreach ($tableAlignment->getAttributes() as $attributeName => $attributeValue) {
                $xmlWriter->writeAttribute($attributeName, $attributeValue);
            }
            $xmlWriter->endElement();
        }

        $this->writeTblWidth($xmlWriter, 'w:tblW', $style->getUnit(), $style->getWidth());
        $this->writeTblWidth($xmlWriter, 'w:tblCellSpacing', TblWidth::TWIP, $style->getCellSpacing());
        $this->writeIndent($xmlWriter, $style);
        $this->writeLayout($xmlWriter, $style->getLayout());

        // Position
        $styleWriter = new TablePosition($xmlWriter, $style->getPosition());
        $styleWriter->write();

        //Right to left
        // The SDK reads the value of `w:bidiVisual` as an enumeration of `on` and
        // `off` and refuses to read `1`, which the schema's `ST_OnOff` allows.
        $xmlWriter->writeElementIf($style->isBidiVisual() !== null, 'w:bidiVisual', 'w:val', $style->isBidiVisual() ? 'on' : 'off');

        $this->writeMargin($xmlWriter, $style);
        $this->writeBorder($xmlWriter, $style);

        if ($this->isInline) {
            // The shading of a table is the shading of the table, and the table's
            // properties are where the schema names it: `w:shd` is a child of
            // `w:tblPr`, and `w:tblPr` is a child of `w:tbl`.
            $this->writeShading($xmlWriter, $style);
        }

        $xmlWriter->endElement(); // w:tblPr

        if ($this->isInline) {
            // A table in a document has the grid and the rows after its
            // properties and nothing else: `CT_Tbl` names no `w:tcPr` and no
            // `w:tblStylePr`, and a reader that walks it stops at the first child
            // it did not expect.
            return;
        }

        // The cells of a table style, and the first row of it. The shading of a
        // table style is the shading of its cells, which is where it was and
        // where a reader looks for it.
        $xmlWriter->startElement('w:tcPr');
        $this->writeShading($xmlWriter, $style);
        $xmlWriter->endElement(); // w:tcPr

        $firstRow = $style->getFirstRow();
        if ($firstRow instanceof TableStyle) {
            $this->writeFirstRow($xmlWriter, $firstRow);
        }
    }

    /**
     * Enable/Disable automatic resizing of the table.
     *
     * @param string $layout autofit / fixed
     */
    private function writeLayout(XMLWriter $xmlWriter, $layout): void
    {
        $xmlWriter->startElement('w:tblLayout');
        $xmlWriter->writeAttribute('w:type', $layout);
        $xmlWriter->endElement(); // w:tblLayout
    }

    /**
     * Write margin.
     */
    private function writeMargin(XMLWriter $xmlWriter, TableStyle $style): void
    {
        if ($style->hasMargin()) {
            $xmlWriter->startElement('w:tblCellMar');

            $styleWriter = new MarginBorder($xmlWriter);
            $styleWriter->setSizes($style->getCellMargin());
            $styleWriter->write();

            $xmlWriter->endElement(); // w:tblCellMar
        }
    }

    /**
     * Set whether the properties belong to a table in a document.
     *
     * @param bool $value
     */
    public function setIsInline($value): void
    {
        $this->isInline = $value;
    }

    /**
     * Write border.
     *
     * @param string $elementName w:tblBorders for a table, w:tcBorders for a cell
     */
    private function writeBorder(XMLWriter $xmlWriter, TableStyle $style, $elementName = 'w:tblBorders'): void
    {
        if ($style->hasBorder()) {
            $xmlWriter->startElement($elementName);

            $styleWriter = new MarginBorder($xmlWriter);
            $styleWriter->setSizes($style->getBorderSize());
            $styleWriter->setColors($style->getBorderColor());
            $styleWriter->write();

            $xmlWriter->endElement(); // w:tblBorders or w:tcBorders
        }
    }

    /**
     * Writes a table width.
     *
     * @param string $elementName
     * @param string $unit
     * @param null|float|int $width
     */
    private function writeTblWidth(XMLWriter $xmlWriter, $elementName, $unit, $width = null): void
    {
        if (null === $width) {
            return;
        }
        $xmlWriter->startElement($elementName);
        $xmlWriter->writeAttribute('w:w', $width);
        $xmlWriter->writeAttribute('w:type', $unit);
        $xmlWriter->endElement();
    }

    /**
     * Write row style.
     */
    private function writeFirstRow(XMLWriter $xmlWriter, TableStyle $style): void
    {
        $xmlWriter->startElement('w:tblStylePr');
        $xmlWriter->writeAttribute('w:type', 'firstRow');
        $xmlWriter->startElement('w:tcPr');

        $this->writeBorder($xmlWriter, $style, 'w:tcBorders');
        $this->writeShading($xmlWriter, $style);

        $xmlWriter->endElement(); // w:tcPr
        $xmlWriter->endElement(); // w:tblStylePr
    }

    /**
     * Write shading.
     *
     * The `w:shd` belongs to the properties of the table or of the cell, and the
     * caller has opened them.
     */
    private function writeShading(XMLWriter $xmlWriter, TableStyle $style): void
    {
        if (null !== $style->getShading()) {
            $styleWriter = new Shading($xmlWriter, $style->getShading());
            $styleWriter->write();
        }
    }

    /**
     * Set width.
     *
     * @param int $value
     */
    public function setWidth($value = null): void
    {
        $this->width = $value;
    }

    private function writeIndent(XMLWriter $xmlWriter, TableStyle $style): void
    {
        $indent = $style->getIndent();

        if ($indent === null) {
            return;
        }

        $this->writeTblWidth($xmlWriter, 'w:tblInd', $indent->getType(), $indent->getValue());
    }
}
