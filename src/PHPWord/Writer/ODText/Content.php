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
 * PHPWord_Writer_ODText_Content
 *
 * @category   PHPWord
 * @package    PHPWord_Writer_ODText
 * @copyright  Copyright (c) 2009 - 2010 PHPWord (http://www.codeplex.com/PHPWord)
 */
class PHPWord_Writer_ODText_Content extends PHPWord_Writer_ODText_WriterPart
{
	/**
	 * Write content file to XML format
	 *
	 * @param 	PHPWord $pPHPWord
	 * @return 	string 						XML Output
	 * @throws 	Exception
	 */
	public function writeContent(PHPWord $pPHPWord = null)
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

		// office:document-content
		$objWriter->startElement('office:document-content');
		$objWriter->writeAttribute('xmlns:office', 'urn:oasis:names:tc:opendocument:xmlns:office:1.0');
		$objWriter->writeAttribute('xmlns:style', 'urn:oasis:names:tc:opendocument:xmlns:style:1.0');
		$objWriter->writeAttribute('xmlns:text', 'urn:oasis:names:tc:opendocument:xmlns:text:1.0');
		$objWriter->writeAttribute('xmlns:table', 'urn:oasis:names:tc:opendocument:xmlns:table:1.0');
		$objWriter->writeAttribute('xmlns:draw', 'urn:oasis:names:tc:opendocument:xmlns:drawing:1.0');
		$objWriter->writeAttribute('xmlns:fo', 'urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0');
		$objWriter->writeAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
		$objWriter->writeAttribute('xmlns:dc', 'http://purl.org/dc/elements/1.1/');
		$objWriter->writeAttribute('xmlns:meta', 'urn:oasis:names:tc:opendocument:xmlns:meta:1.0');
		$objWriter->writeAttribute('xmlns:number', 'urn:oasis:names:tc:opendocument:xmlns:datastyle:1.0');
		$objWriter->writeAttribute('xmlns:svg', 'urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0');
		$objWriter->writeAttribute('xmlns:chart', 'urn:oasis:names:tc:opendocument:xmlns:chart:1.0');
		$objWriter->writeAttribute('xmlns:dr3d', 'urn:oasis:names:tc:opendocument:xmlns:dr3d:1.0');
		$objWriter->writeAttribute('xmlns:math', 'http://www.w3.org/1998/Math/MathML');
		$objWriter->writeAttribute('xmlns:form', 'urn:oasis:names:tc:opendocument:xmlns:form:1.0');
		$objWriter->writeAttribute('xmlns:script', 'urn:oasis:names:tc:opendocument:xmlns:script:1.0');
		$objWriter->writeAttribute('xmlns:ooo', 'http://openoffice.org/2004/office');
		$objWriter->writeAttribute('xmlns:ooow', 'http://openoffice.org/2004/writer');
		$objWriter->writeAttribute('xmlns:oooc', 'http://openoffice.org/2004/calc');
		$objWriter->writeAttribute('xmlns:dom', 'http://www.w3.org/2001/xml-events');
		$objWriter->writeAttribute('xmlns:xforms', 'http://www.w3.org/2002/xforms');
		$objWriter->writeAttribute('xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');
		$objWriter->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
		$objWriter->writeAttribute('xmlns:rpt', 'http://openoffice.org/2005/report');
		$objWriter->writeAttribute('xmlns:of', 'urn:oasis:names:tc:opendocument:xmlns:of:1.2');
		$objWriter->writeAttribute('xmlns:xhtml', 'http://www.w3.org/1999/xhtml');
		$objWriter->writeAttribute('xmlns:grddl', 'http://www.w3.org/2003/g/data-view#');
		$objWriter->writeAttribute('xmlns:tableooo', 'http://openoffice.org/2009/table');
		$objWriter->writeAttribute('xmlns:field', 'urn:openoffice:names:experimental:ooo-ms-interop:xmlns:field:1.0');
		$objWriter->writeAttribute('xmlns:formx', 'urn:openoffice:names:experimental:ooxml-odf-interop:xmlns:form:1.0');
		$objWriter->writeAttribute('xmlns:css3t', 'http://www.w3.org/TR/css3-text/');
		$objWriter->writeAttribute('office:version', '1.2');
			
			// office:automatic-styles
			$objWriter->startElement('office:automatic-styles');
			
			$objWriter->endElement();
			
			// office:body
			$objWriter->startElement('office:body');
				// office:text
				$objWriter->startElement('office:text');
				
				$objWriter->endElement();
			$objWriter->endElement();
		$objWriter->endElement();

		// Return
		return $objWriter->getData();
	}
}
