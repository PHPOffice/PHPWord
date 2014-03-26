<?php
/**
 * PHPWord
 *
 * Copyright (c) 2014 PHPWord
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @copyright  Copyright (c) 2014 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.9.0
 */

namespace PhpOffice\PhpWord\Writer\Word2007;

use PhpOffice\PhpWord\Section\Footer\PreserveText;
use PhpOffice\PhpWord\Section\Image;
use PhpOffice\PhpWord\Section\Table;
use PhpOffice\PhpWord\Section\Text;
use PhpOffice\PhpWord\Section\TextBreak;
use PhpOffice\PhpWord\Section\TextRun;
use PhpOffice\PhpWord\Shared\XMLWriter;

/**
 * Word2007 header part writer
 */
class Header extends Base
{
    /**
     * Write word/headerx.xml
     *
     * @param PhpOffice\PhpWord\Section\Header $header
     */
    public function writeHeader(\PhpOffice\PhpWord\Section\Header $header)
    {
        // Create XML writer
        if ($this->getParentWriter()->getUseDiskCaching()) {
            $xmlWriter = new XMLWriter(XMLWriter::STORAGE_DISK, $this->getParentWriter()->getDiskCachingDirectory());
        } else {
            $xmlWriter = new XMLWriter(XMLWriter::STORAGE_MEMORY);
        }

        // XML header
        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');

        $xmlWriter->startElement('w:hdr');
        $xmlWriter->writeAttribute('xmlns:ve', 'http://schemas.openxmlformats.org/markup-compatibility/2006');
        $xmlWriter->writeAttribute('xmlns:o', 'urn:schemas-microsoft-com:office:office');
        $xmlWriter->writeAttribute('xmlns:r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');
        $xmlWriter->writeAttribute('xmlns:m', 'http://schemas.openxmlformats.org/officeDocument/2006/math');
        $xmlWriter->writeAttribute('xmlns:v', 'urn:schemas-microsoft-com:vml');
        $xmlWriter->writeAttribute('xmlns:wp', 'http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing');
        $xmlWriter->writeAttribute('xmlns:w10', 'urn:schemas-microsoft-com:office:word');
        $xmlWriter->writeAttribute('xmlns:w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
        $xmlWriter->writeAttribute('xmlns:wne', 'http://schemas.microsoft.com/office/word/2006/wordml');


        $_elements = $header->getElements();

        foreach ($_elements as $element) {
            if ($element instanceof Text) {
                $this->_writeText($xmlWriter, $element);
            } elseif ($element instanceof TextRun) {
                $this->_writeTextRun($xmlWriter, $element);
            } elseif ($element instanceof TextBreak) {
                $this->_writeTextBreak($xmlWriter, $element);
            } elseif ($element instanceof Table) {
                $this->_writeTable($xmlWriter, $element);
            } elseif ($element instanceof Image) {
                if (!$element->getIsWatermark()) {
                    $this->_writeImage($xmlWriter, $element);
                } else {
                    $this->_writeWatermark($xmlWriter, $element);
                }
            } elseif ($element instanceof PreserveText) {
                $this->_writePreserveText($xmlWriter, $element);
            }
        }

        $xmlWriter->endElement();

        // Return
        return $xmlWriter->getData();
    }
}
