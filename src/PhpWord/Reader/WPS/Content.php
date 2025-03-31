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

namespace PhpOffice\PhpWord\Reader\WPS;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\XMLReader;

/**
 * WPS content reader
 *
 * @since 0.18.0
 */
class Content extends AbstractPart
{
    /**
     * Read content.xml.
     */
    public function read(PhpWord $phpWord): void
    {
        $xmlReader = new XMLReader();
        $xmlReader->getDomFromZip($this->docFile, $this->xmlFile);
        
        $nodes = $xmlReader->getElements('office:body/office:text/*');
        if ($nodes->length > 0) {
            $section = $phpWord->addSection();
            foreach ($nodes as $node) {
                $this->readElement($xmlReader, $node, $section);
            }
        }
    }
    
    /**
     * Read element based on node name
     */
    private function readElement(XMLReader $xmlReader, \DOMElement $node, \PhpOffice\PhpWord\Element\Section $parent): void
    {
        switch ($node->nodeName) {
            case 'text:p':
                $this->readParagraph($xmlReader, $node, $parent);
                break;
            case 'text:h':
                $this->readHeading($xmlReader, $node, $parent);
                break;
            case 'table:table':
                // Implement table reading as needed
                break;
        }
    }
    
    /**
     * Read paragraph
     */
    protected function readParagraph(XMLReader $xmlReader, \DOMElement $domNode, $parent, $docPart = 'document'): void
    {
        $textRun = $parent->addTextRun();
        $nodes = $xmlReader->getElements('*', $domNode);
        foreach ($nodes as $textNode) {
            if ($textNode->nodeName == 'text:span') {
                $text = $xmlReader->getValue('.', $textNode);
                if (!empty($text)) {
                    $textRun->addText($text);
                }
            } elseif ($textNode->nodeName == 'text:line-break') {
                $textRun->addTextBreak();
            }
        }
        
        // If the paragraph has direct text content (not wrapped in spans)
        $textContent = $this->getDirectTextContent($domNode);
        if (!empty($textContent)) {
            $textRun->addText($textContent);
        }
    }
    
    /**
     * Read heading
     */
    private function readHeading(XMLReader $xmlReader, \DOMElement $node, \PhpOffice\PhpWord\Element\Section $parent): void
    {
        $text = $xmlReader->getValue('.', $node);
        $level = $xmlReader->getAttribute('text:outline-level', $node);
        if (empty($level)) {
            $level = 1;
        }
        $parent->addTitle($text, $level);
    }
    
    /**
     * Get direct text content of a node, excluding child element content
     */
    private function getDirectTextContent(\DOMElement $node): string
    {
        $textContent = '';
        foreach ($node->childNodes as $child) {
            if ($child->nodeType === XML_TEXT_NODE) {
                $textContent .= $child->nodeValue;
            }
        }
        return trim($textContent);
    }
}