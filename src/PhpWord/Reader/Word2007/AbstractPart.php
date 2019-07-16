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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Reader\Word2007;

use PhpOffice\Common\XMLReader;
use PhpOffice\PhpWord\ComplexType\TblWidth as TblWidthComplexType;
use PhpOffice\PhpWord\Element\AbstractContainer;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\TrackChange;
use PhpOffice\PhpWord\PhpWord;

/**
 * Abstract part reader
 *
 * This class is inherited by ODText reader
 *
 * @since 0.10.0
 */
abstract class AbstractPart
{
    /**
     * Conversion method
     *
     * @const int
     */
    const READ_VALUE = 'attributeValue';            // Read attribute value
    const READ_EQUAL = 'attributeEquals';           // Read `true` when attribute value equals specified value
    const READ_TRUE = 'attributeTrue';              // Read `true` when element exists
    const READ_FALSE = 'attributeFalse';            // Read `false` when element exists
    const READ_SIZE = 'attributeMultiplyByTwo';     // Read special attribute value for Font::$size

    /**
     * Document file
     *
     * @var string
     */
    protected $docFile;

    /**
     * XML file
     *
     * @var string
     */
    protected $xmlFile;

    /**
     * Part relationships
     *
     * @var array
     */
    protected $rels = array();

    /**
     * Read part.
     */
    abstract public function read(PhpWord $phpWord);

    /**
     * Create new instance
     *
     * @param string $docFile
     * @param string $xmlFile
     */
    public function __construct($docFile, $xmlFile)
    {
        $this->docFile = $docFile;
        $this->xmlFile = $xmlFile;
    }

    /**
     * Set relationships.
     *
     * @param array $value
     */
    public function setRels($value)
    {
        $this->rels = $value;
    }

