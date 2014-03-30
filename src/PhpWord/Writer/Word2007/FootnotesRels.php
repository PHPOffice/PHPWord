<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\Word2007;

use PhpOffice\PhpWord\Exceptions\Exception;
use PhpOffice\PhpWord\Shared\XMLWriter;

/**
 * Word2007 footnotes rel part writer
 */
class FootnotesRels extends WriterPart
{
    /**
     * Write word/_rels/footnotes.xml.rels
     *
     * @param mixed $_relsCollection
     */
    public function writeFootnotesRels($_relsCollection)
    {
        // Create XML writer
        $xmlWriter = $this->getXmlWriter();

        // XML header
        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');

        // Relationships
        $xmlWriter->startElement('Relationships');
        $xmlWriter->writeAttribute('xmlns', 'http://schemas.openxmlformats.org/package/2006/relationships');

        // Relationships to Links
        foreach ($_relsCollection as $relation) {
            $relationType = $relation['type'];
            $relationName = $relation['target'];
            $relationId   = $relation['rID'];
            $targetMode   = ($relationType == 'hyperlink') ? 'External' : '';

            $this->_writeRelationship($xmlWriter, $relationId, 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/' . $relationType, $relationName, $targetMode);
        }

        $xmlWriter->endElement();

        // Return
        return $xmlWriter->getData();
    }

    /**
     * Write individual rels entry
     *
     * @param PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param int $pId Relationship ID
     * @param string $pType Relationship type
     * @param string $pTarget Relationship target
     * @param string $pTargetMode Relationship target mode
     */
    private function _writeRelationship(XMLWriter $xmlWriter = null, $pId = 1, $pType = '', $pTarget = '', $pTargetMode = '')
    {
        if ($pType != '' && $pTarget != '') {
            if (strpos($pId, 'rId') === false) {
                $pId = 'rId' . $pId;
            }

            // Write relationship
            $xmlWriter->startElement('Relationship');
            $xmlWriter->writeAttribute('Id', $pId);
            $xmlWriter->writeAttribute('Type', $pType);
            $xmlWriter->writeAttribute('Target', $pTarget);

            if ($pTargetMode != '') {
                $xmlWriter->writeAttribute('TargetMode', $pTargetMode);
            }

            $xmlWriter->endElement();
        } else {
            throw new Exception("Invalid parameters passed.");
        }
    }
}
