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

use PhpOffice\PhpWord\Section\Footnote;
use PhpOffice\PhpWord\Shared\XMLWriter;

/**
 * Word2007 footnotes part writer
 */
class Footnotes extends Base
{
    /**
     * Write word/footnotes.xml
     *
     * @param array $allFootnotesCollection
     */
    public function writeFootnotes($allFootnotesCollection)
    {
        // Create XML writer
        $xmlWriter = null;
        if ($this->getParentWriter()->getUseDiskCaching()) {
            $xmlWriter = new XMLWriter(XMLWriter::STORAGE_DISK, $this->getParentWriter()->getDiskCachingDirectory());
        } else {
            $xmlWriter = new XMLWriter(XMLWriter::STORAGE_MEMORY);
        }

        // XML header
        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');

        $xmlWriter->startElement('w:footnotes');
        $xmlWriter->writeAttribute('xmlns:r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');
        $xmlWriter->writeAttribute('xmlns:w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');

        // write separator and continuation separator
        $xmlWriter->startElement('w:footnote');
        $xmlWriter->writeAttribute('w:id', 0);
        $xmlWriter->writeAttribute('w:type', 'separator');
        $xmlWriter->startElement('w:p');
        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:separator');
        $xmlWriter->endElement(); // w:separator
        $xmlWriter->endElement(); // w:r
        $xmlWriter->endElement(); // w:p
        $xmlWriter->endElement(); // w:footnote

        $xmlWriter->startElement('w:footnote');
        $xmlWriter->writeAttribute('w:id', 1);
        $xmlWriter->writeAttribute('w:type', 'continuationSeparator');
        $xmlWriter->startElement('w:p');
        $xmlWriter->startElement('w:r');
        $xmlWriter->startElement('w:continuationSeparator');
        $xmlWriter->endElement(); // w:continuationSeparator
        $xmlWriter->endElement(); // w:r
        $xmlWriter->endElement(); // w:p
        $xmlWriter->endElement(); // w:footnote

        foreach ($allFootnotesCollection as $footnote) {
            if ($footnote instanceof Footnote) {
                $this->_writeFootnote($xmlWriter, $footnote);
            }
        }

        $xmlWriter->endElement();

        // Return
        return $xmlWriter->getData();
    }
}
