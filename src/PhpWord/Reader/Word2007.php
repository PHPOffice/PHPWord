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
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Shared\XMLReader;
use PhpOffice\PhpWord\Element\Section;

/**
 * Reader for Word2007
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
    private $partRels = array('document' => array(), 'footnotes' => array());

    /**
     * Current active part document|footnotes|headerx|footerx
     *
     * @var string
     */
    private $activePart = 'document';

    /**
     * Can the current ReaderInterface read the file?
     *
     * @param string $fileName
     * @return bool
     * @throws Exception
     */
    public function canRead($fileName)
    {
        // Check if file exists
        if (!file_exists($fileName)) {
            throw new Exception("Could not open {$fileName} for reading! File does not exist.");
        }

        $return = false;
        // Load file
        $zipClass = Settings::getZipClass();
        $zip = new $zipClass();
        if ($zip->open($fileName) === true) {
            // check if it is an OOXML archive
            $rels = simplexml_load_string($this->getFromZipArchive($zip, "_rels/.rels"));
            if ($rels !== false) {
                foreach ($rels->Relationship as $rel) {
                    switch ($rel["Type"]) {
                        case "http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument":
                            if (basename($rel["Target"]) == 'document.xml') {
                                $return = true;
                            }
                            break;

                    }
                }
            }
            $zip->close();
        }

        return $return;
    }

    /**
     * Get zip content
     *
     * @param mixed $archive
     * @param string $fileName
     * @param bool $removeNamespace
     * @return string
     */
    public function getFromZipArchive($archive, $fileName = '', $removeNamespace = false)
    {
        // Root-relative paths
        // if (strpos($fileName, '//') !== false) {
        //     $fileName = substr($fileName, strpos($fileName, '//') + 1);
        // }
        // $fileName = realpath($fileName);

        // Apache POI fixes
        $contents = $archive->getFromName($fileName);
        if ($contents === false) {
            $contents = $archive->getFromName(substr($fileName, 1));
        }

        // Remove namespaces from elements and attributes name
        if ($removeNamespace) {
            $contents = preg_replace('~(</?|\s)w:~is', '$1', $contents);
        }

        return $contents;
    }

    /**
     * Loads PhpWord from file
     *
     * @param string $fileName
     * @return PhpWord|null
     */
    public function load($fileName)
    {
        // Check if file exists and can be read
        if (!$this->canRead($fileName)) {
            return null;
        }

        // Initialisations
        $this->phpWord = new PhpWord();
        $zipClass = Settings::getZipClass();
        $zip = new $zipClass();
        $zip->open($fileName);

        //  Read document relationships
        $this->readPartRels($fileName, 'document');

        // Read properties and documents
        $rels = simplexml_load_string($this->getFromZipArchive($zip, "_rels/.rels"));
        foreach ($rels->Relationship as $rel) {
            switch ($rel["Type"]) {
                // Core properties
                case "http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties":
                    $xmlCore = simplexml_load_string($this->getFromZipArchive($zip, "{$rel['Target']}"));
                    if (is_object($xmlCore)) {
                        $xmlCore->registerXPathNamespace("dc", "http://purl.org/dc/elements/1.1/");
                        $xmlCore->registerXPathNamespace("dcterms", "http://purl.org/dc/terms/");
                        $xmlCore->registerXPathNamespace("cp", "http://schemas.openxmlformats.org/package/2006/metadata/core-properties");
                        $docProps = $this->phpWord->getDocumentProperties();
                        $docProps->setCreator((string)self::arrayItem($xmlCore->xpath("dc:creator")));
                        $docProps->setLastModifiedBy((string)self::arrayItem($xmlCore->xpath("cp:lastModifiedBy")));
                        $docProps->setCreated(strtotime(self::arrayItem($xmlCore->xpath("dcterms:created"))));
                        $docProps->setModified(strtotime(self::arrayItem($xmlCore->xpath("dcterms:modified"))));
                        $docProps->setTitle((string)self::arrayItem($xmlCore->xpath("dc:title")));
                        $docProps->setDescription((string)self::arrayItem($xmlCore->xpath("dc:description")));
                        $docProps->setSubject((string)self::arrayItem($xmlCore->xpath("dc:subject")));
                        $docProps->setKeywords((string)self::arrayItem($xmlCore->xpath("cp:keywords")));
                        $docProps->setCategory((string)self::arrayItem($xmlCore->xpath("cp:category")));
                    }
                    break;
                // Extended properties
                case "http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties":
                    $xmlCore = simplexml_load_string($this->getFromZipArchive($zip, "{$rel['Target']}"));
                    if (is_object($xmlCore)) {
                        $docProps = $this->phpWord->getDocumentProperties();
                        if (isset($xmlCore->Company)) {
                            $docProps->setCompany((string)$xmlCore->Company);
                        }
                        if (isset($xmlCore->Manager)) {
                            $docProps->setManager((string)$xmlCore->Manager);
                        }
                    }
                    break;
                // Custom properties
                case "http://schemas.openxmlformats.org/officeDocument/2006/relationships/custom-properties":
                    $xmlCore = simplexml_load_string($this->getFromZipArchive($zip, "{$rel['Target']}"));
                    if (is_object($xmlCore)) {
                        $docProps = $this->phpWord->getDocumentProperties();
                        foreach ($xmlCore as $xmlProperty) {
                            $cellDataOfficeAttributes = $xmlProperty->attributes();
                            if (isset($cellDataOfficeAttributes['name'])) {
                                $propertyName = (string)$cellDataOfficeAttributes['name'];
                                $cellDataOfficeChildren = $xmlProperty->children("http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes");
                                $attributeType = $cellDataOfficeChildren->getName();
                                $attributeValue = (string)$cellDataOfficeChildren->{$attributeType};
                                $attributeValue = DocumentProperties::convertProperty($attributeValue, $attributeType);
                                $attributeType = DocumentProperties::convertPropertyType($attributeType);
                                $docProps->setCustomProperty($propertyName, $attributeValue, $attributeType);
                            }
                        }
                    }
                    break;
            }
        }

        // Read document
        $this->readDocument($fileName, 'word/document.xml');

        // Read document relationships
        foreach ($this->partRels['document'] as $rId => $rel) {
            if ($rel['type'] == 'styles') {
                $this->readStyles($fileName, 'word/' . $rel['target']);
            }
        }
        $zip->close();

        return $this->phpWord;
    }

    /**
     * Read _rels/$partName.xml.rels
     *
     * @param string $fileName
     * @param string $partName document|footnotes|headerx|footerx
     */
    private function readPartRels($fileName, $partName)
    {
        $relPrefix = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships/';
        $xmlReader = new XMLReader();
        $response = $xmlReader->getDomFromZip($fileName, "word/_rels/{$partName}.xml.rels");
        if ($response) {
            $rels = $xmlReader->getElements('*');
            foreach ($rels as $rel) {
                $this->partRels[$partName][$rel->getAttribute('Id')] = array(
                    'type' => str_replace($relPrefix, '', $rel->getAttribute('Type')),
                    'target' => $rel->getAttribute('Target'),
                );
            }
        }
    }

    /**
     * Read styles.xml
     *
     * @param string $fileName
     * @param string $xmlFile
     */
    private function readStyles($fileName, $xmlFile)
    {
        $xmlReader = new XMLReader();
        $xmlReader->getDomFromZip($fileName, $xmlFile);

        $nodes = $xmlReader->getElements('w:style');
        if ($nodes->length > 0) {
            foreach ($nodes as $node) {
                $type = $xmlReader->getAttribute($node, 'w:type');
                $name = $xmlReader->getAttribute($node, 'w:styleId');
                if (is_null($name)) {
                    $name = $xmlReader->getAttribute('w:name', 'w:val', $node);
                }
                $default = ($xmlReader->getAttribute($node, 'w:default') == 1);
                if ($type == 'paragraph') {
                    $pStyle = $this->readWpPr($xmlReader, $node);
                    $fStyle = $this->readWrPr($xmlReader, $node);
                    if (empty($fStyle)) {
                        $this->phpWord->addParagraphStyle($name, $pStyle);
                    } else {
                        $this->phpWord->addFontStyle($name, $fStyle, $pStyle);
                    }
                } elseif ($type == 'character') {
                    $fStyle = $this->readWrPr($xmlReader, $node);
                    if (!empty($fStyle)) {
                        $this->phpWord->addFontStyle($name, $fStyle);
                    }
                } elseif ($type == 'table') {
                    $tStyle = $this->readWtblPr($xmlReader, $node);
                    if (!empty($tStyle)) {
                        $this->phpWord->addTableStyle($name, $tStyle);
                    }
                }
            }
        }
    }

    /**
     * Read document.xml
     *
     * @param string $fileName
     * @param string $xmlFile
     */
    private function readDocument($fileName, $xmlFile)
    {
        $xmlReader = new XMLReader();
        $xmlReader->getDomFromZip($fileName, $xmlFile);

        $nodes = $xmlReader->getElements('w:body/*');
        if ($nodes->length > 0) {
            $section = $this->phpWord->addSection();
            foreach ($nodes as $node) {
                if ($node->nodeName == 'w:p') { // Paragraph
                    if ($xmlReader->getAttribute('w:r/w:br', 'w:type', $node) == 'page') {
                        $section->addPageBreak(); // PageBreak
                    } else {
                        $this->readWp($xmlReader, $node, $section);
                    }
                    // Section properties
                    if ($xmlReader->elementExists('w:pPr/w:sectPr', $node)) {
                        $settingsNode = $xmlReader->getElement('w:pPr/w:sectPr', $node);
                        $settings = $this->readWsectPr($xmlReader, $settingsNode);
                        $section->setSettings($settings);
                        $this->readHeaderFooter($fileName, $settings, $section);
                        $section = $this->phpWord->addSection();
                    }
                } elseif ($node->nodeName == 'w:tbl') { // Table
                    $this->readWtbl($xmlReader, $node, $section);
                } elseif ($node->nodeName == 'w:sectPr') { // Last section
                    $settings = $this->readWsectPr($xmlReader, $node);
                    $section->setSettings($settings);
                    $this->readHeaderFooter($fileName, $settings, $section);
                }
            }
        }
    }

    /**
     * Read header footer
     *
     * @param string $fileName
     * @param array $settings
     * @param Section $section
     */
    private function readHeaderFooter($fileName, $settings, &$section)
    {
        if (is_array($settings) && array_key_exists('headerFooter', $settings)) {
            foreach ($settings['headerFooter'] as $rId => $headerFooter) {
                if (array_key_exists($rId, $this->partRels['document'])) {
                    $target = $this->partRels['document'][$rId]['target'];
                    $xmlFile = 'word/' . $target;
                    $method = 'add' . $headerFooter['method'];
                    $type = $headerFooter['type'];
                    $object = $section->$method($type);

                    $this->activePart = str_replace('.xml', '', $target);
                    $this->readPartRels($fileName, $this->activePart);

                    $xmlReader = new XMLReader();
                    $xmlReader->getDomFromZip($fileName, $xmlFile);
                    $nodes = $xmlReader->getElements('*');
                    if ($nodes->length > 0) {
                        foreach ($nodes as $node) {
                            if ($node->nodeName == 'w:p') { // Paragraph
                                $this->readWp($xmlReader, $node, $object);
                            } elseif ($node->nodeName == 'w:tbl') { // Table
                                $this->readWtbl($xmlReader, $node, $object);
                            }
                        }
                    }
                }
            }
        }
        $this->activePart = 'document';
    }

    /**
     * Read w:p
     *
     * @param mixed $container
     * @todo Get font style for preserve text
     */
    private function readWp(XMLReader $xmlReader, \DOMNode $domNode, &$container)
    {
        // Paragraph style
        $pStyle = null;
        if ($xmlReader->elementExists('w:pPr', $domNode)) {
            $pStyle = $this->readWpPr($xmlReader, $domNode);
        }

        // Content
        if ($xmlReader->elementExists('w:r/w:instrText', $domNode)) { // Preserve text
            $textContent = '';
            $fStyle = $this->readWrPr($xmlReader, $domNode);
            $nodes = $xmlReader->getElements('w:r', $domNode);
            foreach ($nodes as $node) {
                $instrText = $xmlReader->getValue('w:instrText', $node);
                if (!is_null($instrText)) {
                    $textContent .= '{' . $instrText . '}';
                } else {
                    $textContent .= $xmlReader->getValue('w:t', $node);
                }
            }
            $container->addPreserveText($textContent, $fStyle, $pStyle);
        } else { // Text and TextRun
            $runCount = $xmlReader->countElements('w:r', $domNode);
            $linkCount = $xmlReader->countElements('w:hyperlink', $domNode);
            $runLinkCount = $runCount + $linkCount;
            if ($runLinkCount == 0) {
                $container->addTextBreak(null, $pStyle);
            } else {
                if ($runLinkCount > 1) {
                    $textContainer = &$container->addTextRun($pStyle);
                    $pStyle = null;
                } else {
                    $textContainer = &$container;
                }
                $nodes = $xmlReader->getElements('*', $domNode);
                foreach ($nodes as $node) {
                    $this->readWr($xmlReader, $node, $textContainer, $pStyle);
                }
            }
        }
    }

    /**
     * Read w:r
     *
     * @param mixed $container
     * @param mixed $pStyle
     */
    private function readWr(XMLReader $xmlReader, \DOMNode $domNode, &$container, $pStyle = null)
    {
        if (!in_array($domNode->nodeName, array('w:r', 'w:hyperlink'))) {
            return;
        }
        $fStyle = $this->readWrPr($xmlReader, $domNode);
        if ($domNode->nodeName == 'w:hyperlink') {
            $rId = $xmlReader->getAttribute($domNode, 'r:id');
            $textContent = $xmlReader->getValue('w:r/w:t', $domNode);
            if (array_key_exists($this->activePart, $this->partRels)) {
                if (array_key_exists($rId, $this->partRels[$this->activePart])) {
                    $linkSource = $this->partRels[$this->activePart][$rId]['target'];
                }
            }
            $container->addLink($linkSource, $textContent, $fStyle, $pStyle);
        } else {
            $textContent = $xmlReader->getValue('w:t', $domNode);
            $container->addText($textContent, $fStyle, $pStyle);
        }
    }

    /**
     * Read w:tbl
     *
     * @param mixed $container
     */
    private function readWtbl(XMLReader $xmlReader, \DOMNode $domNode, &$container)
    {
        // Table style
        $tblStyle = null;
        if ($xmlReader->elementExists('w:tblPr', $domNode)) {
            $tblStyle = $this->readWtblPr($xmlReader, $domNode);
        }

        $table = $container->addTable($tblStyle);
        $tblNodes = $xmlReader->getElements('*', $domNode);
        foreach ($tblNodes as $tblNode) {
            $tblNodeName = $tblNode->nodeName;
            if ($tblNode->nodeName == 'w:tblGrid') { // Column
                // @todo Do something with table columns
            } elseif ($tblNode->nodeName == 'w:tr') { // Row
                $rowHeight = $xmlReader->getAttribute('w:trPr/w:trHeight', 'w:val', $tblNode);
                $rowHRule = $xmlReader->getAttribute('w:trPr/w:trHeight', 'w:hRule', $tblNode);
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
                        $cellWidth = $xmlReader->getAttribute('w:tcPr/w:tcW', 'w:w', $rowNode);
                        $cellStyle = null;
                        if ($xmlReader->elementExists('w:tcPr', $rowNode)) {
                            $cellStyle = $this->readWtcPr(
                                $xmlReader,
                                $xmlReader->getElement('w:tcPr', $rowNode)
                            );
                        }

                        $cell = $row->addCell($cellWidth, $cellStyle);
                        $cellNodes = $xmlReader->getElements('*', $rowNode);
                        foreach ($cellNodes as $cellNode) {
                            if ($cellNode->nodeName == 'w:p') { // Paragraph
                                $this->readWp($xmlReader, $cellNode, $cell);
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
    private function readWsectPr(XMLReader $xmlReader, \DOMNode $domNode)
    {
        $ret = null;
        $mapping = array(
            'w:type' => 'breakType', 'w:pgSz' => 'pageSize',
            'w:pgMar' => 'pageMargin', 'w:cols' => 'columns',
            'w:headerReference' => 'header', 'w:footerReference' => 'footer',
        );
        $nodes = $xmlReader->getElements('*', $domNode);
        foreach ($nodes as $node) {
            $nodeName = $node->nodeName;
            if (!array_key_exists($nodeName, $mapping)) {
                continue;
            }
            $retKey = $mapping[$nodeName];
            if ($nodeName == 'w:type') {
                $ret['breakType'] = $xmlReader->getAttribute($node, 'w:val');
            } elseif ($nodeName == 'w:pgSz') {
                $ret['pageSizeW'] = $xmlReader->getAttribute($node, 'w:w');
                $ret['pageSizeH'] = $xmlReader->getAttribute($node, 'w:h');
                $ret['orientation'] = $xmlReader->getAttribute($node, 'w:orient');
            } elseif ($nodeName == 'w:pgMar') {
                $ret['topMargin'] = $xmlReader->getAttribute($node, 'w:top');
                $ret['leftMargin'] = $xmlReader->getAttribute($node, 'w:left');
                $ret['bottomMargin'] = $xmlReader->getAttribute($node, 'w:bottom');
                $ret['rightMargin'] = $xmlReader->getAttribute($node, 'w:right');
                $ret['headerHeight'] = $xmlReader->getAttribute($node, 'w:header');
                $ret['footerHeight'] = $xmlReader->getAttribute($node, 'w:footer');
                $ret['gutter'] = $xmlReader->getAttribute($node, 'w:gutter');
            } elseif ($nodeName == 'w:cols') {
                $ret['colsNum'] = $xmlReader->getAttribute($node, 'w:num');
                $ret['colsSpace'] = $xmlReader->getAttribute($node, 'w:space');
            } elseif (in_array($nodeName, array('w:headerReference', 'w:footerReference'))) {
                $id = $xmlReader->getAttribute($node, 'r:id');
                $ret['headerFooter'][$id] = array(
                    'method' => $retKey,
                    'type' => $xmlReader->getAttribute($node, 'w:type'),
                );
            }
        }

        return $ret;
    }

    /**
     * Read w:pPr
     *
     * @return string|array|null
     */
    private function readWpPr(XMLReader $xmlReader, \DOMNode $domNode)
    {
        $ret = null;
        if ($xmlReader->elementExists('w:pPr', $domNode)) {
            if ($xmlReader->elementExists('w:pPr/w:pStyle', $domNode)) {
                $ret = $xmlReader->getAttribute('w:pPr/w:pStyle', 'w:val', $domNode);
            } else {
                $ret = array();
                $mapping = array(
                    'w:jc' => 'align', 'w:ind' => 'indent', 'w:spacing' => 'spacing',
                    'w:basedOn' => 'basedOn', 'w:next' => 'next',
                    'w:widowControl' => 'widowControl', 'w:keepNext' => 'keepNext',
                    'w:keepLines' => 'keepLines', 'w:pageBreakBefore' => 'pageBreakBefore',
                );
                $nodes = $xmlReader->getElements('w:pPr/*', $domNode);
                foreach ($nodes as $node) {
                    $nodeName = $node->nodeName;
                    if (!array_key_exists($nodeName, $mapping)) {
                        continue;
                    }
                    $retKey = $mapping[$nodeName];
                    if ($nodeName == 'w:ind') {
                        $ret['indent'] = $xmlReader->getAttribute($node, 'w:left');
                        $ret['hanging'] = $xmlReader->getAttribute($node, 'w:hanging');
                    } elseif ($nodeName == 'w:spacing') {
                        $ret['spaceAfter'] = $xmlReader->getAttribute($node, 'w:after');
                        $ret['spaceBefore'] = $xmlReader->getAttribute($node, 'w:before');
                        $ret['line'] = $xmlReader->getAttribute($node, 'w:line');
                    } elseif (in_array($nodeName, array('w:keepNext', 'w:keepLines', 'w:pageBreakBefore'))) {
                        $ret[$retKey] = true;
                    } elseif (in_array($nodeName, array('w:widowControl'))) {
                        $ret[$retKey] = false;
                    } elseif (in_array($nodeName, array('w:jc', 'w:basedOn', 'w:next'))) {
                        $ret[$retKey] = $xmlReader->getAttribute($node, 'w:val');
                    }
                }
            }
        }

        return $ret;
    }

    /**
     * Read w:rPr
     *
     * @return string|array|null
     */
    private function readWrPr(XMLReader $xmlReader, \DOMNode $domNode)
    {
        $ret = null;
        if ($xmlReader->elementExists('w:rPr', $domNode)) {
            if ($xmlReader->elementExists('w:rPr/w:rStyle', $domNode)) {
                $ret = $xmlReader->getAttribute('w:rPr/w:rStyle', 'w:val', $domNode);
            } else {
                $ret = array();
                $mapping = array(
                    'w:b' => 'bold', 'w:i' => 'italic', 'w:color' => 'color',
                    'w:strike' => 'strikethrough', 'w:u' => 'underline',
                    'w:highlight' => 'fgColor', 'w:sz' => 'size',
                    'w:rFonts' => 'name', 'w:vertAlign' => 'superScript',
                );
                $nodes = $xmlReader->getElements('w:rPr/*', $domNode);
                foreach ($nodes as $node) {
                    $nodeName = $node->nodeName;
                    if (!array_key_exists($nodeName, $mapping)) {
                        continue;
                    }
                    $retKey = $mapping[$nodeName];
                    if ($nodeName == 'w:rFonts') {
                        $ret['name'] = $xmlReader->getAttribute($node, 'w:ascii');
                        $ret['hint'] = $xmlReader->getAttribute($node, 'w:hint');
                    } elseif (in_array($nodeName, array('w:b', 'w:i', 'w:strike'))) {
                        $ret[$retKey] = true;
                    } elseif (in_array($nodeName, array('w:u', 'w:highlight', 'w:color'))) {
                        $ret[$retKey] = $xmlReader->getAttribute($node, 'w:val');
                    } elseif ($nodeName == 'w:sz') {
                        $ret[$retKey] = $xmlReader->getAttribute($node, 'w:val') / 2;
                    } elseif ($nodeName == 'w:vertAlign') {
                        $ret[$retKey] = $xmlReader->getAttribute($node, 'w:val');
                        if ($ret[$retKey] == 'superscript') {
                            $ret['superScript'] = true;
                        } else {
                            $ret['superScript'] = false;
                            $ret['subScript'] = true;
                        }
                    }
                }
            }
        }

        return $ret;
    }
    /**
     * Read w:tblPr
     *
     * @return string|array|null
     * @todo Capture w:tblStylePr w:type="firstRow"
     */
    private function readWtblPr(XMLReader $xmlReader, \DOMNode $domNode)
    {
        $ret = null;
        $margins = array('top', 'left', 'bottom', 'right');
        $borders = $margins + array('insideH', 'insideV');

        if ($xmlReader->elementExists('w:tblPr', $domNode)) {
            if ($xmlReader->elementExists('w:tblPr/w:tblStyle', $domNode)) {
                $ret = $xmlReader->getAttribute('w:tblPr/w:tblStyle', 'w:val', $domNode);
            } else {
                $ret = array();
                $mapping = array(
                    'w:tblCellMar' => 'cellMargin', 'w:tblBorders' => 'border',
                );
                $nodes = $xmlReader->getElements('w:tblPr/*', $domNode);
                foreach ($nodes as $node) {
                    $nodeName = $node->nodeName;
                    if (!array_key_exists($nodeName, $mapping)) {
                        continue;
                    }
                    $retKey = $mapping[$nodeName];
                    if ($nodeName == 'w:tblCellMar') {
                        foreach ($margins as $side) {
                            $ucfirstSide = ucfirst($side);
                            $ret["cellMargin$ucfirstSide"] = $xmlReader->getAttribute("w:$side", 'w:w', $node);
                        }
                    } elseif ($nodeName == 'w:tblBorders') {
                        foreach ($borders as $side) {
                            $ucfirstSide = ucfirst($side);
                            $ret["border{$ucfirstSide}Size"] = $xmlReader->getAttribute("w:$side", 'w:sz', $node);
                            $ret["border{$ucfirstSide}Color"] = $xmlReader->getAttribute("w:$side", 'w:color', $node);
                        }
                    }
                }
            }
        }

        return $ret;
    }

    /**
     * Read w:tcPr
     *
     * @return array|null
     */
    private function readWtcPr(XMLReader $xmlReader, \DOMNode $domNode)
    {
        $ret = null;
        $mapping = array(
            'w:shd' => 'bgColor',
            'w:vAlign' => 'valign', 'w:textDirection' => 'textDirection',
            'w:gridSpan' => 'gridSpan', 'w:vMerge' => 'vMerge',
        );
        $nodes = $xmlReader->getElements('*', $domNode);
        foreach ($nodes as $node) {
            $nodeName = $node->nodeName;
            if (!array_key_exists($nodeName, $mapping)) {
                continue;
            }
            $retKey = $mapping[$nodeName];
            if ($nodeName == 'w:shd') {
                $ret['bgColor'] = $xmlReader->getAttribute($node, 'w:fill');
            } else {
                $ret[$retKey] = $xmlReader->getAttribute($node, 'w:val');
            }
        }

        return $ret;
    }

    /**
     * Return item of array
     *
     * @param array $array
     * @param integer $key
     * @return string
     */
    private static function arrayItem($array, $key = 0)
    {
        return (isset($array[$key]) ? $array[$key] : null);
    }
}
