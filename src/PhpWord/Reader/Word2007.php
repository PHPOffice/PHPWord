<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Reader;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\DocumentProperties;
use PhpOffice\PhpWord\Shared\XMLReader;
use PhpOffice\PhpWord\Element\Section;

/**
 * Reader for Word2007
 *
 * @since 0.9.2
 * @todo title, list, watermark, checkbox, toc
 * @todo Partly done: image, object
 */
class Word2007 extends AbstractReader implements ReaderInterface
{
    /**
     * PhpWord object
     *
     * @var PhpWord
     */
    private $phpWord;

    /**
     * Part relationships
     *
     * @var array
     */
    private $rels = array('main' => array(), 'document' => array());

    /**
     * Loads PhpWord from file
     *
     * @param string $filename
     * @return PhpWord
     */
    public function load($filename)
    {
        $this->phpWord = new PhpWord();

        $this->readRelationships($filename);


        // Read styles and numbering first
        foreach ($this->rels['document'] as $rId => $rel) {
            switch ($rel['type']) {
                case 'styles':
                    $this->readStyles($filename, $rel['target']);
                    break;
                case 'numbering':
                    $this->readNumbering($filename, $rel['target']);
                    break;
            }
        }

        // Read main relationship
        foreach ($this->rels['main'] as $rId => $rel) {
            switch ($rel['type']) {

                case 'officeDocument':
                    $this->readDocument($filename, $rel['target']);
                    break;

                case 'core-properties':
                    $mapping = array(
                        'dc:creator' => 'setCreator',
                        'dc:title' => 'setTitle',
                        'dc:description' => 'setDescription',
                        'dc:subject' => 'setSubject',
                        'cp:keywords' => 'setKeywords',
                        'cp:category' => 'setCategory',
                        'cp:lastModifiedBy' => 'setLastModifiedBy',
                        'dcterms:created' => 'setCreated',
                        'dcterms:modified' => 'setModified',
                    );
                    $callbacks = array('dcterms:created' => 'strtotime', 'dcterms:modified' => 'strtotime');
                    $this->readDocProps($filename, $rel['target'], $mapping, $callbacks);
                    break;

                case 'extended-properties':
                    $mapping = array('Company' => 'setCompany', 'Manager' => 'setManager');
                    $this->readDocProps($filename, $rel['target'], $mapping);
                    break;

                case 'custom-properties':
                    $this->readDocPropsCustom($filename, $rel['target']);
                    break;
            }
        }

        // Read footnotes and endnotes
        foreach ($this->rels['document'] as $rId => $rel) {
            switch ($rel['type']) {
                case 'footnotes':
                case 'endnotes':
                    $this->readNotes($filename, $rel['target'], $rel['type']);
                    break;
            }
        }

        return $this->phpWord;
    }

