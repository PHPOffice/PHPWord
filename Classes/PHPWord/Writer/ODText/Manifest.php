<?php
/**
 * PHPWord
 *
 * Copyright (c) 2009 - 2010 PHPWord
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
 * @package    PHPWord_Writer_ODText
 * @copyright  Copyright (c) 2009 - 2010 PHPWord (http://www.codeplex.com/PHPWord)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    ##VERSION##, ##DATE##
 */


/**
 * PHPWord_Writer_ODText_Manifest
 *
 * @category   PHPWord
 * @package    PHPWord_Writer_ODText
 * @copyright  Copyright (c) 2009 - 2010 PHPWord (http://www.codeplex.com/PHPWord)
 */
class PHPWord_Writer_ODText_Manifest extends PHPWord_Writer_ODText_WriterPart
{
	/**
	 * Write Manifest file to XML format
	 *
	 * @param 	PHPWord $pPHPWord
	 * @return 	string 						XML Output
	 * @throws 	Exception
	 */
	public function writeManifest(PHPWord $pPHPWord = null)
	{
		// Create XML writer
		$objWriter = null;
		if ($this->getParentWriter()->getUseDiskCaching()) {
			$objWriter = new PHPWord_Shared_XMLWriter(PHPWord_Shared_XMLWriter::STORAGE_DISK, $this->getParentWriter()->getDiskCachingDirectory());
		} else {
			$objWriter = new PHPWord_Shared_XMLWriter(PHPWord_Shared_XMLWriter::STORAGE_MEMORY);
		}

		// XML header
		$objWriter->startDocument('1.0','UTF-8');

		// manifest:manifest
		$objWriter->startElement('manifest:manifest');
		$objWriter->writeAttribute('xmlns:manifest', 'urn:oasis:names:tc:opendocument:xmlns:manifest:1.0');
		$objWriter->writeAttribute('manifest:version', '1.2');
			
			// manifest:file-entry
			$objWriter->startElement('manifest:file-entry');
			$objWriter->writeAttribute('manifest:media-type', 'application/vnd.oasis.opendocument.text');
			$objWriter->writeAttribute('manifest:version', '1.2');
			$objWriter->writeAttribute('manifest:full-path', '/');
			$objWriter->endElement();
			// manifest:file-entry
			$objWriter->startElement('manifest:file-entry');
			$objWriter->writeAttribute('manifest:media-type', 'text/xml');
			$objWriter->writeAttribute('manifest:full-path', 'content.xml');
			$objWriter->endElement();
			// manifest:file-entry
			$objWriter->startElement('manifest:file-entry');
			$objWriter->writeAttribute('manifest:media-type', 'text/xml');
			$objWriter->writeAttribute('manifest:full-path', 'meta.xml');
			$objWriter->endElement();
			// manifest:file-entry
			$objWriter->startElement('manifest:file-entry');
			$objWriter->writeAttribute('manifest:media-type', 'text/xml');
			$objWriter->writeAttribute('manifest:full-path', 'styles.xml');
			$objWriter->endElement();
				
			for ($i = 0; $i < $this->getParentWriter()->getDrawingHashTable()->count(); ++$i) {
				if ($this->getParentWriter()->getDrawingHashTable()->getByIndex($i) instanceof PHPWord_Shape_Drawing) {
					$extension 	= strtolower($this->getParentWriter()->getDrawingHashTable()->getByIndex($i)->getExtension());
					$mimeType 	= $this->_getImageMimeType( $this->getParentWriter()->getDrawingHashTable()->getByIndex($i)->getPath() );
					
					$objWriter->startElement('manifest:file-entry');
					$objWriter->writeAttribute('manifest:media-type', $mimeType);
					$objWriter->writeAttribute('manifest:full-path', 'Pictures/' . str_replace(' ', '_', $this->getParentWriter()->getDrawingHashTable()->getByIndex($i)->getIndexedFilename()));
					$objWriter->endElement();
				} else if ($this->getParentWriter()->getDrawingHashTable()->getByIndex($i) instanceof PHPWord_Shape_MemoryDrawing) {
					$extension 	= strtolower($this->getParentWriter()->getDrawingHashTable()->getByIndex($i)->getMimeType());
					$extension 	= explode('/', $extension);
					$extension 	= $extension[1];
					
					$mimeType 	= $this->getParentWriter()->getDrawingHashTable()->getByIndex($i)->getMimeType();
					
					$objWriter->startElement('manifest:file-entry');
					$objWriter->writeAttribute('manifest:media-type', $mimeType);
					$objWriter->writeAttribute('manifest:full-path', 'Pictures/' . str_replace(' ', '_', $this->getParentWriter()->getDrawingHashTable()->getByIndex($i)->getIndexedFilename()));
					$objWriter->endElement();
				}
			}
				
		$objWriter->endElement();

		// Return
		return $objWriter->getData();
	}

	
	/**
	 * Get image mime type
	 *
	 * @param 	string	$pFile	Filename
	 * @return 	string	Mime Type
	 * @throws 	Exception
	 */
	private function _getImageMimeType($pFile = '')
	{
		if (PHPWord_Shared_File::file_exists($pFile)) {
			$image = getimagesize($pFile);
			return image_type_to_mime_type($image[2]);
		} else {
			throw new Exception("File $pFile does not exist");
		}
	}
}
