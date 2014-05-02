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

namespace PhpOffice\PhpWord\Writer\Word2007;

use PhpOffice\PhpWord\Exceptions\Exception;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\XMLWriter;

/**
 * Word2007 rels part writer
 */
class Rels extends WriterPart
{
    /**
     * Write _rels/.rels
     *
     * @param PhpOffice\PhpWord\PhpWord $phpWord
     */
    public function writeRelationships(PhpWord $phpWord = null)
    {
        // Create XML writer
        $xmlWriter = null;
        if ($this->getParentWriter()->getUseDiskCaching()) {
            $xmlWriter = new XMLWriter(XMLWriter::STORAGE_DISK, $this->getParentWriter()->getDiskCachingDirectory());
        } else {
            $xmlWriter = new XMLWriter(XMLWriter::STORAGE_MEMORY);
        }

        // XML header
        $xmlWriter->startDocument('1.0', 'UTF-8', 'yes');

        // Relationships
        $xmlWriter->startElement('Relationships');
        $xmlWriter->writeAttribute('xmlns', 'http://schemas.openxmlformats.org/package/2006/relationships');

        $relationId = 1;

        // Relationship word/document.xml
        $this->_writeRelationship(
            $xmlWriter,
            $relationId,
            'http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument',
            'word/document.xml'
        );

        // Relationship docProps/core.xml
        $this->_writeRelationship(
            $xmlWriter,
            ++$relationId,
            'http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties',
            'docProps/core.xml'
        );

        // Relationship docProps/app.xml
        $this->_writeRelationship(
            $xmlWriter,
            ++$relationId,
            'http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties',
            'docProps/app.xml'
        );

        $xmlWriter->endElement();

        return $xmlWriter->getData();
    }

    /**
     * Write Override content type
     *
     * @param \PhpOffice\PhpWord\Shared\XMLWriter $xmlWriter
     * @param int $pId Relationship ID. rId will be prepended!
     * @param string $pType Relationship type
     * @param string $pTarget Relationship target
     * @param string $pTargetMode Relationship target mode
     * @throws \PhpOffice\PhpWord\Exceptions\Exception
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
