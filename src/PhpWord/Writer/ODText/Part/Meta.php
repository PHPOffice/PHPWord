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

namespace PhpOffice\PhpWord\Writer\ODText\Part;

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\PhpWord;

/**
 * ODText meta part writer: meta.xml
 */
class Meta extends AbstractPart
{
    /**
     * Write part
     *
     * @return string
     */
    public function write()
    {
        $phpWord = $this->getParentWriter()->getPhpWord();
        $docProps = $phpWord->getDocumentProperties();
        $xmlWriter = $this->getXmlWriter();

        $xmlWriter->startDocument('1.0', 'UTF-8');
        $xmlWriter->startElement('office:document-meta');
        $xmlWriter->writeAttribute('office:version', '1.2');
        $xmlWriter->writeAttribute('xmlns:office', 'urn:oasis:names:tc:opendocument:xmlns:office:1.0');
        $xmlWriter->writeAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
        $xmlWriter->writeAttribute('xmlns:dc', 'http://purl.org/dc/elements/1.1/');
        $xmlWriter->writeAttribute('xmlns:meta', 'urn:oasis:names:tc:opendocument:xmlns:meta:1.0');
        $xmlWriter->writeAttribute('xmlns:ooo', 'http://openoffice.org/2004/office');
        $xmlWriter->writeAttribute('xmlns:grddl', 'http://www.w3.org/2003/g/data-view#');

        // office:meta
        $xmlWriter->startElement('office:meta');
        $xmlWriter->writeElement('dc:creator', $docProps->getLastModifiedBy());
        $xmlWriter->writeElement('dc:date', gmdate('Y-m-d\TH:i:s.000', $docProps->getModified()));
        $xmlWriter->writeElement('dc:description', $docProps->getDescription());
        $xmlWriter->writeElement('dc:subject', $docProps->getSubject());
        $xmlWriter->writeElement('dc:title', $docProps->getTitle());
        $xmlWriter->writeElement('meta:creation-date', gmdate('Y-m-d\TH:i:s.000', $docProps->getCreated()));
        $xmlWriter->writeElement('meta:initial-creator', $docProps->getCreator());
        $xmlWriter->writeElement('meta:keyword', $docProps->getKeywords());

        // @todo : Where these properties are written ?
        // $docProps->getCategory()
        // $docProps->getCompany()

        $xmlWriter->endElement();

        $xmlWriter->endElement();

        // Return
        return $xmlWriter->getData();
    }
}
