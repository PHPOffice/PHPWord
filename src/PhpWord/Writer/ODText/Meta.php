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

namespace PhpOffice\PhpWord\Writer\ODText;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\XMLWriter;

/**
 * ODText meta part writer
 */
class Meta extends WriterPart
{
    /**
     * Write Meta file to XML format
     *
     * @param  \PhpOffice\PhpWord\PhpWord $phpWord
     * @return string XML Output
     */
    public function writeMeta(PhpWord $phpWord = null)
    {
        // Create XML writer
        $xmlWriter = null;
        if ($this->getParentWriter()->getUseDiskCaching()) {
            $xmlWriter = new XMLWriter(XMLWriter::STORAGE_DISK, $this->getParentWriter()->getDiskCachingDirectory());
        } else {
            $xmlWriter = new XMLWriter(XMLWriter::STORAGE_MEMORY);
        }

        // XML header
        $xmlWriter->startDocument('1.0', 'UTF-8');

        // office:document-meta
        $xmlWriter->startElement('office:document-meta');
        $xmlWriter->writeAttribute('xmlns:office', 'urn:oasis:names:tc:opendocument:xmlns:office:1.0');
        $xmlWriter->writeAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
        $xmlWriter->writeAttribute('xmlns:dc', 'http://purl.org/dc/elements/1.1/');
        $xmlWriter->writeAttribute('xmlns:meta', 'urn:oasis:names:tc:opendocument:xmlns:meta:1.0');
        $xmlWriter->writeAttribute('xmlns:ooo', 'http://openoffice.org/2004/office');
        $xmlWriter->writeAttribute('xmlns:grddl', 'http://www.w3.org/2003/g/data-view#');
        $xmlWriter->writeAttribute('office:version', '1.2');

        // office:meta
        $xmlWriter->startElement('office:meta');

        // dc:creator
        $xmlWriter->writeElement('dc:creator', $phpWord->getDocumentProperties()->getLastModifiedBy());
        // dc:date
        $xmlWriter->writeElement('dc:date', gmdate('Y-m-d\TH:i:s.000', $phpWord->getDocumentProperties()->getModified()));
        // dc:description
        $xmlWriter->writeElement('dc:description', $phpWord->getDocumentProperties()->getDescription());
        // dc:subject
        $xmlWriter->writeElement('dc:subject', $phpWord->getDocumentProperties()->getSubject());
        // dc:title
        $xmlWriter->writeElement('dc:title', $phpWord->getDocumentProperties()->getTitle());
        // meta:creation-date
        $xmlWriter->writeElement('meta:creation-date', gmdate('Y-m-d\TH:i:s.000', $phpWord->getDocumentProperties()->getCreated()));
        // meta:initial-creator
        $xmlWriter->writeElement('meta:initial-creator', $phpWord->getDocumentProperties()->getCreator());
        // meta:keyword
        $xmlWriter->writeElement('meta:keyword', $phpWord->getDocumentProperties()->getKeywords());

        // @todo : Where these properties are written ?
        // $phpWord->getDocumentProperties()->getCategory()
        // $phpWord->getDocumentProperties()->getCompany()

        $xmlWriter->endElement();

        $xmlWriter->endElement();

        // Return
        return $xmlWriter->getData();
    }
}
