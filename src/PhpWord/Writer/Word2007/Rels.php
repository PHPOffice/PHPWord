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
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\XMLWriter;

/**
 * Word2007 rels part writer
 */
class Rels extends Base
{
    /**
     * Write _rels/.rels
     *
     * @param PhpWord $phpWord
     */
    public function writeRelationships(PhpWord $phpWord = null)
    {
        // Create XML writer
        $xmlWriter = $this->getXmlWriter();

        // XML header
        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');

        // Relationships
        $xmlWriter->startElement('Relationships');
        $xmlWriter->writeAttribute('xmlns', 'http://schemas.openxmlformats.org/package/2006/relationships');

        $relationId = 1;

        // Relationship word/document.xml
        $this->writeRel(
            $xmlWriter,
            $relationId,
            'http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument',
            'word/document.xml'
        );

        // Relationship docProps/core.xml
        $this->writeRel(
            $xmlWriter,
            ++$relationId,
            'http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties',
            'docProps/core.xml'
        );

        // Relationship docProps/app.xml
        $this->writeRel(
            $xmlWriter,
            ++$relationId,
            'http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties',
            'docProps/app.xml'
        );

        $xmlWriter->endElement();

        return $xmlWriter->getData();
    }
}
