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

use PhpOffice\PhpWord\Shared\XMLWriter;
use PhpOffice\PhpWord\Style\Alignment as AlignmentStyle;
use PhpOffice\PhpWord\Style\Table as TableStyle;

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
     *
     * @return void
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
            if ($this->width !== null) {
                $this->writeWidth($xmlWriter, $this->width, 'pct');
            }
            $xmlWriter->endElement();
        }
    }

    /**
     * Write full style.
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Style\Table $style
     * @return void
     */
    private function writeStyle(XMLWriter $xmlWriter, TableStyle $style)
    {
        // w:tblPr
        $xmlWriter->startElement('w:tblPr');

        // Alignment
        $styleWriter = new Alignment($xmlWriter, new AlignmentStyle(array('value' => $style->getAlign())));
        $styleWriter->write();

        $this->writeWidth($xmlWriter, $style->getWidth(), $style->getUnit());
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
     * Write width.
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param int $width
     * @param string $unit
     * @return void
     */
    private function writeWidth(XMLWriter $xmlWriter, $width, $unit)
    {
        $xmlWriter->startElement('w:tblW');
        $xmlWriter->writeAttribute('w:w', $width);
        $xmlWriter->writeAttribute('w:type', $unit);
        $xmlWriter->endElement(); // w:tblW
    }

    /**
     * Write margin.
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Style\Table $style
     * @return void
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
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Style\Table $style
     * @return void
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
     * Write row style.
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Style\Table $style
     * @return void
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
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param \PhpOffice\PhpWord\Style\Table $style
     * @return void
     */
    private function writeShading(XMLWriter $xmlWriter, TableStyle $style)
    {
        if ($style->getShading() !== null) {
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
     * @return void
     */
    public function setWidth($value = null)
    {
        $this->width = $value;
    }
}
