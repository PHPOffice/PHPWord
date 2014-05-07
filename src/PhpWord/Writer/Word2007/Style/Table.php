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
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Style;

/**
 * Table style writer
 *
 * @since 0.10.0
 */
class Table extends AbstractStyle
{
    /**
     * Is full style
     *
     * @var bool
     */
    private $isFullStyle = true;

    /**
     * Write style
     */
    public function write()
    {
        if (is_null($style = $this->getStyle())) {
            return;
        }
        $xmlWriter = $this->getXmlWriter();
        $hasBorders = $style->hasBorders();
        $hasMargins = $style->hasMargins();

        if ($hasMargins || $hasBorders) {
            $xmlWriter->startElement('w:tblPr');
            if ($hasMargins) {
                $mbWriter = new MarginBorder($xmlWriter);
                $mbWriter->setSizes($style->getCellMargin());

                $xmlWriter->startElement('w:tblCellMar');
                $mbWriter->write();
                $xmlWriter->endElement(); // w:tblCellMar
            }
            if ($hasBorders) {
                $mbWriter = new MarginBorder($xmlWriter);
                $mbWriter->setSizes($style->getBorderSize());
                $mbWriter->setColors($style->getBorderColor());

                $xmlWriter->startElement('w:tblBorders');
                $mbWriter->write();
                $xmlWriter->endElement(); // w:tblBorders
            }
            $xmlWriter->endElement(); // w:tblPr
        }
        // Only write background color and first row for full style
        if ($this->isFullStyle) {
            // Background color
            if (!is_null($style->getShading())) {
                $xmlWriter->startElement('w:tcPr');
                $styleWriter = new Shading($xmlWriter, $style->getShading());
                $styleWriter->write();
                $xmlWriter->endElement();
            }
            // First Row
            $firstRow = $style->getFirstRow();
            if ($firstRow instanceof \PhpOffice\PhpWord\Style\Table) {
                $this->writeFirstRow($firstRow);
            }
        }
    }

    /**
     * Set is full style
     *
     * @param bool $value
     */
    public function setIsFullStyle($value)
    {
        $this->isFullStyle = $value;
    }

    /**
     * Write row style
     *
     * @param string $type
     */
    private function writeFirstRow(\PhpOffice\PhpWord\Style\Table $style)
    {
        $xmlWriter = $this->getXmlWriter();

        $xmlWriter->startElement('w:tblStylePr');
        $xmlWriter->writeAttribute('w:type', 'firstRow');
        $xmlWriter->startElement('w:tcPr');
        if (!is_null($style->getShading())) {
            $styleWriter = new Shading($xmlWriter, $style->getShading());
            $styleWriter->write();
        }

        // Borders
        if ($style->hasBorders()) {
            $mbWriter = new MarginBorder($xmlWriter);
            $mbWriter->setSizes($style->getBorderSize());
            $mbWriter->setColors($style->getBorderColor());

            $xmlWriter->startElement('w:tcBorders');
            $mbWriter->write();
            $xmlWriter->endElement(); // w:tcBorders
        }

        $xmlWriter->endElement(); // w:tcPr
        $xmlWriter->endElement(); // w:tblStylePr
    }
}
