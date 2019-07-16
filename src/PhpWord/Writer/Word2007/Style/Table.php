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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Style;

use PhpOffice\Common\XMLWriter;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\Style\Table as TableStyle;
use PhpOffice\PhpWord\Writer\Word2007\Element\TableAlignment;

/**
 * Table style writer
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
     * Write style.
     */
    public function write()
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
     *
     * @param \PhpOffice\Common\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Style\Table $style
     */
    private function writeStyle(XMLWriter $xmlWriter, TableStyle $style)
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
        $xmlWriter->writeElementIf($style->isBidiVisual() !== null, 'w:bidiVisual', 'w:val', $this->writeOnOf($style->isBidiVisual()));

        $this->writeMargin($xmlWriter, $style);
        $this->writeBorder($xmlWriter, $style);

        $xmlWriter->endElement(); // w:tblPr

        $this->writeShading($xmlWriter, $style);

        // First row style
        $firstRow = $style->getFirstRow();
        if ($firstRow instanceof TableStyle) {
            $this->writeFirstRow($xmlWriter, $firstRow);
        }
    }

    /**
     * Enable/Disable automatic resizing of the table
     *
     * @param \PhpOffice\Common\XMLWriter $xmlWriter
     * @param string $layout autofit / fixed
     */
    private function writeLayout(XMLWriter $xmlWriter, $layout)
    {
        $xmlWriter->startElement('w:tblLayout');
        $xmlWriter->writeAttribute('w:type', $layout);
        $xmlWriter->endElement(); // w:tblLayout
    }

    /**
     * Write margin.
     *
     * @param \PhpOffice\Common\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Style\Table $style
     */
    private function writeMargin(XMLWriter $xmlWriter, TableStyle $style)
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
     * Write border.
     *
     * @param \PhpOffice\Common\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Style\Table $style
     */
    private function writeBorder(XMLWriter $xmlWriter, TableStyle $style)
    {
        if ($style->hasBorder()) {
            $xmlWriter->startElement('w:tblBorders');

            $styleWriter = new MarginBorder($xmlWriter);
            $styleWriter->setSizes($style->getBorderSize());
            $styleWriter->setColors($style->getBorderColor());
            $styleWriter->write();

            $xmlWriter->endElement(); // w:tblBorders
        }
    }

    /**
     * Writes a table width
     *
     * @param \PhpOffice\Common\XMLWriter $xmlWriter
     * @param string $elementName
     * @param string $unit
     * @param int|float $width
     */
    private function writeTblWidth(XMLWriter $xmlWriter, $elementName, $unit, $width = null)
    {
        if (null === $width) {
            return;
        }
        $xmlWriter->startElement($elementName);
        $xmlWriter->writeAttributeIf(null !== $width, 'w:w', $width);
        $xmlWriter->writeAttribute('w:type', $unit);
        $xmlWriter->endElement();
    }

    /**
     * Write row style.
     *
     * @param \PhpOffice\Common\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Style\Table $style
     */
    private function writeFirstRow(XMLWriter $xmlWriter, TableStyle $style)
    {
        $xmlWriter->startElement('w:tblStylePr');
        $xmlWriter->writeAttribute('w:type', 'firstRow');
        $xmlWriter->startElement('w:tcPr');

        $this->writeBorder($xmlWriter, $style);
        $this->writeShading($xmlWriter, $style);

        $xmlWriter->endElement(); // w:tcPr
        $xmlWriter->endElement(); // w:tblStylePr
    }

    /**
     * Write shading.
     *
     * @param \PhpOffice\Common\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Style\Table $style
     */
    private function writeShading(XMLWriter $xmlWriter, TableStyle $style)
    {
        if (null !== $style->getShading()) {
            $xmlWriter->startElement('w:tcPr');

            $styleWriter = new Shading($xmlWriter, $style->getShading());
            $styleWriter->write();

            $xmlWriter->endElement();
        }
    }

    /**
     * Set width.
     *
     * @param int $value
     */
    public function setWidth($value = null)
    {
        $this->width = $value;
    }

    /**
     * @param XMLWriter $xmlWriter
     * @param TableStyle $style
     */
    private function writeIndent(XMLWriter $xmlWriter, TableStyle $style)
    {
        $indent = $style->getIndent();

        if ($indent === null) {
            return;
        }

        $this->writeTblWidth($xmlWriter, 'w:tblInd', $indent->getType(), $indent->getValue());
    }
}
