<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Reader\Word2007;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\XMLReader;

/**
 * Document reader
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
                                if (!is_null($settings)) {
                                    $this->readHeaderFooter($settings, $section);
                                }
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
                        if (!is_null($settings)) {
                            $this->readHeaderFooter($settings, $section);
                        }
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
     * Read w:p
     *
     * @param mixed $parent
     * @param string $docPart
     *
     * @todo Get font style for preserve text
     */
    private function readParagraph(XMLReader $xmlReader, \DOMElement $domNode, &$parent, $docPart)
    {
        // Paragraph style
        $paragraphStyle = null;
        $headingMatches = array();
        if ($xmlReader->elementExists('w:pPr', $domNode)) {
            $paragraphStyle = $this->readParagraphStyle($xmlReader, $domNode);
            if (is_string($paragraphStyle)) {
                preg_match('/Heading(\d)/', $paragraphStyle, $headingMatches);
            }
        }

        // PreserveText
        if ($xmlReader->elementExists('w:r/w:instrText', $domNode)) {
            $ignoreText = false;
            $textContent = '';
            $fontStyle = $this->readFontStyle($xmlReader, $domNode);
            $nodes = $xmlReader->getElements('w:r', $domNode);
            foreach ($nodes as $node) {
                $instrText = $xmlReader->getValue('w:instrText', $node);
                if ($xmlReader->elementExists('w:fldChar', $node)) {
                    $fldCharType = $xmlReader->getAttribute('w:fldCharType', $node, 'w:fldChar');
                    if ($fldCharType == 'begin') {
                        $ignoreText = true;
                    } elseif ($fldCharType == 'end') {
                        $ignoreText = false;
                    }
                }
                if (!is_null($instrText)) {
                    $textContent .= '{' . $instrText . '}';
                } else {
                    if ($ignoreText === false) {
                        $textContent .= $xmlReader->getValue('w:t', $node);
                    }
                }
            }
            $parent->addPreserveText($textContent, $fontStyle, $paragraphStyle);

        // List item
        } elseif ($xmlReader->elementExists('w:pPr/w:numPr', $domNode)) {
            $textContent = '';
            $numId = $xmlReader->getAttribute('w:val', $domNode, 'w:pPr/w:numPr/w:numId');
            $levelId = $xmlReader->getAttribute('w:val', $domNode, 'w:pPr/w:numPr/w:ilvl');
            $nodes = $xmlReader->getElements('w:r', $domNode);
            foreach ($nodes as $node) {
                $textContent .= $xmlReader->getValue('w:t', $node);
            }
            $parent->addListItem($textContent, $levelId, null, "PHPWordList{$numId}", $paragraphStyle);

        // Heading
        } elseif (!empty($headingMatches)) {
            $textContent = '';
            $nodes = $xmlReader->getElements('w:r', $domNode);
            foreach ($nodes as $node) {
                $textContent .= $xmlReader->getValue('w:t', $node);
            }
            $parent->addTitle($textContent, $headingMatches[1]);

        // Text and TextRun
        } else {
            $runCount = $xmlReader->countElements('w:r', $domNode);
            $linkCount = $xmlReader->countElements('w:hyperlink', $domNode);
            $runLinkCount = $runCount + $linkCount;
            if ($runLinkCount == 0) {
                $parent->addTextBreak(null, $paragraphStyle);
            } else {
                if ($runLinkCount > 1) {
                    $textrun = $parent->addTextRun($paragraphStyle);
                    $textParent = &$textrun;
                } else {
                    $textParent = &$parent;
                }
                $nodes = $xmlReader->getElements('*', $domNode);
                foreach ($nodes as $node) {
                    $this->readRun($xmlReader, $node, $textParent, $docPart, $paragraphStyle);
                }
            }
        }
    }

    /**
     * Read w:tbl
     *
     * @param mixed $parent
     * @param string $docPart
     */
    private function readTable(XMLReader $xmlReader, \DOMElement $domNode, &$parent, $docPart)
    {
        // Table style
        $tblStyle = null;
        if ($xmlReader->elementExists('w:tblPr', $domNode)) {
            $tblStyle = $this->readTableStyle($xmlReader, $domNode);
        }

        $table = $parent->addTable($tblStyle);
        $tblNodes = $xmlReader->getElements('*', $domNode);
        foreach ($tblNodes as $tblNode) {
            if ($tblNode->nodeName == 'w:tblGrid') { // Column
                // @todo Do something with table columns

            } elseif ($tblNode->nodeName == 'w:tr') { // Row
                $rowHeight = $xmlReader->getAttribute('w:val', $tblNode, 'w:trPr/w:trHeight');
                $rowHRule = $xmlReader->getAttribute('w:hRule', $tblNode, 'w:trPr/w:trHeight');
                $rowHRule = $rowHRule == 'exact' ? true : false;
                $rowStyle = array(
                    'tblHeader' => $xmlReader->elementExists('w:trPr/w:tblHeader', $tblNode),
                    'cantSplit' => $xmlReader->elementExists('w:trPr/w:cantSplit', $tblNode),
                    'exactHeight' => $rowHRule,
                );

                $row = $table->addRow($rowHeight, $rowStyle);
                $rowNodes = $xmlReader->getElements('*', $tblNode);
                foreach ($rowNodes as $rowNode) {
                    if ($rowNode->nodeName == 'w:trPr') { // Row style
                        // @todo Do something with row style

                    } elseif ($rowNode->nodeName == 'w:tc') { // Cell
                        $cellWidth = $xmlReader->getAttribute('w:w', $rowNode, 'w:tcPr/w:tcW');
                        $cellStyle = null;
                        $cellStyleNode = $xmlReader->getElement('w:tcPr', $rowNode);
                        if (!is_null($cellStyleNode)) {
                            $cellStyle = $this->readCellStyle($xmlReader, $cellStyleNode);
                        }

                        $cell = $row->addCell($cellWidth, $cellStyle);
                        $cellNodes = $xmlReader->getElements('*', $rowNode);
                        foreach ($cellNodes as $cellNode) {
                            if ($cellNode->nodeName == 'w:p') { // Paragraph
                                $this->readParagraph($xmlReader, $cellNode, $cell, $docPart);
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
     * @return array|null
     */
    private function readSectionStyle(XMLReader $xmlReader, \DOMElement $domNode)
    {
        $ret = null;
        $mapping = array(
            'w:type' => 'breakType', 'w:pgSz' => 'pageSize',
            'w:pgMar' => 'pageMargin', 'w:cols' => 'columns',
            'w:headerReference' => 'header', 'w:footerReference' => 'footer',
        );
        $nodes = $xmlReader->getElements('*', $domNode);
        foreach ($nodes as $node) {
            if (!array_key_exists($node->nodeName, $mapping)) {
                continue;
            }
            $property = $mapping[$node->nodeName];
            switch ($node->nodeName) {

                case 'w:type':
                    $ret['breakType'] = $xmlReader->getAttribute('w:val', $node);
                    break;

                case 'w:pgSz':
                    $ret['pageSizeW'] = $xmlReader->getAttribute('w:w', $node);
                    $ret['pageSizeH'] = $xmlReader->getAttribute('w:h', $node);
                    $ret['orientation'] = $xmlReader->getAttribute('w:orient', $node);
                    break;

                case 'w:pgMar':
                    $ret['topMargin'] = $xmlReader->getAttribute('w:top', $node);
                    $ret['leftMargin'] = $xmlReader->getAttribute('w:left', $node);
                    $ret['bottomMargin'] = $xmlReader->getAttribute('w:bottom', $node);
                    $ret['rightMargin'] = $xmlReader->getAttribute('w:right', $node);
                    $ret['headerHeight'] = $xmlReader->getAttribute('w:header', $node);
                    $ret['footerHeight'] = $xmlReader->getAttribute('w:footer', $node);
                    $ret['gutter'] = $xmlReader->getAttribute('w:gutter', $node);
                    break;

                case 'w:cols':
                    $ret['colsNum'] = $xmlReader->getAttribute('w:num', $node);
                    $ret['colsSpace'] = $xmlReader->getAttribute('w:space', $node);
                    break;

                case 'w:headerReference':
                case 'w:footerReference':
                    $id = $xmlReader->getAttribute('r:id', $node);
                    $ret['hf'][$id] = array(
                        'method' => $property,
                        'type' => $xmlReader->getAttribute('w:type', $node),
                    );
                    break;
            }
        }

        return $ret;
    }

    /**
     * Read w:tcPr
     *
     * @return array|null
     */
    private function readCellStyle(XMLReader $xmlReader, \DOMElement $domNode)
    {
        $style = null;
        $mapping = array(
            'w:shd' => 'bgColor',
            'w:vAlign' => 'valign', 'w:textDirection' => 'textDirection',
            'w:gridSpan' => 'gridSpan', 'w:vMerge' => 'vMerge',
        );
        $nodes = $xmlReader->getElements('*', $domNode);
        foreach ($nodes as $node) {
            if (!array_key_exists($node->nodeName, $mapping)) {
                continue;
            }
            $property = $mapping[$node->nodeName];
            switch ($node->nodeName) {
                case 'w:shd':
                    $style['bgColor'] = $xmlReader->getAttribute('w:fill', $node);
                    break;

                default:
                    $style[$property] = $xmlReader->getAttribute('w:val', $node);
                    break;
            }
        }

        return $style;
    }
}
