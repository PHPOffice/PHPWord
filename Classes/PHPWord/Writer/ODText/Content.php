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
	
		// We firstly search all fonts used
		$_sections = $pPHPWord->getSections();
		$countSections = count($_sections);
		if($countSections > 0) {
			$pSection = 0;
			$numPStyles = 0;
			$numFStyles = 0;
		
			foreach($_sections as $section) {
				$pSection++;
				$_elements = $section->getElements();
		
				foreach($_elements as $element) {
					if($element instanceof PHPWord_Section_Text) {
						$fStyle = $element->getFontStyle();
						$pStyle = $element->getParagraphStyle();
							
						if($fStyle instanceof PHPWord_Style_Font){
							$numFStyles++;
		
							$arrStyle = array(
								'color'=>$fStyle->getColor(),
								'name' =>$fStyle->getName()
							);
							$pPHPWord->addFontStyle('T'.$numFStyles, $arrStyle);
							$element->setFontStyle('T'.$numFStyles);
						}
						elseif($pStyle instanceof PHPWord_Style_Paragraph){
							$numPStyles++;
									
							$pPHPWord->addParagraphStyle('P'.$numPStyles, array());
							$element->setParagraph('P'.$numPStyles);
						}
					}
				}
			}
		}
		
		// office:font-face-decls
		$objWriter->startElement('office:font-face-decls');
		$arrFonts = array();
			
		$styles = PHPWord_Style::getStyles();
		$numFonts = 0;
		if(count($styles) > 0) {
			foreach($styles as $styleName => $style) {
				// PHPWord_Style_Font
				if($style instanceof PHPWord_Style_Font) {
					$numFonts++;
					$name = $style->getName();
					if(!in_array($name, $arrFonts)){
						$arrFonts[] = $name;

						// style:font-face
						$objWriter->startElement('style:font-face');
						$objWriter->writeAttribute('style:name', $name);
						$objWriter->writeAttribute('svg:font-family', $name);
						$objWriter->endElement();
					}
				}
			}
			if(!in_array('Arial', $arrFonts)){
				$objWriter->startElement('style:font-face');
				$objWriter->writeAttribute('style:name', 'Arial');
				$objWriter->writeAttribute('svg:font-family', 'Arial');
				$objWriter->endElement();
			}
		}
		$objWriter->endElement();
		
		$objWriter->startElement('office:automatic-styles');
		$styles = PHPWord_Style::getStyles();
		$numPStyles = 0;
		if(count($styles) > 0) {
			foreach($styles as $styleName => $style) {
				if(preg_match('#^T[0-9]+$#', $styleName) != 0
					|| preg_match('#^P[0-9]+$#', $styleName) != 0){
					// PHPWord_Style_Font
					if($style instanceof PHPWord_Style_Font) {
						$objWriter->startElement('style:style');
						$objWriter->writeAttribute('style:name', $styleName);
						$objWriter->writeAttribute('style:family', 'text');
							// style:text-properties
							$objWriter->startElement('style:text-properties');
							$objWriter->writeAttribute('fo:color', '#'.$style->getColor());
							$objWriter->writeAttribute('style:font-name', $style->getName());
							$objWriter->writeAttribute('style:font-name-complex', $style->getName());
							$objWriter->endElement();
						$objWriter->endElement();
					}
					if($style instanceof PHPWord_Style_Paragraph){
						$numPStyles++;
						// style:style
						$objWriter->startElement('style:style');
						$objWriter->writeAttribute('style:name', $styleName);
						$objWriter->writeAttribute('style:family', 'paragraph');
						$objWriter->writeAttribute('style:parent-style-name', 'Standard');
						$objWriter->writeAttribute('style:master-page-name', 'Standard');
							// style:paragraph-properties
							$objWriter->startElement('style:paragraph-properties');
							$objWriter->writeAttribute('style:page-number', 'auto');
							$objWriter->endElement();
						$objWriter->endElement();
					}
				}
			}
			
			if($numPStyles == 0){
				// style:style
				$objWriter->startElement('style:style');
				$objWriter->writeAttribute('style:name', 'P1');
				$objWriter->writeAttribute('style:family', 'paragraph');
				$objWriter->writeAttribute('style:parent-style-name', 'Standard');
				$objWriter->writeAttribute('style:master-page-name', 'Standard');
					// style:paragraph-properties
					$objWriter->startElement('style:paragraph-properties');
					$objWriter->writeAttribute('style:page-number', 'auto');
					$objWriter->endElement();
				$objWriter->endElement();
			}
		}
		$objWriter->endElement();
						
			// office:body
			$objWriter->startElement('office:body');
				// office:text
				$objWriter->startElement('office:text');
					// text:sequence-decls
					$objWriter->startElement('text:sequence-decls');
						// text:sequence-decl
						$objWriter->startElement('text:sequence-decl');
						$objWriter->writeAttribute('text:display-outline-level', 0);
						$objWriter->writeAttribute('text:name', 'Illustration');
						$objWriter->endElement();
						// text:sequence-decl
						$objWriter->startElement('text:sequence-decl');
						$objWriter->writeAttribute('text:display-outline-level', 0);
						$objWriter->writeAttribute('text:name', 'Table');
						$objWriter->endElement();
						// text:sequence-decl
						$objWriter->startElement('text:sequence-decl');
						$objWriter->writeAttribute('text:display-outline-level', 0);
						$objWriter->writeAttribute('text:name', 'Text');
						$objWriter->endElement();
						// text:sequence-decl
						$objWriter->startElement('text:sequence-decl');
						$objWriter->writeAttribute('text:display-outline-level', 0);
						$objWriter->writeAttribute('text:name', 'Drawing');
						$objWriter->endElement();
					$objWriter->endElement();
					
					$_sections = $pPHPWord->getSections();
					$countSections = count($_sections);
					$pSection = 0;
					
					if($countSections > 0) {
						foreach($_sections as $section) {
							$pSection++;
					
							$_elements = $section->getElements();
					
							foreach($_elements as $element) {
								if($element instanceof PHPWord_Section_Text) {
									$this->_writeText($objWriter, $element);
								}/* elseif($element instanceof PHPWord_Section_TextRun) {
									$this->_writeTextRun($objWriter, $element);
								} elseif($element instanceof PHPWord_Section_Link) {
									$this->_writeLink($objWriter, $element);
								} elseif($element instanceof PHPWord_Section_Title) {
									$this->_writeTitle($objWriter, $element);
								}*/ elseif($element instanceof PHPWord_Section_TextBreak) {
									$this->_writeTextBreak($objWriter);
								}/* elseif($element instanceof PHPWord_Section_PageBreak) {
									$this->_writePageBreak($objWriter);
								} elseif($element instanceof PHPWord_Section_Table) {
									$this->_writeTable($objWriter, $element);
								} elseif($element instanceof PHPWord_Section_ListItem) {
									$this->_writeListItem($objWriter, $element);
								} elseif($element instanceof PHPWord_Section_Image ||
								$element instanceof PHPWord_Section_MemoryImage) {
									$this->_writeImage($objWriter, $element);
								} elseif($element instanceof PHPWord_Section_Object) {
									$this->_writeObject($objWriter, $element);
								} elseif($element instanceof PHPWord_TOC) {
									$this->_writeTOC($objWriter);
								}*/
								else {
									print_r($element);
									echo '<br />';
								}
							}
					
							if($pSection == $countSections) {
								$this->_writeEndSection($objWriter, $section);
							} else {
								$this->_writeSection($objWriter, $section);
							}
						}
					}
				$objWriter->endElement();
			$objWriter->endElement();
		$objWriter->endElement();

		// Return
		return $objWriter->getData();
	}
	
	protected function _writeText(PHPWord_Shared_XMLWriter $objWriter = null, PHPWord_Section_Text $text, $withoutP = false) {
		$styleFont = $text->getFontStyle();
		$styleParagraph = $text->getParagraphStyle();
		
		$SfIsObject = ($styleFont instanceof PHPWord_Style_Font) ? true : false;
		
		if($SfIsObject) {
			// Don't never be the case, because I browse all sections for cleaning all styles not declared
			die('PHPWord : $SfIsObject wouldn\'t be an object');
		}
		else {
			// text:p
			$objWriter->startElement('text:p');
			if(empty($styleFont)){
				if(empty($styleParagraph)){
					$objWriter->writeAttribute('text:style-name', 'P1');
				}
				else {
					$objWriter->writeAttribute('text:style-name', $text->getParagraphStyle());
				}
				$objWriter->writeRaw($text->getText());
			}
			else {
				if(empty($styleParagraph)){
					$objWriter->writeAttribute('text:style-name', 'Standard');
				}
				else {
					$objWriter->writeAttribute('text:style-name', $text->getParagraphStyle());
				}
				// text:span
				$objWriter->startElement('text:span');
				$objWriter->writeAttribute('text:style-name', $styleFont);
				$objWriter->writeRaw($text->getText());
				$objWriter->endElement();
			}
			$objWriter->endElement();
		}
	}
	protected function _writeTextBreak(PHPWord_Shared_XMLWriter $objWriter = null) {
		$objWriter->startElement('text:p');
		$objWriter->writeAttribute('text:style-name', 'Standard');
		$objWriter->endElement();
	}
	private function _writeEndSection(PHPWord_Shared_XMLWriter $objWriter = null, PHPWord_Section $section) {
	}
}
