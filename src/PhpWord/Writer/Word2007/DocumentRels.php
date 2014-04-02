<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007;

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Shared\XMLWriter;

/**
 * Word2007 document rels part writer
 */
class DocumentRels extends Base
{
    /**
     * Write word/_rels/document.xml.rels
     *
     * @param array $_relsCollection
     */
    public function writeDocumentRels($_relsCollection)
    {
        // Create XML writer
        $xmlWriter = $this->getXmlWriter();

        // XML header
        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');

        // Relationships
        $xmlWriter->startElement('Relationships');
        $xmlWriter->writeAttribute('xmlns', 'http://schemas.openxmlformats.org/package/2006/relationships');

        // Relationship word/document.xml
        $this->writeRel(
            $xmlWriter,
            1,
            'http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles',
            'styles.xml'
        );

        // Relationship word/numbering.xml
        $this->writeRel(
            $xmlWriter,
            2,
            'http://schemas.openxmlformats.org/officeDocument/2006/relationships/numbering',
            'numbering.xml'
        );

        // Relationship word/settings.xml
        $this->writeRel(
            $xmlWriter,
            3,
            'http://schemas.openxmlformats.org/officeDocument/2006/relationships/settings',
            'settings.xml'
        );

        // Relationship word/settings.xml
        $this->writeRel(
            $xmlWriter,
            4,
            'http://schemas.openxmlformats.org/officeDocument/2006/relationships/theme',
            'theme/theme1.xml'
        );

        // Relationship word/settings.xml
        $this->writeRel(
            $xmlWriter,
            5,
            'http://schemas.openxmlformats.org/officeDocument/2006/relationships/webSettings',
            'webSettings.xml'
        );

        // Relationship word/settings.xml
        $this->writeRel(
            $xmlWriter,
            6,
            'http://schemas.openxmlformats.org/officeDocument/2006/relationships/fontTable',
            'fontTable.xml'
        );

        $this->writeMediaRels($xmlWriter, $_relsCollection);
        $xmlWriter->endElement(); // Relationships

        // Return
        return $xmlWriter->getData();
    }

    /**
     * Write header footer rels word/_rels/*.xml.rels
     *
     * @param array $_relsCollection
     */
    public function writeHeaderFooterRels($_relsCollection)
    {
        $xmlWriter = $this->getXmlWriter();
        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');
        $xmlWriter->startElement('Relationships');
        $xmlWriter->writeAttribute('xmlns', 'http://schemas.openxmlformats.org/package/2006/relationships');
        $this->writeMediaRels($xmlWriter, $_relsCollection);
        $xmlWriter->endElement();

        return $xmlWriter->getData();
    }
}
