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
        $this->writeRelationship(
            $xmlWriter,
            1,
            'http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles',
            'styles.xml'
        );

        // Relationship word/numbering.xml
        $this->writeRelationship(
            $xmlWriter,
            2,
            'http://schemas.openxmlformats.org/officeDocument/2006/relationships/numbering',
            'numbering.xml'
        );

        // Relationship word/settings.xml
        $this->writeRelationship(
            $xmlWriter,
            3,
            'http://schemas.openxmlformats.org/officeDocument/2006/relationships/settings',
            'settings.xml'
        );

        // Relationship word/settings.xml
        $this->writeRelationship(
            $xmlWriter,
            4,
            'http://schemas.openxmlformats.org/officeDocument/2006/relationships/theme',
            'theme/theme1.xml'
        );

        // Relationship word/settings.xml
        $this->writeRelationship(
            $xmlWriter,
            5,
            'http://schemas.openxmlformats.org/officeDocument/2006/relationships/webSettings',
            'webSettings.xml'
        );

        // Relationship word/settings.xml
        $this->writeRelationship(
            $xmlWriter,
            6,
            'http://schemas.openxmlformats.org/officeDocument/2006/relationships/fontTable',
            'fontTable.xml'
        );

        // Relationships to Images / Embeddings / Headers / Footers
        foreach ($_relsCollection as $relation) {
            $relationType = $relation['type'];
            $relationName = $relation['target'];
            $relationId = $relation['rID'];
            $targetMode = ($relationType == 'hyperlink') ? 'External' : '';

            $this->writeRelationship(
                $xmlWriter,
                $relationId,
                'http://schemas.openxmlformats.org/officeDocument/2006/relationships/' . $relationType,
                $relationName,
                $targetMode
            );
        }


        $xmlWriter->endElement();

        // Return
        return $xmlWriter->getData();
    }

    /**
     * Write header footer rels
     *
     * @param array $_relsCollection
     */
    public function writeHeaderFooterRels($_relsCollection)
    {
        // Create XML writer
        $xmlWriter = $this->getXmlWriter();

        // XML header
        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');

        // Relationships
        $xmlWriter->startElement('Relationships');
        $xmlWriter->writeAttribute('xmlns', 'http://schemas.openxmlformats.org/package/2006/relationships');

        // Relationships to Images / Embeddings / Headers / Footers
        foreach ($_relsCollection as $relation) {
            $relationType = $relation['type'];
            $relationName = $relation['target'];
            $relationId = $relation['rID'];

            $this->writeRelationship(
                $xmlWriter,
                $relationId,
                'http://schemas.openxmlformats.org/officeDocument/2006/relationships/' . $relationType,
                $relationName
            );
        }


        $xmlWriter->endElement();

        // Return
        return $xmlWriter->getData();
    }
}
