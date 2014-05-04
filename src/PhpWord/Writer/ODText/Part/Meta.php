<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\ODText\Part;

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\PhpWord;

/**
 * ODText meta part writer
 */
class Meta extends AbstractPart
{
    /**
     * Write Meta file to XML format
     *
     * @param  \PhpOffice\PhpWord\PhpWord $phpWord
     * @return string XML Output
     */
    public function writeMeta(PhpWord $phpWord = null)
    {
        if (is_null($phpWord)) {
            throw new Exception("No PhpWord assigned.");
        }

        // Create XML writer
        $xmlWriter = $this->getXmlWriter();

        // XML header
        $xmlWriter->startDocument('1.0', 'UTF-8');

        // office:document-meta
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