    /**
     * Read w:p.
     *
     * @param \PhpOffice\Common\XMLReader $xmlReader
     * @param \DOMElement $domNode
     * @param \PhpOffice\PhpWord\Element\AbstractContainer $parent
     * @param string $docPart
     *
     * @todo Get font style for preserve text
     */
    protected function readParagraph(XMLReader $xmlReader, \DOMElement $domNode, $parent, $docPart = 'document')
    {
        // Paragraph style
        $paragraphStyle = null;
        $headingDepth = null;
        if ($xmlReader->elementExists('w:pPr', $domNode)) {
            $paragraphStyle = $this->readParagraphStyle($xmlReader, $domNode);
            $headingDepth = $this->getHeadingDepth($paragraphStyle);
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
                    if ('begin' == $fldCharType) {
                        $ignoreText = true;
                    } elseif ('end' == $fldCharType) {
                        $ignoreText = false;
                    }
                }
                if (!is_null($instrText)) {
                    $textContent .= '{' . $instrText . '}';
                } else {
                    if (false === $ignoreText) {
                        $textContent .= $xmlReader->getValue('w:t', $node);
                    }
                }
            }
            $parent->addPreserveText(htmlspecialchars($textContent, ENT_QUOTES, 'UTF-8'), $fontStyle, $paragraphStyle);
        } elseif ($xmlReader->elementExists('w:pPr/w:numPr', $domNode)) {
            // List item
            $numId = $xmlReader->getAttribute('w:val', $domNode, 'w:pPr/w:numPr/w:numId');
            $levelId = $xmlReader->getAttribute('w:val', $domNode, 'w:pPr/w:numPr/w:ilvl');
            $nodes = $xmlReader->getElements('*', $domNode);

            $listItemRun = $parent->addListItemRun($levelId, "PHPWordList{$numId}", $paragraphStyle);

            foreach ($nodes as $node) {
                $this->readRun($xmlReader, $node, $listItemRun, $docPart, $paragraphStyle);
            }
        } elseif ($headingDepth !== null) {
            // Heading or Title
            $textContent = null;
            $nodes = $xmlReader->getElements('w:r', $domNode);
            if ($nodes->length === 1) {
                $textContent = htmlspecialchars($xmlReader->getValue('w:t', $nodes->item(0)), ENT_QUOTES, 'UTF-8');
            } else {
                $textContent = new TextRun($paragraphStyle);
                foreach ($nodes as $node) {
                    $this->readRun($xmlReader, $node, $textContent, $docPart, $paragraphStyle);
                }
            }
            $parent->addTitle($textContent, $headingDepth);
        } else {
            // Text and TextRun
            $textRunContainers = $xmlReader->countElements('w:r|w:ins|w:del|w:hyperlink|w:smartTag', $domNode);
            if (0 === $textRunContainers) {
                $parent->addTextBreak(null, $paragraphStyle);
            } else {
                $nodes = $xmlReader->getElements('*', $domNode);
                $paragraph = $parent->addTextRun($paragraphStyle);
                foreach ($nodes as $node) {
                    $this->readRun($xmlReader, $node, $paragraph, $docPart, $paragraphStyle);
                }
            }
        }
    }

    /**
     * Returns the depth of the Heading, returns 0 for a Title
     *
     * @param array $paragraphStyle
     * @return number|null
     */
    private function getHeadingDepth(array $paragraphStyle = null)
    {
        if (is_array($paragraphStyle) && isset($paragraphStyle['styleName'])) {
            if ('Title' === $paragraphStyle['styleName']) {
                return 0;
            }

            $headingMatches = array();
            preg_match('/Heading(\d)/', $paragraphStyle['styleName'], $headingMatches);
            if (!empty($headingMatches)) {
                return $headingMatches[1];
            }
        }

        return null;
    }

    /**
     * Read w:r.
     *
     * @param \PhpOffice\Common\XMLReader $xmlReader
     * @param \DOMElement $domNode
     * @param \PhpOffice\PhpWord\Element\AbstractContainer $parent
     * @param string $docPart
     * @param mixed $paragraphStyle
     *
     * @todo Footnote paragraph style
     */
    protected function readRun(XMLReader $xmlReader, \DOMElement $domNode, $parent, $docPart, $paragraphStyle = null)
    {
        if (in_array($domNode->nodeName, array('w:ins', 'w:del', 'w:smartTag', 'w:hyperlink'))) {
            $nodes = $xmlReader->getElements('*', $domNode);
            foreach ($nodes as $node) {
                $this->readRun($xmlReader, $node, $parent, $docPart, $paragraphStyle);
            }
        } elseif ($domNode->nodeName == 'w:r') {
            $fontStyle = $this->readFontStyle($xmlReader, $domNode);
            $nodes = $xmlReader->getElements('*', $domNode);
            foreach ($nodes as $node) {
                $this->readRunChild($xmlReader, $node, $parent, $docPart, $paragraphStyle, $fontStyle);
            }
        }
    }

    /**
     * Parses nodes under w:r
     *
     * @param XMLReader $xmlReader
     * @param \DOMElement $node
     * @param AbstractContainer $parent
     * @param string $docPart
     * @param mixed $paragraphStyle
     * @param mixed $fontStyle
     */
    protected function readRunChild(XMLReader $xmlReader, \DOMElement $node, AbstractContainer $parent, $docPart, $paragraphStyle = null, $fontStyle = null)
    {
        $runParent = $node->parentNode->parentNode;
        if ($node->nodeName == 'w:footnoteReference') {
            // Footnote
            $wId = $xmlReader->getAttribute('w:id', $node);
            $footnote = $parent->addFootnote();
            $footnote->setRelationId($wId);
        } elseif ($node->nodeName == 'w:endnoteReference') {
            // Endnote
            $wId = $xmlReader->getAttribute('w:id', $node);
            $endnote = $parent->addEndnote();
            $endnote->setRelationId($wId);
        } elseif ($node->nodeName == 'w:pict') {
            // Image
            $rId = $xmlReader->getAttribute('r:id', $node, 'v:shape/v:imagedata');
            $target = $this->getMediaTarget($docPart, $rId);
            if (!is_null($target)) {
                if ('External' == $this->getTargetMode($docPart, $rId)) {
                    $imageSource = $target;
                } else {
                    $imageSource = "zip://{$this->docFile}#{$target}";
                }
                $parent->addImage($imageSource);
            }
        } elseif ($node->nodeName == 'w:drawing') {
            // Office 2011 Image
            $xmlReader->registerNamespace('wp', 'http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing');
            $xmlReader->registerNamespace('r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');
            $xmlReader->registerNamespace('pic', 'http://schemas.openxmlformats.org/drawingml/2006/picture');
            $xmlReader->registerNamespace('a', 'http://schemas.openxmlformats.org/drawingml/2006/main');

            $name = $xmlReader->getAttribute('name', $node, 'wp:inline/a:graphic/a:graphicData/pic:pic/pic:nvPicPr/pic:cNvPr');
            $embedId = $xmlReader->getAttribute('r:embed', $node, 'wp:inline/a:graphic/a:graphicData/pic:pic/pic:blipFill/a:blip');
            $target = $this->getMediaTarget($docPart, $embedId);
            if (!is_null($target)) {
                $imageSource = "zip://{$this->docFile}#{$target}";
                $parent->addImage($imageSource, null, false, $name);
            }
        } elseif ($node->nodeName == 'w:object') {
            // Object
            $rId = $xmlReader->getAttribute('r:id', $node, 'o:OLEObject');
            // $rIdIcon = $xmlReader->getAttribute('r:id', $domNode, 'w:object/v:shape/v:imagedata');
            $target = $this->getMediaTarget($docPart, $rId);
            if (!is_null($target)) {
                $textContent = "&lt;Object: {$target}>";
                $parent->addText($textContent, $fontStyle, $paragraphStyle);
            }
        } elseif ($node->nodeName == 'w:br') {
            $parent->addTextBreak();
        } elseif ($node->nodeName == 'w:tab') {
            $parent->addText("\t");
        } elseif ($node->nodeName == 'w:t' || $node->nodeName == 'w:delText') {
            // TextRun
            $textContent = htmlspecialchars($xmlReader->getValue('.', $node), ENT_QUOTES, 'UTF-8');

            if ($runParent->nodeName == 'w:hyperlink') {
                $rId = $xmlReader->getAttribute('r:id', $runParent);
                $target = $this->getMediaTarget($docPart, $rId);
                if (!is_null($target)) {
                    $parent->addLink($target, $textContent, $fontStyle, $paragraphStyle);
                } else {
                    $parent->addText($textContent, $fontStyle, $paragraphStyle);
                }
            } else {
                /** @var AbstractElement $element */
                $element = $parent->addText($textContent, $fontStyle, $paragraphStyle);
                if (in_array($runParent->nodeName, array('w:ins', 'w:del'))) {
                    $type = ($runParent->nodeName == 'w:del') ? TrackChange::DELETED : TrackChange::INSERTED;
                    $author = $runParent->getAttribute('w:author');
                    $date = \DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $runParent->getAttribute('w:date'));
                    $element->setChangeInfo($type, $author, $date);
                }
            }
        }
    }

    /**
     * Read w:tbl.
     *
     * @param \PhpOffice\Common\XMLReader $xmlReader
     * @param \DOMElement $domNode
     * @param mixed $parent
     * @param string $docPart
     */
    protected function readTable(XMLReader $xmlReader, \DOMElement $domNode, $parent, $docPart = 'document')
    {
        // Table style
        $tblStyle = null;
        if ($xmlReader->elementExists('w:tblPr', $domNode)) {
            $tblStyle = $this->readTableStyle($xmlReader, $domNode);
        }

        /** @var \PhpOffice\PhpWord\Element\Table $table Type hint */
        $table = $parent->addTable($tblStyle);
        $tblNodes = $xmlReader->getElements('*', $domNode);
        foreach ($tblNodes as $tblNode) {
            if ('w:tblGrid' == $tblNode->nodeName) { // Column
                // @todo Do something with table columns
            } elseif ('w:tr' == $tblNode->nodeName) { // Row
                $rowHeight = $xmlReader->getAttribute('w:val', $tblNode, 'w:trPr/w:trHeight');
                $rowHRule = $xmlReader->getAttribute('w:hRule', $tblNode, 'w:trPr/w:trHeight');
                $rowHRule = $rowHRule == 'exact';
                $rowStyle = array(
                    'tblHeader'   => $xmlReader->elementExists('w:trPr/w:tblHeader', $tblNode),
                    'cantSplit'   => $xmlReader->elementExists('w:trPr/w:cantSplit', $tblNode),
                    'exactHeight' => $rowHRule,
                );

                $row = $table->addRow($rowHeight, $rowStyle);
                $rowNodes = $xmlReader->getElements('*', $tblNode);
                foreach ($rowNodes as $rowNode) {
                    if ('w:trPr' == $rowNode->nodeName) { // Row style
                        // @todo Do something with row style
                    } elseif ('w:tc' == $rowNode->nodeName) { // Cell
                        $cellWidth = $xmlReader->getAttribute('w:w', $rowNode, 'w:tcPr/w:tcW');
                        $cellStyle = null;
                        $cellStyleNode = $xmlReader->getElement('w:tcPr', $rowNode);
                        if (!is_null($cellStyleNode)) {
                            $cellStyle = $this->readCellStyle($xmlReader, $cellStyleNode);
                        }

                        $cell = $row->addCell($cellWidth, $cellStyle);
                        $cellNodes = $xmlReader->getElements('*', $rowNode);
                        foreach ($cellNodes as $cellNode) {
                            if ('w:p' == $cellNode->nodeName) { // Paragraph
                                $this->readParagraph($xmlReader, $cellNode, $cell, $docPart);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Read w:pPr.
     *
     * @param \PhpOffice\Common\XMLReader $xmlReader
     * @param \DOMElement $domNode
     * @return array|null
     */
    protected function readParagraphStyle(XMLReader $xmlReader, \DOMElement $domNode)
    {
        if (!$xmlReader->elementExists('w:pPr', $domNode)) {
            return null;
        }

        $styleNode = $xmlReader->getElement('w:pPr', $domNode);
        $styleDefs = array(
            'styleName'           => array(self::READ_VALUE, array('w:pStyle', 'w:name')),
            'alignment'           => array(self::READ_VALUE, 'w:jc'),
            'basedOn'             => array(self::READ_VALUE, 'w:basedOn'),
            'next'                => array(self::READ_VALUE, 'w:next'),
            'indent'              => array(self::READ_VALUE, 'w:ind', 'w:left'),
            'hanging'             => array(self::READ_VALUE, 'w:ind', 'w:hanging'),
            'spaceAfter'          => array(self::READ_VALUE, 'w:spacing', 'w:after'),
            'spaceBefore'         => array(self::READ_VALUE, 'w:spacing', 'w:before'),
            'widowControl'        => array(self::READ_FALSE, 'w:widowControl'),
            'keepNext'            => array(self::READ_TRUE,  'w:keepNext'),
            'keepLines'           => array(self::READ_TRUE,  'w:keepLines'),
            'pageBreakBefore'     => array(self::READ_TRUE,  'w:pageBreakBefore'),
            'contextualSpacing'   => array(self::READ_TRUE,  'w:contextualSpacing'),
            'bidi'                => array(self::READ_TRUE,  'w:bidi'),
            'suppressAutoHyphens' => array(self::READ_TRUE,  'w:suppressAutoHyphens'),
        );

        return $this->readStyleDefs($xmlReader, $styleNode, $styleDefs);
    }

    /**
     * Read w:rPr
     *
     * @param \PhpOffice\Common\XMLReader $xmlReader
     * @param \DOMElement $domNode
     * @return array|null
     */
    protected function readFontStyle(XMLReader $xmlReader, \DOMElement $domNode)
    {
        if (is_null($domNode)) {
            return null;
        }
        // Hyperlink has an extra w:r child
        if ('w:hyperlink' == $domNode->nodeName) {
            $domNode = $xmlReader->getElement('w:r', $domNode);
        }
        if (!$xmlReader->elementExists('w:rPr', $domNode)) {
            return null;
        }

        $styleNode = $xmlReader->getElement('w:rPr', $domNode);
        $styleDefs = array(
            'styleName'           => array(self::READ_VALUE, 'w:rStyle'),
            'name'                => array(self::READ_VALUE, 'w:rFonts', array('w:ascii', 'w:hAnsi', 'w:eastAsia', 'w:cs')),
            'hint'                => array(self::READ_VALUE, 'w:rFonts', 'w:hint'),
            'size'                => array(self::READ_SIZE,  array('w:sz', 'w:szCs')),
            'color'               => array(self::READ_VALUE, 'w:color'),
            'underline'           => array(self::READ_VALUE, 'w:u'),
            'bold'                => array(self::READ_TRUE,  'w:b'),
            'italic'              => array(self::READ_TRUE,  'w:i'),
            'strikethrough'       => array(self::READ_TRUE,  'w:strike'),
            'doubleStrikethrough' => array(self::READ_TRUE,  'w:dstrike'),
            'smallCaps'           => array(self::READ_TRUE,  'w:smallCaps'),
            'allCaps'             => array(self::READ_TRUE,  'w:caps'),
            'superScript'         => array(self::READ_EQUAL, 'w:vertAlign', 'w:val', 'superscript'),
            'subScript'           => array(self::READ_EQUAL, 'w:vertAlign', 'w:val', 'subscript'),
            'fgColor'             => array(self::READ_VALUE, 'w:highlight'),
            'rtl'                 => array(self::READ_TRUE,  'w:rtl'),
            'lang'                => array(self::READ_VALUE, 'w:lang'),
            'position'            => array(self::READ_VALUE, 'w:position'),
            'hidden'              => array(self::READ_TRUE,  'w:vanish'),
        );

        return $this->readStyleDefs($xmlReader, $styleNode, $styleDefs);
    }

    /**
     * Read w:tblPr
     *
     * @param \PhpOffice\Common\XMLReader $xmlReader
     * @param \DOMElement $domNode
     * @return string|array|null
     * @todo Capture w:tblStylePr w:type="firstRow"
     */
    protected function readTableStyle(XMLReader $xmlReader, \DOMElement $domNode)
    {
        $style = null;
        $margins = array('top', 'left', 'bottom', 'right');
        $borders = array_merge($margins, array('insideH', 'insideV'));

        if ($xmlReader->elementExists('w:tblPr', $domNode)) {
            if ($xmlReader->elementExists('w:tblPr/w:tblStyle', $domNode)) {
                $style = $xmlReader->getAttribute('w:val', $domNode, 'w:tblPr/w:tblStyle');
            } else {
                $styleNode = $xmlReader->getElement('w:tblPr', $domNode);
                $styleDefs = array();
                foreach ($margins as $side) {
                    $ucfSide = ucfirst($side);
                    $styleDefs["cellMargin$ucfSide"] = array(self::READ_VALUE, "w:tblCellMar/w:$side", 'w:w');
                }
                foreach ($borders as $side) {
                    $ucfSide = ucfirst($side);
                    $styleDefs["border{$ucfSide}Size"] = array(self::READ_VALUE, "w:tblBorders/w:$side", 'w:sz');
                    $styleDefs["border{$ucfSide}Color"] = array(self::READ_VALUE, "w:tblBorders/w:$side", 'w:color');
                    $styleDefs["border{$ucfSide}Style"] = array(self::READ_VALUE, "w:tblBorders/w:$side", 'w:val');
                }
                $styleDefs['layout'] = array(self::READ_VALUE, 'w:tblLayout', 'w:type');
                $styleDefs['bidiVisual'] = array(self::READ_TRUE, 'w:bidiVisual');
                $styleDefs['cellSpacing'] = array(self::READ_VALUE, 'w:tblCellSpacing', 'w:w');
                $style = $this->readStyleDefs($xmlReader, $styleNode, $styleDefs);

                $tablePositionNode = $xmlReader->getElement('w:tblpPr', $styleNode);
                if ($tablePositionNode !== null) {
                    $style['position'] = $this->readTablePosition($xmlReader, $tablePositionNode);
                }

                $indentNode = $xmlReader->getElement('w:tblInd', $styleNode);
                if ($indentNode !== null) {
                    $style['indent'] = $this->readTableIndent($xmlReader, $indentNode);
                }
            }
        }

        return $style;
    }

    /**
     * Read w:tblpPr
     *
     * @param \PhpOffice\Common\XMLReader $xmlReader
     * @param \DOMElement $domNode
     * @return array
     */
    private function readTablePosition(XMLReader $xmlReader, \DOMElement $domNode)
    {
        $styleDefs = array(
            'leftFromText'   => array(self::READ_VALUE, '.', 'w:leftFromText'),
            'rightFromText'  => array(self::READ_VALUE, '.', 'w:rightFromText'),
            'topFromText'    => array(self::READ_VALUE, '.', 'w:topFromText'),
            'bottomFromText' => array(self::READ_VALUE, '.', 'w:bottomFromText'),
            'vertAnchor'     => array(self::READ_VALUE, '.', 'w:vertAnchor'),
            'horzAnchor'     => array(self::READ_VALUE, '.', 'w:horzAnchor'),
            'tblpXSpec'      => array(self::READ_VALUE, '.', 'w:tblpXSpec'),
            'tblpX'          => array(self::READ_VALUE, '.', 'w:tblpX'),
            'tblpYSpec'      => array(self::READ_VALUE, '.', 'w:tblpYSpec'),
            'tblpY'          => array(self::READ_VALUE, '.', 'w:tblpY'),
        );

        return $this->readStyleDefs($xmlReader, $domNode, $styleDefs);
    }

    /**
     * Read w:tblInd
     *
     * @param \PhpOffice\Common\XMLReader $xmlReader
     * @param \DOMElement $domNode
     * @return TblWidthComplexType
     */
    private function readTableIndent(XMLReader $xmlReader, \DOMElement $domNode)
    {
        $styleDefs = array(
            'value' => array(self::READ_VALUE, '.', 'w:w'),
            'type'  => array(self::READ_VALUE, '.', 'w:type'),
        );
        $styleDefs = $this->readStyleDefs($xmlReader, $domNode, $styleDefs);

        return new TblWidthComplexType((int) $styleDefs['value'], $styleDefs['type']);
    }

    /**
     * Read w:tcPr
     *
     * @param \PhpOffice\Common\XMLReader $xmlReader
     * @param \DOMElement $domNode
     * @return array
     */
    private function readCellStyle(XMLReader $xmlReader, \DOMElement $domNode)
    {
        $styleDefs = array(
            'valign'        => array(self::READ_VALUE, 'w:vAlign'),
            'textDirection' => array(self::READ_VALUE, 'w:textDirection'),
            'gridSpan'      => array(self::READ_VALUE, 'w:gridSpan'),
            'vMerge'        => array(self::READ_VALUE, 'w:vMerge'),
            'bgColor'       => array(self::READ_VALUE, 'w:shd', 'w:fill'),
        );

        return $this->readStyleDefs($xmlReader, $domNode, $styleDefs);
    }

    /**
     * Returns the first child element found
     *
     * @param XMLReader $xmlReader
     * @param \DOMElement $parentNode
     * @param string|array $elements
     * @return string|null
     */
    private function findPossibleElement(XMLReader $xmlReader, \DOMElement $parentNode = null, $elements)
    {
        if (is_array($elements)) {
            //if element is an array, we take the first element that exists in the XML
            foreach ($elements as $possibleElement) {
                if ($xmlReader->elementExists($possibleElement, $parentNode)) {
                    return $possibleElement;
                }
            }
        } else {
            return $elements;
        }

        return null;
    }

    /**
     * Returns the first attribute found
     *
     * @param XMLReader $xmlReader
     * @param \DOMElement $node
     * @param string|array $attributes
     * @return string|null
     */
    private function findPossibleAttribute(XMLReader $xmlReader, \DOMElement $node, $attributes)
    {
        //if attribute is an array, we take the first attribute that exists in the XML
        if (is_array($attributes)) {
            foreach ($attributes as $possibleAttribute) {
                if ($xmlReader->getAttribute($possibleAttribute, $node)) {
                    return $possibleAttribute;
                }
            }

            return null;
        }

        return $attributes;
    }

    /**
     * Read style definition
     *
     * @param \PhpOffice\Common\XMLReader $xmlReader
     * @param \DOMElement $parentNode
     * @param array $styleDefs
     * @ignoreScrutinizerPatch
     * @return array
     */
    protected function readStyleDefs(XMLReader $xmlReader, \DOMElement $parentNode = null, $styleDefs = array())
    {
        $styles = array();

        foreach ($styleDefs as $styleProp => $styleVal) {
            list($method, $element, $attribute, $expected) = array_pad($styleVal, 4, null);

            $element = $this->findPossibleElement($xmlReader, $parentNode, $element);
            if ($element === null) {
                continue;
            }

            if ($xmlReader->elementExists($element, $parentNode)) {
                $node = $xmlReader->getElement($element, $parentNode);

                $attribute = $this->findPossibleAttribute($xmlReader, $node, $attribute);

                // Use w:val as default if no attribute assigned
                $attribute = ($attribute === null) ? 'w:val' : $attribute;
                $attributeValue = $xmlReader->getAttribute($attribute, $node);

                $styleValue = $this->readStyleDef($method, $attributeValue, $expected);
                if ($styleValue !== null) {
                    $styles[$styleProp] = $styleValue;
                }
            }
        }

        return $styles;
    }

    /**
     * Return style definition based on conversion method
     *
     * @param string $method
     * @ignoreScrutinizerPatch
     * @param string|null $attributeValue
     * @param mixed $expected
     * @return mixed
     */
    private function readStyleDef($method, $attributeValue, $expected)
    {
        $style = $attributeValue;

        if (self::READ_SIZE == $method) {
            $style = $attributeValue / 2;
        } elseif (self::READ_TRUE == $method) {
            $style = $this->isOn($attributeValue);
        } elseif (self::READ_FALSE == $method) {
            $style = !$this->isOn($attributeValue);
        } elseif (self::READ_EQUAL == $method) {
            $style = $attributeValue == $expected;
        }

        return $style;
    }

    /**
     * Parses the value of the on/off value, null is considered true as it means the w:val attribute was not present
     *
     * @see http://www.datypic.com/sc/ooxml/t-w_ST_OnOff.html
     * @param string $value
     * @return bool
     */
    private function isOn($value = null)
    {
        return $value === null || $value === '1' || $value === 'true' || $value === 'on';
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

        if (isset($this->rels[$docPart]) && isset($this->rels[$docPart][$rId])) {
            $target = $this->rels[$docPart][$rId]['target'];
        }

        return $target;
    }

    /**
     * Returns the target mode
     *
     * @param string $docPart
     * @param string $rId
     * @return string|null
     */
    private function getTargetMode($docPart, $rId)
    {
        $mode = null;

        if (isset($this->rels[$docPart]) && isset($this->rels[$docPart][$rId])) {
            $mode = $this->rels[$docPart][$rId]['targetMode'];
        }

        return $mode;
    }
}
