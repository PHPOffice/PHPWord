<?php
/**
 * PHPWord
 *
 * Copyright (c) 2011 PHPWord
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
 * @copyright  Copyright (c) 010 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    Beta 0.6.3, 08.07.2011
 */


class PHPWord_Writer_Word2007_FootnotesRels extends PHPWord_Writer_Word2007_WriterPart
{
    public function writeFootnotesRels($_relsCollection)
    {
        // Create XML writer
        $objWriter = null;
        if ($this->getParentWriter()->getUseDiskCaching()) {
            $objWriter = new PHPWord_Shared_XMLWriter(PHPWord_Shared_XMLWriter::STORAGE_DISK, $this->getParentWriter()->getDiskCachingDirectory());
        } else {
            $objWriter = new PHPWord_Shared_XMLWriter(PHPWord_Shared_XMLWriter::STORAGE_MEMORY);
        }

        // XML header
        $objWriter->startDocument('1.0', 'UTF-8', 'yes');

        // Relationships
        $objWriter->startElement('Relationships');
        $objWriter->writeAttribute('xmlns', 'http://schemas.openxmlformats.org/package/2006/relationships');

        // Relationships to Links
        foreach ($_relsCollection as $relation) {
            $relationType = $relation['type'];
            $relationName = $relation['target'];
            $relationId   = $relation['rID'];
            $targetMode   = ($relationType == 'hyperlink') ? 'External' : '';

            $this->_writeRelationship($objWriter, $relationId, 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/' . $relationType, $relationName, $targetMode);
        }

        $objWriter->endElement();

        // Return
        return $objWriter->getData();
    }

    private function _writeRelationship(PHPWord_Shared_XMLWriter $objWriter = null, $pId = 1, $pType = '', $pTarget = '', $pTargetMode = '')
    {
        if ($pType != '' && $pTarget != '') {
            if (strpos($pId, 'rId') === false) {
                $pId = 'rId' . $pId;
            }

            // Write relationship
            $objWriter->startElement('Relationship');
            $objWriter->writeAttribute('Id', $pId);
            $objWriter->writeAttribute('Type', $pType);
            $objWriter->writeAttribute('Target', $pTarget);

            if ($pTargetMode != '') {
                $objWriter->writeAttribute('TargetMode', $pTargetMode);
            }

            $objWriter->endElement();
        } else {
            throw new Exception("Invalid parameters passed.");
        }
    }
}
