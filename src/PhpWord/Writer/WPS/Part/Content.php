<?php

/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\WPS\Part;

use PhpOffice\PhpWord\Element\AbstractContainer;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Writer\WPS\Media;
use XMLWriter;

/**
 * WPS content part writer
 */
class Content extends AbstractPart
{
    /**
     * Write content file.
     */
    public function write(): string
    {
        $phpWord = $this->getParentWriter()->getPhpWord();
        $xmlWriter = $this->getXmlWriter();
        
        // XML header
        $xmlWriter->startDocument('1.0', 'UTF-8');
        
        // office:document-content
        $xmlWriter->startElement('office:document-content');
        $xmlWriter->writeAttribute('xmlns:office', 'urn:oasis:names:tc:opendocument:xmlns:office:1.0');
        $xmlWriter->writeAttribute('xmlns:style', 'urn:oasis:names:tc:opendocument:xmlns:style:1.0');
        $xmlWriter->writeAttribute('xmlns:text', 'urn:oasis:names:tc:opendocument:xmlns:text:1.0');
        $xmlWriter->writeAttribute('xmlns:table', 'urn:oasis:names:tc:opendocument:xmlns:table:1.0');
        $xmlWriter->writeAttribute('xmlns:draw', 'urn:oasis:names:tc:opendocument:xmlns:drawing:1.0');
        $xmlWriter->writeAttribute('xmlns:fo', 'urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0');
        $xmlWriter->writeAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
        $xmlWriter->writeAttribute('xmlns:dc', 'http://purl.org/dc/elements/1.1/');
        $xmlWriter->writeAttribute('xmlns:meta', 'urn:oasis:names:tc:opendocument:xmlns:meta:1.0');
        $xmlWriter->writeAttribute('xmlns:number', 'urn:oasis:names:tc:opendocument:xmlns:datastyle:1.0');
        $xmlWriter->writeAttribute('xmlns:svg', 'urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0');
        $xmlWriter->writeAttribute('xmlns:chart', 'urn:oasis:names:tc:opendocument:xmlns:chart:1.0');
        $xmlWriter->writeAttribute('xmlns:dr3d', 'urn:oasis:names:tc:opendocument:xmlns:dr3d:1.0');
        $xmlWriter->writeAttribute('xmlns:math', 'http://www.w3.org/1998/Math/MathML');
        $xmlWriter->writeAttribute('xmlns:form', 'urn:oasis:names:tc:opendocument:xmlns:form:1.0');
        $xmlWriter->writeAttribute('xmlns:script', 'urn:oasis:names:tc:opendocument:xmlns:script:1.0');
        $xmlWriter->writeAttribute('xmlns:ooo', 'http://openoffice.org/2004/office');
        $xmlWriter->writeAttribute('xmlns:ooow', 'http://openoffice.org/2004/writer');
        $xmlWriter->writeAttribute('xmlns:oooc', 'http://openoffice.org/2004/calc');
        $xmlWriter->writeAttribute('xmlns:dom', 'http://www.w3.org/2001/xml-events');
        $xmlWriter->writeAttribute('xmlns:xforms', 'http://www.w3.org/2002/xforms');
        $xmlWriter->writeAttribute('xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');
        $xmlWriter->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $xmlWriter->writeAttribute('xmlns:rpt', 'http://openoffice.org/2005/report');
        $xmlWriter->writeAttribute('xmlns:of', 'urn:oasis:names:tc:opendocument:xmlns:of:1.2');
        $xmlWriter->writeAttribute('office:version', '1.2');
        
        // office:scripts
        $xmlWriter->startElement('office:scripts');
        $xmlWriter->endElement();
        
        // office:font-face-decls
        $xmlWriter->startElement('office:font-face-decls');
        $xmlWriter->startElement('style:font-face');
        $xmlWriter->writeAttribute('style:name', 'Arial');
        $xmlWriter->writeAttribute('svg:font-family', 'Arial');
        $xmlWriter->endElement();
        $xmlWriter->endElement();
        
        // office:automatic-styles
        $xmlWriter->startElement('office:automatic-styles');
        $xmlWriter->endElement();
        
        // office:body
        $xmlWriter->startElement('office:body');
        
        // office:text
        $xmlWriter->startElement('office:text');
        
        // Write sections
        $sections = $phpWord->getSections();
        foreach ($sections as $section) {
            $this->writeSection($xmlWriter, $section);
        }
        
        $xmlWriter->endElement(); // office:text
        $xmlWriter->endElement(); // office:body
        $xmlWriter->endElement(); // office:document-content
        
        return $xmlWriter->getData();
    }
    
    /**
     * Write section
     */
    private function writeSection(XMLWriter $xmlWriter, Section $section): void
    {
        $xmlWriter->startElement('text:section');
        $xmlWriter->writeAttribute('text:style-name', 'Sect' . $section->getSectionId());
        $xmlWriter->writeAttribute('text:name', 'Section' . $section->getSectionId());
        
        // Process all elements
        $elements = $section->getElements();
        $this->writeElements($xmlWriter, $elements);
        
        $xmlWriter->endElement(); // text:section
    }
    
    /**
     * Write elements
     */
    private function writeElements(XMLWriter $xmlWriter, array $elements): void
    {
        foreach ($elements as $element) {
            if ($element instanceof TextRun) {
                $this->writeTextRun($xmlWriter, $element);
            } elseif ($element instanceof Text) {
                $this->writeText($xmlWriter, $element);
            } elseif ($element instanceof Table) {
                $this->writeTable($xmlWriter, $element);
            } elseif ($element instanceof AbstractContainer) {
                $this->writeElements($xmlWriter, $element->getElements());
            }
        }
    }
    
    /**
     * Write text element
     */
    private function writeText(XMLWriter $xmlWriter, Text $text): void
    {
        $xmlWriter->startElement('text:p');
        $xmlWriter->writeRaw($text->getText());
        $xmlWriter->endElement();
    }
    
    /**
     * Write text run element
     */
    private function writeTextRun(XMLWriter $xmlWriter, TextRun $textrun): void
    {
        $xmlWriter->startElement('text:p');
        
        $elements = $textrun->getElements();
        foreach ($elements as $element) {
            if ($element instanceof Text) {
                $xmlWriter->writeRaw($element->getText());
            }
        }
        
        $xmlWriter->endElement();
    }
    
    /**
     * Write table element
     */
    private function writeTable(XMLWriter $xmlWriter, Table $table): void
    {
        $xmlWriter->startElement('table:table');
        $xmlWriter->writeAttribute('table:name', 'Table' . $table->getElementId());
        
        $rows = $table->getRows();
        foreach ($rows as $row) {
            $xmlWriter->startElement('table:table-row');
            
            $cells = $row->getCells();
            foreach ($cells as $cell) {
                $xmlWriter->startElement('table:table-cell');
                
                $elements = $cell->getElements();
                $this->writeElements($xmlWriter, $elements);
                
                $xmlWriter->endElement(); // table:table-cell
            }
            
            $xmlWriter->endElement(); // table:table-row
        }
        
        $xmlWriter->endElement(); // table:table
    }
}
