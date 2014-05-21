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
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Reader\Word2007;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\XMLReader;

/**
 * Document reader
 *
 * @since 0.10.0
 */
class Document extends AbstractPart
{
    /**
     * Read document.xml
     *
     * @param \PhpOffice\PhpWord\PhpWord $phpWord
     */
    public function read(PhpWord &$phpWord)
    {
        $xmlReader = new XMLReader();
        $xmlReader->getDomFromZip($this->docFile, $this->xmlFile);

        $nodes = $xmlReader->getElements('w:body/*');
        if ($nodes->length > 0) {
            $section = $phpWord->addSection();
            foreach ($nodes as $node) {
                switch ($node->nodeName) {

                    case 'w:p': // Paragraph
                        // Page break
                        // @todo <w:lastRenderedPageBreak>
                        if ($xmlReader->getAttribute('w:type', $node, 'w:r/w:br') == 'page') {
                            $section->addPageBreak(); // PageBreak
                        }

                        // Paragraph
                        $this->readParagraph($xmlReader, $node, $section, 'document');
                        // Section properties
                        if ($xmlReader->elementExists('w:pPr/w:sectPr', $node)) {
                            $settingsNode = $xmlReader->getElement('w:pPr/w:sectPr', $node);
                            if (!is_null($settingsNode)) {
                                $settings = $this->readSectionStyle($xmlReader, $settingsNode);
                                $section->setSettings($settings);
                                $this->readHeaderFooter($settings, $section);
                            }
                            $section = $phpWord->addSection();
                        }
                        break;

                    case 'w:tbl': // Table
                        $this->readTable($xmlReader, $node, $section, 'document');
                        break;

                    case 'w:sectPr': // Last section
                        $settings = $this->readSectionStyle($xmlReader, $node);
                        $section->setSettings($settings);
                        $this->readHeaderFooter($settings, $section);
                        break;
                }
            }
        }
    }

    /**
     * Read header footer
     *
     * @param array $settings
     * @param \PhpOffice\PhpWord\Element\Section $section
     */
    private function readHeaderFooter($settings, &$section)
    {
        if (is_array($settings) && array_key_exists('hf', $settings)) {
            foreach ($settings['hf'] as $rId => $hfSetting) {
                if (array_key_exists($rId, $this->rels['document'])) {
                    list($hfType, $xmlFile, $docPart) = array_values($this->rels['document'][$rId]);
                    $method = "add{$hfType}";
                    $hfObject = $section->$method($hfSetting['type']);

                    // Read header/footer content
                    $xmlReader = new XMLReader();
                    $xmlReader->getDomFromZip($this->docFile, $xmlFile);
                    $nodes = $xmlReader->getElements('*');
                    if ($nodes->length > 0) {
                        foreach ($nodes as $node) {
                            switch ($node->nodeName) {

                                case 'w:p': // Paragraph
                                    $this->readParagraph($xmlReader, $node, $hfObject, $docPart);
                                    break;

                                case 'w:tbl': // Table
                                    $this->readTable($xmlReader, $node, $hfObject, $docPart);
                                    break;
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Read w:sectPr
     *
     * @param \PhpOffice\PhpWord\Shared\XMLReader $xmlReader
     * @param \DOMElement $domNode
     * @return array
     */
    private function readSectionStyle(XMLReader $xmlReader, \DOMElement $domNode)
    {
        $styleDefs = array(
            'breakType'     => array(self::READ_VALUE, 'w:type'),
            'pageSizeW'     => array(self::READ_VALUE, 'w:pgSz', 'w:w'),
            'pageSizeH'     => array(self::READ_VALUE, 'w:pgSz', 'w:h'),
            'orientation'   => array(self::READ_VALUE, 'w:pgSz', 'w:orient'),
            'colsNum'       => array(self::READ_VALUE, 'w:cols', 'w:num'),
            'colsSpace'     => array(self::READ_VALUE, 'w:cols', 'w:space'),
            'topMargin'     => array(self::READ_VALUE, 'w:pgMar', 'w:top'),
            'leftMargin'    => array(self::READ_VALUE, 'w:pgMar', 'w:left'),
            'bottomMargin'  => array(self::READ_VALUE, 'w:pgMar', 'w:bottom'),
            'rightMargin'   => array(self::READ_VALUE, 'w:pgMar', 'w:right'),
            'headerHeight'  => array(self::READ_VALUE, 'w:pgMar', 'w:header'),
            'footerHeight'  => array(self::READ_VALUE, 'w:pgMar', 'w:footer'),
            'gutter'        => array(self::READ_VALUE, 'w:pgMar', 'w:gutter'),
        );
        $styles = $this->readStyleDefs($xmlReader, $domNode, $styleDefs);

        // Header and footer
        // @todo Cleanup this part
        $nodes = $xmlReader->getElements('*', $domNode);
        foreach ($nodes as $node) {
            if ($node->nodeName == 'w:headerReference' || $node->nodeName == 'w:footerReference') {
                $id = $xmlReader->getAttribute('r:id', $node);
                $styles['hf'][$id] = array(
                    'method' => str_replace('w:', '', str_replace('Reference', '', $node->nodeName)),
                    'type' => $xmlReader->getAttribute('w:type', $node),
                );
            }
        }

        return $styles;
    }
}
