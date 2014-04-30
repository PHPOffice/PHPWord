<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007\Part;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Exception\Exception;

/**
 * Word2007 document properties part writer
 */
class DocProps extends AbstractPart
{
    /**
     * Write docProps/app.xml
     */
    public function writeDocPropsApp(PhpWord $phpWord = null)
    {
        if (is_null($phpWord)) {
            throw new Exception("No PhpWord assigned.");
        }
        $xmlWriter = $this->getXmlWriter();

        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');
        $xmlWriter->startElement('Properties');
        $xmlWriter->writeAttribute('xmlns', 'http://schemas.openxmlformats.org/officeDocument/2006/extended-properties');
        $xmlWriter->writeAttribute('xmlns:vt', 'http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes');

        $xmlWriter->writeElement('Application', 'PHPWord');
        $xmlWriter->writeElement('Company', $phpWord->getDocumentProperties()->getCompany());
        $xmlWriter->writeElement('Manager', $phpWord->getDocumentProperties()->getManager());

        $xmlWriter->endElement(); // Properties

        return $xmlWriter->getData();
    }


    /**
     * Write docProps/core.xml
     *
     * @param \PhpOffice\PhpWord\PhpWord $phpWord
     */
    public function writeDocPropsCore(PhpWord $phpWord = null)
    {
        if (is_null($phpWord)) {
            throw new Exception("No PhpWord assigned.");
        }
        $xmlWriter = $this->getXmlWriter();

        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');
        $xmlWriter->startElement('cp:coreProperties');
        $xmlWriter->writeAttribute('xmlns:cp', 'http://schemas.openxmlformats.org/package/2006/metadata/core-properties');
        $xmlWriter->writeAttribute('xmlns:dc', 'http://purl.org/dc/elements/1.1/');
        $xmlWriter->writeAttribute('xmlns:dcterms', 'http://purl.org/dc/terms/');
        $xmlWriter->writeAttribute('xmlns:dcmitype', 'http://purl.org/dc/dcmitype/');
        $xmlWriter->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');

        $xmlWriter->writeElement('dc:creator', $phpWord->getDocumentProperties()->getCreator());
        $xmlWriter->writeElement('dc:title', $phpWord->getDocumentProperties()->getTitle());
        $xmlWriter->writeElement('dc:description', $phpWord->getDocumentProperties()->getDescription());
        $xmlWriter->writeElement('dc:subject', $phpWord->getDocumentProperties()->getSubject());
        $xmlWriter->writeElement('cp:keywords', $phpWord->getDocumentProperties()->getKeywords());
        $xmlWriter->writeElement('cp:category', $phpWord->getDocumentProperties()->getCategory());
        $xmlWriter->writeElement('cp:lastModifiedBy', $phpWord->getDocumentProperties()->getLastModifiedBy());

        // dcterms:created
        $xmlWriter->startElement('dcterms:created');
        $xmlWriter->writeAttribute('xsi:type', 'dcterms:W3CDTF');
        $xmlWriter->writeRaw(date(DATE_W3C, $phpWord->getDocumentProperties()->getCreated()));
        $xmlWriter->endElement();

        // dcterms:modified
        $xmlWriter->startElement('dcterms:modified');
        $xmlWriter->writeAttribute('xsi:type', 'dcterms:W3CDTF');
        $xmlWriter->writeRaw(date(DATE_W3C, $phpWord->getDocumentProperties()->getModified()));
        $xmlWriter->endElement();

        $xmlWriter->endElement(); // cp:coreProperties

        return $xmlWriter->getData();
    }
}
