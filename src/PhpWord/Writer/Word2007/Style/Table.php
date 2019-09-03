<?php
declare(strict_types=1);
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
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Style\Lengths\Absolute;
use PhpOffice\PhpWord\Style\Lengths\Auto;
use PhpOffice\PhpWord\Style\Lengths\Length;
use PhpOffice\PhpWord\Style\Lengths\Percent;
use PhpOffice\PhpWord\Style\Table as TableStyle;
use PhpOffice\PhpWord\Writer\Word2007\Element\TableAlignment;

/**
 * Table style writer
 *
 * @since 0.10.0
 */
class Table extends AbstractStyle
{
    use Border;

    /**
     * @var Length Table width
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
                $this->writeTblWidth($xmlWriter, 'w:tblW', $this->width);
            }
            $xmlWriter->endElement();
        }
    }

    /**
     * Write full style.
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

        $this->writeTblWidth($xmlWriter, 'w:tblW', $style->getWidth());
        $this->writeTblWidth($xmlWriter, 'w:tblCellSpacing', $style->getCellSpacing());
        $this->writeIndent($xmlWriter, $style);
        $this->writeLayout($xmlWriter, $style->getLayout());

        // Position
        $styleWriter = new TablePosition($xmlWriter, $style->getPosition());
        $styleWriter->write();

        //Right to left
        $xmlWriter->writeElementIf($style->isBidiVisual() !== null, 'w:bidiVisual', 'w:val', $this->writeOnOf($style->isBidiVisual()));

        $this->writeMargin($xmlWriter, $style);
        $this->writeBorders($xmlWriter, $style);

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
     */
    private function writeMargin(XMLWriter $xmlWriter, TableStyle $style)
    {
        if ($style->hasMargin()) {
            $xmlWriter->startElement('w:tblCellMar');

            $sides = array('top', 'bottom', 'start', 'end');
            foreach ($style->getCellMargin() as $key => $width) {
                $width = $width->toInt('twip') ?? 0;

                $xmlWriter->startElement('w:' . $sides[$key]);
                $xmlWriter->writeAttributeIf($width !== 0, 'w:w', $width);
                $xmlWriter->writeAttribute('w:type', $width === 0 ? 'nil' : 'dxa');
                $xmlWriter->endElement();
            }

            $xmlWriter->endElement();
        }
    }

    /**
     * Write border.
     */
    private function writeBorders(XMLWriter $xmlWriter, TableStyle $style)
    {
        if (!$style->hasBorder()) {
            return;
        }

        $xmlWriter->startElement('w:tblBorders');

        foreach ($style->getBorders() as $side => $border) {
            $this->writeBorder($xmlWriter, $side, $border);
        }

        $xmlWriter->endElement(); // w:tblBorders
    }

    /**
     * Writes a table width
     *
     * @param string $elementName
     */
    private function writeTblWidth(XMLWriter $xmlWriter, $elementName, Length $width)
    {
        if ($width instanceof Absolute) {
            $width = $width->toFloat('twip');
            $unit = 'dxa';
        } elseif ($width instanceof Percent) {
            $width = $width->toFloat();
            $unit = 'pct';
        } elseif ($width instanceof Auto) {
            $width = null;
            $unit = 'auto';
        } else {
            throw new Exception('Unsupported width `' . get_class($width) . '` provided');
        }

        if ($width === null && $unit !== 'auto') {
            return;
        } elseif ($width !== null && $width == 0) {
            $width = null;
            $unit = 'nil';
        }

        $xmlWriter->startElement($elementName);
        $xmlWriter->writeAttributeIf(null !== $width, 'w:w', $width);
        $xmlWriter->writeAttribute('w:type', $unit);
        $xmlWriter->endElement();
    }

    /**
     * Write row style.
     */
    private function writeFirstRow(XMLWriter $xmlWriter, TableStyle $style)
    {
        $xmlWriter->startElement('w:tblStylePr');
        $xmlWriter->writeAttribute('w:type', 'firstRow');
        $xmlWriter->startElement('w:tcPr');

        $this->writeBorders($xmlWriter, $style);
        $this->writeShading($xmlWriter, $style);

        $xmlWriter->endElement(); // w:tcPr
        $xmlWriter->endElement(); // w:tblStylePr
    }

    /**
     * Write shading.
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
     */
    public function setWidth(Length $value)
    {
        $this->width = $value;
    }

    private function writeIndent(XMLWriter $xmlWriter, TableStyle $style)
    {
        $this->writeTblWidth($xmlWriter, 'w:tblInd', $style->getIndent());
    }
}
