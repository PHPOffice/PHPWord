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

namespace PhpOffice\PhpWord\Writer\WPS\Part;

use PhpOffice\PhpWord\Shared\XMLWriter;

/**
 * WPS meta part writer
 *
 * @since 0.18.0
 */
class Meta extends AbstractPart
{
    /**
     * Write meta.xml file.
     *
     * @return string
     */
    public function write(): string
    {
        $xmlWriter = $this->getXmlWriter();
        $phpWord = $this->getParentWriter()->getPhpWord();
        $docInfo = $phpWord->getDocInfo();

        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');
        $xmlWriter->startElement('office:document-meta');

        $xmlWriter->writeAttribute('xmlns:office', 'urn:oasis:names:tc:opendocument:xmlns:office:1.0');
        $xmlWriter->writeAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
        $xmlWriter->writeAttribute('xmlns:dc', 'http://purl.org/dc/elements/1.1/');
        $xmlWriter->writeAttribute('xmlns:meta', 'urn:oasis:names:tc:opendocument:xmlns:meta:1.0');
        $xmlWriter->writeAttribute('xmlns:wps', 'http://wps.kdanmobile.com/2017/office');
        
        $xmlWriter->startElement('office:meta');

        // Creator
        $creator = $docInfo->getCreator();
        if ($creator !== null) {
            $xmlWriter->writeElement('meta:initial-creator', $creator);
            $xmlWriter->writeElement('dc:creator', $creator);
        }

        // Creation date
        $createdDate = $docInfo->getCreated();
        if ($createdDate !== null) {
            $xmlWriter->startElement('meta:creation-date');
            $xmlWriter->writeRaw($createdDate);
            $xmlWriter->endElement();
        }

        // Modification date
        $modifiedDate = $docInfo->getModified();
        if ($modifiedDate !== null) {
            $xmlWriter->startElement('dc:date');
            $xmlWriter->writeRaw($modifiedDate);
            $xmlWriter->endElement();
        }

        // Title
        $title = $docInfo->getTitle();
        if ($title !== null) {
            $xmlWriter->writeElement('dc:title', $title);
        }

        // Description
        $description = $docInfo->getDescription();
        if ($description !== null) {
            $xmlWriter->writeElement('dc:description', $description);
        }

        // Subject
        $subject = $docInfo->getSubject();
        if ($subject !== null) {
            $xmlWriter->writeElement('dc:subject', $subject);
        }

        // Keywords
        $keywords = $docInfo->getKeywords();
        if ($keywords !== null) {
            $xmlWriter->writeElement('meta:keyword', $keywords);
        }

        // Category
        $category = $docInfo->getCategory();
        if ($category !== null) {
            $this->writeUserDefined($xmlWriter, 'Category', $category);
        }

        // Company
        $company = $docInfo->getCompany();
        if ($company !== null) {
            $this->writeUserDefined($xmlWriter, 'Company', $company);
        }

        $xmlWriter->endElement(); // office:meta
        $xmlWriter->endElement(); // office:document-meta

        return $xmlWriter->getData();
    }

    /**
     * Write user defined value
     */
    private function writeUserDefined(XMLWriter $xmlWriter, string $name, string $value): void
    {
        $xmlWriter->startElement('meta:user-defined');
        $xmlWriter->writeAttribute('meta:name', $name);
        $xmlWriter->writeRaw($value);
        $xmlWriter->endElement();
    }
}