    /**
     * Read all relationship files
     *
     * @param string $filename
     */
    private function readRelationships($filename)
    {
        // _rels/.rels
        $this->rels['main'] = $this->getRels($filename, '_rels/.rels');

        // word/_rels/*.xml.rels
        $wordRelsPath = 'word/_rels/';
        $zipClass = Settings::getZipClass();
        $zip = new $zipClass();
        if ($zip->open($filename) === true) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $xmlFile = $zip->getNameIndex($i);
                if ((substr($xmlFile, 0, strlen($wordRelsPath))) == $wordRelsPath) {
                    $docPart = str_replace('.xml.rels', '', str_replace($wordRelsPath, '', $xmlFile));
                    $this->rels[$docPart] = $this->getRels($filename, $xmlFile, 'word/');
                }
            }
            $zip->close();
        }
    }

    /**
     * Read core and extended document properties
     *
     * @param string $filename
     * @param string $xmlFile
     * @param array $mapping
     * @param array $callbacks
     */
    private function readDocProps($filename, $xmlFile, $mapping, $callbacks = array())
    {
        $xmlReader = new XMLReader();
        $xmlReader->getDomFromZip($filename, $xmlFile);
        $docProps = $this->phpWord->getDocumentProperties();

        $nodes = $xmlReader->getElements('*');
        if ($nodes->length > 0) {
            foreach ($nodes as $node) {
                if (!array_key_exists($node->nodeName, $mapping)) {
                    continue;
                }
                $method = $mapping[$node->nodeName];
                $value = $node->nodeValue == '' ? null : $node->nodeValue;
                if (array_key_exists($node->nodeName, $callbacks)) {
                    $value = $callbacks[$node->nodeName]($value);
                }
                if (method_exists($docProps, $method)) {
                    $docProps->$method($value);
                }
            }
        }
    }

    /**
     * Read custom document properties
     *
     * @param string $filename
     * @param string $xmlFile
     */
    private function readDocPropsCustom($filename, $xmlFile)
    {
        $xmlReader = new XMLReader();
        $xmlReader->getDomFromZip($filename, $xmlFile);
        $docProps = $this->phpWord->getDocumentProperties();

        $nodes = $xmlReader->getElements('*');
        if ($nodes->length > 0) {
            foreach ($nodes as $node) {
                $propertyName = $xmlReader->getAttribute('name', $node);
                $attributeNode = $xmlReader->getElement('*', $node);
                $attributeType = $attributeNode->nodeName;
                $attributeValue = $attributeNode->nodeValue;
                $attributeValue = DocumentProperties::convertProperty($attributeValue, $attributeType);
                $attributeType = DocumentProperties::convertPropertyType($attributeType);
                $docProps->setCustomProperty($propertyName, $attributeValue, $attributeType);
            }
        }
    }

    /**
     * Read document.xml
     *
     * @param string $filename
     * @param string $xmlFile
     */
    private function readDocument($filename, $xmlFile)
    {
        $xmlReader = new XMLReader();
        $xmlReader->getDomFromZip($filename, $xmlFile);

        $nodes = $xmlReader->getElements('w:body/*');
        if ($nodes->length > 0) {
            $section = $this->phpWord->addSection();
            foreach ($nodes as $node) {
                switch ($node->nodeName) {

                    case 'w:p': // Paragraph
                        if ($xmlReader->getAttribute('w:type', $node, 'w:r/w:br') == 'page') {
                            $section->addPageBreak(); // PageBreak
                        } else {
                            $this->readParagraph($xmlReader, $node, $section, 'document');
                        }
                        // Section properties
                        if ($xmlReader->elementExists('w:pPr/w:sectPr', $node)) {
                            $settingsNode = $xmlReader->getElement('w:pPr/w:sectPr', $node);
                            if (!is_null($settingsNode)) {
                                $settings = $this->readSectionStyle($xmlReader, $settingsNode);
                                $section->setSettings($settings);
                                $this->readHeaderFooter($filename, $settings, $section);
                            }
                            $section = $this->phpWord->addSection();
                        }
                        break;

                    case 'w:tbl': // Table
                        $this->readTable($xmlReader, $node, $section, 'document');
                        break;

                    case 'w:sectPr': // Last section
                        $settings = $this->readSectionStyle($xmlReader, $node);
                        $section->setSettings($settings);
                        $this->readHeaderFooter($filename, $settings, $section);
                        break;
                }
            }
        }
    }

    /**
     * Read styles.xml
     *
     * @param string $filename
     * @param string $xmlFile
     */
    private function readStyles($filename, $xmlFile)
    {
        $xmlReader = new XMLReader();
        $xmlReader->getDomFromZip($filename, $xmlFile);

        $nodes = $xmlReader->getElements('w:style');
        if ($nodes->length > 0) {
            foreach ($nodes as $node) {
                $type = $xmlReader->getAttribute('w:type', $node);
                $name = $xmlReader->getAttribute('w:styleId', $node);
                if (is_null($name)) {
                    $name = $xmlReader->getAttribute('w:val', $node, 'w:name');
                }
                // $default = ($xmlReader->getAttribute('w:default', $node) == 1);
                switch ($type) {

                    case 'paragraph':
                        $pStyle = $this->readParagraphStyle($xmlReader, $node);
                        $fStyle = $this->readFontStyle($xmlReader, $node);
                        if (empty($fStyle)) {
                            if (is_array($pStyle)) {
                                $this->phpWord->addParagraphStyle($name, $pStyle);
                            }
                        } else {
                            $this->phpWord->addFontStyle($name, $fStyle, $pStyle);
                        }
                        break;

                    case 'character':
                        $fStyle = $this->readFontStyle($xmlReader, $node);
                        if (!empty($fStyle)) {
                            $this->phpWord->addFontStyle($name, $fStyle);
                        }
                        break;

                    case 'table':
                        $tStyle = $this->readTableStyle($xmlReader, $node);
                        if (!empty($tStyle)) {
                            $this->phpWord->addTableStyle($name, $tStyle);
                        }
                        break;
                }
            }
        }
    }

    /**
     * Read numbering.xml
     *
     * @param string $filename
     * @param string $xmlFile
     */
    private function readNumbering($filename, $xmlFile)
    {
        $abstracts = array();
        $numberings = array();
        $xmlReader = new XMLReader();
        $xmlReader->getDomFromZip($filename, $xmlFile);

        // Abstract numbering definition
        $nodes = $xmlReader->getElements('w:abstractNum');
        if ($nodes->length > 0) {
            foreach ($nodes as $node) {
                $abstractId = $xmlReader->getAttribute('w:abstractNumId', $node);
                $abstracts[$abstractId] = array('levels' => array());
                $abstract = &$abstracts[$abstractId];
                $subnodes = $xmlReader->getElements('*', $node);
                foreach ($subnodes as $subnode) {
                    switch ($subnode->nodeName) {
                        case 'w:multiLevelType':
                            $abstract['type'] = $xmlReader->getAttribute('w:val', $subnode);
                            break;
                        case 'w:lvl':
                            $levelId = $xmlReader->getAttribute('w:ilvl', $subnode);
                            $abstract['levels'][$levelId] = $this->readNumberingLevel($xmlReader, $subnode, $levelId);
                            break;
                    }
                }
            }
        }

        // Numbering instance definition
        $nodes = $xmlReader->getElements('w:num');
        if ($nodes->length > 0) {
            foreach ($nodes as $node) {
                $numId = $xmlReader->getAttribute('w:numId', $node);
                $abstractId = $xmlReader->getAttribute('w:val', $node, 'w:abstractNumId');
                $numberings[$numId] = $abstracts[$abstractId];
                $numberings[$numId]['numId'] = $numId;
                $subnodes = $xmlReader->getElements('w:lvlOverride/w:lvl', $node);
                foreach ($subnodes as $subnode) {
                    $levelId = $xmlReader->getAttribute('w:ilvl', $subnode);
                    $overrides = $this->readNumberingLevel($xmlReader, $subnode, $levelId);
                    foreach ($overrides as $key => $value) {
                        $numberings[$numId]['levels'][$levelId][$key] = $value;
                    }
                }
            }
        }

        // Push to Style collection
        foreach ($numberings as $numId => $numbering) {
            $this->phpWord->addNumberingStyle("PHPWordList{$numId}", $numbering);
        }
    }

    /**
     * Read numbering level definition from w:abstractNum and w:num
     *
     * @param integer $levelId
     * @return array
     */
    private function readNumberingLevel(XMLReader $xmlReader, \DOMElement $subnode, $levelId)
    {
        $level = array();

        $level['level'] = $levelId;
        $level['start'] = $xmlReader->getAttribute('w:val', $subnode, 'w:start');
        $level['format'] = $xmlReader->getAttribute('w:val', $subnode, 'w:numFmt');
        $level['restart'] = $xmlReader->getAttribute('w:val', $subnode, 'w:lvlRestart');
        $level['suffix'] = $xmlReader->getAttribute('w:val', $subnode, 'w:suff');
        $level['text'] = $xmlReader->getAttribute('w:val', $subnode, 'w:lvlText');
        $level['align'] = $xmlReader->getAttribute('w:val', $subnode, 'w:lvlJc');
        $level['tab'] = $xmlReader->getAttribute('w:pos', $subnode, 'w:pPr/w:tabs/w:tab');
        $level['left'] = $xmlReader->getAttribute('w:left', $subnode, 'w:pPr/w:ind');
        $level['hanging'] = $xmlReader->getAttribute('w:hanging', $subnode, 'w:pPr/w:ind');
        $level['font'] = $xmlReader->getAttribute('w:ascii', $subnode, 'w:rPr/w:rFonts');
        $level['hint'] = $xmlReader->getAttribute('w:hint', $subnode, 'w:rPr/w:rFonts');

        foreach ($level as $key => $value) {
            if (is_null($value)) {
                unset($level[$key]);
            }
        }

        return $level;
    }

    /**
     * Read header footer
     *
     * @param string $filename
     * @param array $settings
     * @param Section $section
     */
    private function readHeaderFooter($filename, $settings, &$section)
    {
        if (is_array($settings) && array_key_exists('hf', $settings)) {
            foreach ($settings['hf'] as $rId => $hfSetting) {
                if (array_key_exists($rId, $this->rels['document'])) {
                    list($hfType, $xmlFile, $docPart) = array_values($this->rels['document'][$rId]);
                    $method = "add{$hfType}";
                    $hfObject = $section->$method($hfSetting['type']);

                    // Read header/footer content
                    $xmlReader = new XMLReader();
                    $xmlReader->getDomFromZip($filename, $xmlFile);
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
     * Read (footnotes|endnotes).xml
     *
     * @param string $filename
     * @param string $xmlFile
     * @param string $notesType
     */
    private function readNotes($filename, $xmlFile, $notesType = 'footnotes')
    {
        $notesType = ($notesType == 'endnotes') ? 'endnotes' : 'footnotes';
        $collectionClass = 'PhpOffice\\PhpWord\\' . ucfirst($notesType);
        $collection = $collectionClass::getElements();

        $xmlReader = new XMLReader();
        $xmlReader->getDomFromZip($filename, $xmlFile);
        $nodes = $xmlReader->getElements('*');
        if ($nodes->length > 0) {
            foreach ($nodes as $node) {
                $id = $xmlReader->getAttribute('w:id', $node);
                $type = $xmlReader->getAttribute('w:type', $node);

                // Avoid w:type "separator" and "continuationSeparator"
                // Only look for <footnote> or <endnote> without w:type attribute
                if (is_null($type) && array_key_exists($id, $collection)) {
                    $element = $collection[$id];
                    $pNodes = $xmlReader->getElements('w:p/*', $node);
                    foreach ($pNodes as $pNode) {
                        $this->readRun($xmlReader, $pNode, $element, $notesType);
                    }
                    $collectionClass::setElement($id, $element);
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
        $pStyle = null;
        if ($xmlReader->elementExists('w:pPr', $domNode)) {
            $pStyle = $this->readParagraphStyle($xmlReader, $domNode);
        }

        // PreserveText
        if ($xmlReader->elementExists('w:r/w:instrText', $domNode)) {
            $ignoreText = false;
            $textContent = '';
            $fStyle = $this->readFontStyle($xmlReader, $domNode);
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
            $parent->addPreserveText($textContent, $fStyle, $pStyle);

        // List item
        } elseif ($xmlReader->elementExists('w:pPr/w:numPr', $domNode)) {
            $textContent = '';
            $numId = $xmlReader->getAttribute('w:val', $domNode, 'w:pPr/w:numPr/w:numId');
            $levelId = $xmlReader->getAttribute('w:val', $domNode, 'w:pPr/w:numPr/w:ilvl');
            $nodes = $xmlReader->getElements('w:r', $domNode);
            foreach ($nodes as $node) {
                $textContent .= $xmlReader->getValue('w:t', $node);
            }
            $parent->addListItem($textContent, $levelId, null, "PHPWordList{$numId}", $pStyle);

        // Text and TextRun
        } else {
            $runCount = $xmlReader->countElements('w:r', $domNode);
            $linkCount = $xmlReader->countElements('w:hyperlink', $domNode);
            $runLinkCount = $runCount + $linkCount;
            if ($runLinkCount == 0) {
                $parent->addTextBreak(null, $pStyle);
            } else {
                if ($runLinkCount > 1) {
                    $textrun = $parent->addTextRun($pStyle);
                    $textParent = &$textrun;
                } else {
                    $textParent = &$parent;
                }
                $nodes = $xmlReader->getElements('*', $domNode);
                foreach ($nodes as $node) {
                    $this->readRun($xmlReader, $node, $textParent, $docPart, $pStyle);
                }
            }
        }
    }

    /**
     * Read w:r
     *
     * @param \PhpOffice\PhpWord\Shared\XMLReader $xmlReader
     * @param \DOMElement $domNode
     * @param mixed $parent
     * @param string $docPart
     * @param mixed $pStyle
     *
     * @todo Footnote paragraph style
     */
    private function readRun(XMLReader $xmlReader, \DOMElement $domNode, &$parent, $docPart, $pStyle = null)
    {
        if (!in_array($domNode->nodeName, array('w:r', 'w:hyperlink'))) {
            return;
        }
        $fStyle = $this->readFontStyle($xmlReader, $domNode);

        // Link
        if ($domNode->nodeName == 'w:hyperlink') {
            $rId = $xmlReader->getAttribute('r:id', $domNode);
            $textContent = $xmlReader->getValue('w:r/w:t', $domNode);
            $target = $this->getMediaTarget($docPart, $rId);
            if (!is_null($target)) {
                $parent->addLink($target, $textContent, $fStyle, $pStyle);
            }
        } else {
            // Footnote
            if ($xmlReader->elementExists('w:footnoteReference', $domNode)) {
                $parent->addFootnote();

            // Endnote
            } elseif ($xmlReader->elementExists('w:endnoteReference', $domNode)) {
                $parent->addEndnote();

            // Image
            } elseif ($xmlReader->elementExists('w:pict', $domNode)) {
                $rId = $xmlReader->getAttribute('r:id', $domNode, 'w:pict/v:shape/v:imagedata');
                $target = $this->getMediaTarget($docPart, $rId);
                if (!is_null($target)) {
                    $textContent = "<Image: {$target}>";
                    $parent->addText($textContent, $fStyle, $pStyle);
                }

            // Object
            } elseif ($xmlReader->elementExists('w:object', $domNode)) {
                $rId = $xmlReader->getAttribute('r:id', $domNode, 'w:object/o:OLEObject');
                // $rIdIcon = $xmlReader->getAttribute('r:id', $domNode, 'w:object/v:shape/v:imagedata');
                $target = $this->getMediaTarget($docPart, $rId);
                if (!is_null($target)) {
                    $textContent = "<Object: {$target}>";
                    $parent->addText($textContent, $fStyle, $pStyle);
                }

            // TextRun
            } else {
                $textContent = $xmlReader->getValue('w:t', $domNode);
                $parent->addText($textContent, $fStyle, $pStyle);
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
     * Read w:pPr
     *
     * @return string|array|null
     */
    private function readParagraphStyle(XMLReader $xmlReader, \DOMElement $domNode)
    {
        $style = null;
        if ($xmlReader->elementExists('w:pPr', $domNode)) {
            if ($xmlReader->elementExists('w:pPr/w:pStyle', $domNode)) {
                $style = $xmlReader->getAttribute('w:val', $domNode, 'w:pPr/w:pStyle');
            } else {
                $style = array();
                $mapping = array(
                    'w:ind' => 'indent', 'w:spacing' => 'spacing',
                    'w:jc' => 'align', 'w:basedOn' => 'basedOn', 'w:next' => 'next',
                    'w:widowControl' => 'widowControl', 'w:keepNext' => 'keepNext',
                    'w:keepLines' => 'keepLines', 'w:pageBreakBefore' => 'pageBreakBefore',
                );

                $nodes = $xmlReader->getElements('w:pPr/*', $domNode);
                foreach ($nodes as $node) {
                    if (!array_key_exists($node->nodeName, $mapping)) {
                        continue;
                    }
                    $property = $mapping[$node->nodeName];
                    switch ($node->nodeName) {

                        case 'w:ind':
                            $style['indent'] = $xmlReader->getAttribute('w:left', $node);
                            $style['hanging'] = $xmlReader->getAttribute('w:hanging', $node);
                            break;

                        case 'w:spacing':
                            $style['spaceAfter'] = $xmlReader->getAttribute('w:after', $node);
                            $style['spaceBefore'] = $xmlReader->getAttribute('w:before', $node);
                            // Commented. Need to adjust the number when return value is null
                            // $style['spacing'] = $xmlReader->getAttribute('w:line', $node);
                            break;

                        case 'w:keepNext':
                        case 'w:keepLines':
                        case 'w:pageBreakBefore':
                            $style[$property] = true;
                            break;

                        case 'w:widowControl':
                            $style[$property] = false;
                            break;

                        case 'w:jc':
                        case 'w:basedOn':
                        case 'w:next':
                            $style[$property] = $xmlReader->getAttribute('w:val', $node);
                            break;
                    }
                }
            }
        }

        return $style;
    }

    /**
     * Read w:rPr
     *
     * @return string|array|null
     */
    private function readFontStyle(XMLReader $xmlReader, \DOMElement $domNode)
    {
        $style = null;
        // Hyperlink has an extra w:r child
        if ($domNode->nodeName == 'w:hyperlink') {
            $domNode = $xmlReader->getElement('w:r', $domNode);
        }
        if (is_null($domNode)) {
            return $style;
        }
        if ($xmlReader->elementExists('w:rPr', $domNode)) {
            if ($xmlReader->elementExists('w:rPr/w:rStyle', $domNode)) {
                $style = $xmlReader->getAttribute('w:val', $domNode, 'w:rPr/w:rStyle');
            } else {
                $style = array();
                $mapping = array(
                    'w:b' => 'bold', 'w:i' => 'italic', 'w:color' => 'color',
                    'w:strike' => 'strikethrough', 'w:u' => 'underline',
                    'w:highlight' => 'fgColor', 'w:sz' => 'size',
                    'w:rFonts' => 'name', 'w:vertAlign' => 'superScript',
                );

                $nodes = $xmlReader->getElements('w:rPr/*', $domNode);
                foreach ($nodes as $node) {
                    if (!array_key_exists($node->nodeName, $mapping)) {
                        continue;
                    }
                    $property = $mapping[$node->nodeName];
                    switch ($node->nodeName) {

                        case 'w:rFonts':
                            $style['name'] = $xmlReader->getAttribute('w:ascii', $node);
                            $style['hint'] = $xmlReader->getAttribute('w:hint', $node);
                            break;

                        case 'w:b':
                        case 'w:i':
                        case 'w:strike':
                            $style[$property] = true;
                            break;

                        case 'w:u':
                        case 'w:highlight':
                        case 'w:color':
                            $style[$property] = $xmlReader->getAttribute('w:val', $node);
                            break;

                        case 'w:sz':
                            $style[$property] = $xmlReader->getAttribute('w:val', $node) / 2;
                            break;

                        case 'w:vertAlign':
                            $style[$property] = $xmlReader->getAttribute('w:val', $node);
                            if ($style[$property] == 'superscript') {
                                $style['superScript'] = true;
                            } else {
                                $style['superScript'] = false;
                                $style['subScript'] = true;
                            }
                            break;
                    }
                }
            }
        }

        return $style;
    }
    /**
     * Read w:tblPr
     *
     * @return string|array|null
     * @todo Capture w:tblStylePr w:type="firstRow"
     */
    private function readTableStyle(XMLReader $xmlReader, \DOMElement $domNode)
    {
        $style = null;
        $margins = array('top', 'left', 'bottom', 'right');
        $borders = $margins + array('insideH', 'insideV');

        if ($xmlReader->elementExists('w:tblPr', $domNode)) {
            if ($xmlReader->elementExists('w:tblPr/w:tblStyle', $domNode)) {
                $style = $xmlReader->getAttribute('w:val', $domNode, 'w:tblPr/w:tblStyle');
            } else {
                $style = array();
                $mapping = array(
                    'w:tblCellMar' => 'cellMargin',
                    'w:tblBorders' => 'border',
                );

                $nodes = $xmlReader->getElements('w:tblPr/*', $domNode);
                foreach ($nodes as $node) {
                    if (!array_key_exists($node->nodeName, $mapping)) {
                        continue;
                    }
                    // $property = $mapping[$node->nodeName];
                    switch ($node->nodeName) {

                        case 'w:tblCellMar':
                            foreach ($margins as $side) {
                                $ucfSide = ucfirst($side);
                                $style["cellMargin$ucfSide"] = $xmlReader->getAttribute('w:w', $node, "w:$side");
                            }
                            break;

                        case 'w:tblBorders':
                            foreach ($borders as $side) {
                                $ucfSide = ucfirst($side);
                                $style["border{$ucfSide}Size"] = $xmlReader->getAttribute('w:sz', $node, "w:$side");
                                $style["border{$ucfSide}Color"] = $xmlReader->getAttribute('w:color', $node, "w:$side");
                            }
                            break;
                    }
                }
            }
        }

        return $style;
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

    /**
     * Get relationship array
     *
     * @param string $filename
     * @param string $xmlFile
     * @param string $targetPrefix
     * @return array
     */
    private function getRels($filename, $xmlFile, $targetPrefix = '')
    {
        $metaPrefix = 'http://schemas.openxmlformats.org/package/2006/relationships/metadata/';
        $officePrefix = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/';

        $rels = array();

        $xmlReader = new XMLReader();
        $xmlReader->getDomFromZip($filename, $xmlFile);
        $nodes = $xmlReader->getElements('*');
        foreach ($nodes as $node) {
            $rId = $xmlReader->getAttribute('Id', $node);
            $type = $xmlReader->getAttribute('Type', $node);
            $target = $xmlReader->getAttribute('Target', $node);

            // Remove URL prefixes from $type to make it easier to read
            $type = str_replace($metaPrefix, '', $type);
            $type = str_replace($officePrefix, '', $type);
            $docPart = str_replace('.xml', '', $target);

            // Do not add prefix to link source
            if (!in_array($type, array('hyperlink'))) {
                $target = $targetPrefix . $target;
            }

            // Push to return array
            $rels[$rId] = array('type' => $type, 'target' => $target, 'docPart' => $docPart);
        }
        ksort($rels);

        return $rels;
    }

    /**
     * Returns the target of image, object, or link as stored in ::readMainRels
     *
     * @param string $docPart
     * @param string $rId
     * @return string|null
     */
    private function getMediaTarget($docPart, $rId)
    {
        $target = null;
        if (array_key_exists($docPart, $this->rels)) {
            if (array_key_exists($rId, $this->rels[$docPart])) {
                $target = $this->rels[$docPart][$rId]['target'];
            }
        }

        return $target;
    }
}
