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
 * @category   PHPWord
 * @package    PHPWord
 * @copyright  Copyright (c) 2014 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.8.0
 */


class PHPWord_Writer_Word2007_Footnotes extends PHPWord_Writer_Word2007_Base
{
    public function writeFootnotes($allFootnotesCollection)
    {
        // Create XML writer
        $objWriter = null;
        if ($this->getParentWriter()->getUseDiskCaching()) {
            $objWriter = new PHPWord_Shared_XMLWriter(PHPWord_Shared_XMLWriter::STORAGE_DISK, $this->getParentWriter()->getDiskCachingDirectory());
        } else {
            $objWriter = new PHPWord_Shared_XMLWriter(PHPWord_Shared_XMLWriter::STORAGE_MEMORY);
        }

        // XML header
        $objWriter->startDocument('1.0', 'UTF-8', 'yes');

        $objWriter->startElement('w:footnotes');
        $objWriter->writeAttribute('xmlns:r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');
        $objWriter->writeAttribute('xmlns:w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');

        // write separator and continuation separator
        $objWriter->startElement('w:footnote');
        $objWriter->writeAttribute('w:id', 0);
        $objWriter->writeAttribute('w:type', 'separator');
        $objWriter->startElement('w:p');
        $objWriter->startElement('w:r');
        $objWriter->startElement('w:separator');
        $objWriter->endElement(); // w:separator
        $objWriter->endElement(); // w:r
        $objWriter->endElement(); // w:p
        $objWriter->endElement(); // w:footnote

        $objWriter->startElement('w:footnote');
        $objWriter->writeAttribute('w:id', 1);
        $objWriter->writeAttribute('w:type', 'continuationSeparator');
        $objWriter->startElement('w:p');
        $objWriter->startElement('w:r');
        $objWriter->startElement('w:continuationSeparator');
        $objWriter->endElement(); // w:continuationSeparator
        $objWriter->endElement(); // w:r
        $objWriter->endElement(); // w:p
        $objWriter->endElement(); // w:footnote


        foreach ($allFootnotesCollection as $footnote) {
            if ($footnote instanceof PHPWord_Section_Footnote) {
                $this->_writeFootnote($objWriter, $footnote);
            }
        }

        $objWriter->endElement();

        // Return
        return $objWriter->getData();
    }
}
