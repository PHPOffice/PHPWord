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

namespace PhpOffice\PhpWord\Writer\ODText;

use PhpOffice\PhpWord\Shared\XMLWriter;

class Meta extends WriterPart
{
    /**
     * Write Meta file to XML format
     *
     * @param PHPWord $phpWord
     * @return string XML Output
     * @throws Exception
     */
    public function writeMeta(PHPWord $phpWord = null)
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
        $xmlWriter->writeElement('dc:creator', $phpWord->getProperties()->getLastModifiedBy());
        // dc:date
        $xmlWriter->writeElement('dc:date', gmdate('Y-m-d\TH:i:s.000', $phpWord->getProperties()->getModified()));
        // dc:description
        $xmlWriter->writeElement('dc:description', $phpWord->getProperties()->getDescription());
        // dc:subject
        $xmlWriter->writeElement('dc:subject', $phpWord->getProperties()->getSubject());
        // dc:title
        $xmlWriter->writeElement('dc:title', $phpWord->getProperties()->getTitle());
        // meta:creation-date
        $xmlWriter->writeElement('meta:creation-date', gmdate('Y-m-d\TH:i:s.000', $phpWord->getProperties()->getCreated()));
        // meta:initial-creator
        $xmlWriter->writeElement('meta:initial-creator', $phpWord->getProperties()->getCreator());
        // meta:keyword
        $xmlWriter->writeElement('meta:keyword', $phpWord->getProperties()->getKeywords());

        // @todo : Where these properties are written ?
        // $phpWord->getProperties()->getCategory()
        // $phpWord->getProperties()->getCompany()

        $xmlWriter->endElement();

        $xmlWriter->endElement();

        // Return
        return $xmlWriter->getData();
    }
}
