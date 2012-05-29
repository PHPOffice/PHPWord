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
 * PHPWord_Writer_ODText_Meta
 *
 * @category   PHPWord
 * @package    PHPWord_Writer_ODText
 * @copyright  Copyright (c) 2009 - 2010 PHPWord (http://www.codeplex.com/PHPWord)
 */
class PHPWord_Writer_ODText_Meta extends PHPWord_Writer_ODText_WriterPart
{
	/**
	 * Write Meta file to XML format
	 *
	 * @param 	PHPWord $pPHPWord
	 * @return 	string 						XML Output
	 * @throws 	Exception
	 */
	public function writeMeta(PHPWord $pPHPWord = null) {
		// Create XML writer
		$objWriter = null;
		if ($this->getParentWriter()->getUseDiskCaching()) {
			$objWriter = new PHPWord_Shared_XMLWriter(PHPWord_Shared_XMLWriter::STORAGE_DISK, $this->getParentWriter()->getDiskCachingDirectory());
		} else {
			$objWriter = new PHPWord_Shared_XMLWriter(PHPWord_Shared_XMLWriter::STORAGE_MEMORY);
		}

		// XML header
		$objWriter->startDocument('1.0','UTF-8');

		// office:document-meta
		$objWriter->startElement('office:document-meta');
		$objWriter->writeAttribute('xmlns:office', 'urn:oasis:names:tc:opendocument:xmlns:office:1.0');
		$objWriter->writeAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
		$objWriter->writeAttribute('xmlns:dc', 'http://purl.org/dc/elements/1.1/');
		$objWriter->writeAttribute('xmlns:meta', 'urn:oasis:names:tc:opendocument:xmlns:meta:1.0');
		$objWriter->writeAttribute('xmlns:ooo', 'http://openoffice.org/2004/office');
		$objWriter->writeAttribute('xmlns:grddl', 'http://www.w3.org/2003/g/data-view#');
		$objWriter->writeAttribute('office:version', '1.2');
			
			// office:meta
			$objWriter->startElement('office:meta');
			
				// dc:creator
				$objWriter->writeElement('dc:creator', $pPHPWord->getProperties()->getLastModifiedBy());
				// dc:date
				$objWriter->writeElement('dc:date', gmdate('Y-m-d\TH:i:s.000' ,$pPHPWord->getProperties()->getModified()));
				// dc:description
				$objWriter->writeElement('dc:description', $pPHPWord->getProperties()->getDescription());
				// dc:subject
				$objWriter->writeElement('dc:subject', $pPHPWord->getProperties()->getSubject());
				// dc:title
				$objWriter->writeElement('dc:title', $pPHPWord->getProperties()->getTitle());
				// meta:creation-date
				$objWriter->writeElement('meta:creation-date', gmdate('Y-m-d\TH:i:s.000' ,$pPHPWord->getProperties()->getCreated()));
				// meta:initial-creator
				$objWriter->writeElement('meta:initial-creator', $pPHPWord->getProperties()->getCreator());
				// meta:keyword
				$objWriter->writeElement('meta:keyword', $pPHPWord->getProperties()->getKeywords());
	
				// @todo : Where these properties are written ?
				// $pPHPWord->getProperties()->getCategory()
				// $pPHPWord->getProperties()->getCompany()
			
			$objWriter->endElement();
				
		$objWriter->endElement();

		// Return
		return $objWriter->getData();
	}
}
