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

namespace PhpOffice\PhpWord\Reader\Word2007;

use DateTime;
use DOMElement;
use PhpOffice\PhpWord\ComplexType\TblGrid;
use PhpOffice\PhpWord\ComplexType\TblIndent;
use PhpOffice\PhpWord\ComplexType\TblWidth as TblWidthComplexType;
use PhpOffice\PhpWord\Element\AbstractContainer;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\TrackChange;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\XMLReader;
use PhpOffice\PhpWord\Style\Tab;

/**
 * Abstract part reader.
 *
 * This class is inherited by ODText reader
 *
 * @since 0.10.0
 */
abstract class AbstractPart
{
    /**
     * Conversion method.
     *
     * @const int
     */
    const READ_VALUE = 'attributeValue';            // Read attribute value
    const READ_EQUAL = 'attributeEquals';           // Read `true` when attribute value equals specified value
    const READ_TRUE = 'attributeTrue';              // Read `true` when element exists
    const READ_FALSE = 'attributeFalse';            // Read `false` when element exists
    const READ_SIZE = 'attributeMultiplyByTwo';     // Read special attribute value for Font::$size

    /**
     * Document file.
     *
     * @var string
     */
    protected $docFile;

    /**
     * XML file.
     *
     * @var string
     */
    protected $xmlFile;

    /**
     * Part relationships.
     *
     * @var array
     */
    protected $rels = [];

    /**
     * Read part.
     */
    abstract public function read(PhpWord $phpWord);

    /**
     * Create new instance.
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
    public function setRels($value): void
    {
        $this->rels = $value;
    }

    /**
     * 读取段落内容 w:p.
     *
     * @param \PhpOffice\PhpWord\Element\AbstractContainer $parent
     * @param string $docPart
     *
     * @todo Get font style for preserve text 获取保留文本的字体样式
     */
    protected function readParagraph(XMLReader $xmlReader, DOMElement $domNode, $parent, $docPart = 'document'): void
    {
        if (isset($num) == false) static $num = 0;

        // 段落样式
        $paragraphStyle = null;  //段落基本样式
        $headingDepth = null; //头部深度
        if ($xmlReader->elementExists('w:pPr', $domNode)) {
            $paragraphStyle = $this->readParagraphStyle($xmlReader, $domNode, $docPart);
            $headingDepth = $this->getHeadingDepth($paragraphStyle);
        }

        // 保留文本
        if ($xmlReader->elementExists('w:r/w:instrText', $domNode)) { //校验x:r元素下是否有w:instrText
            $ignoreText = false;
            $textContent = '';
            $fontStyle = $this->readFontStyle($xmlReader, $domNode);
            $nodes = $xmlReader->getElements('w:r', $domNode);
            $paragraph = $parent->addTextRun($paragraphStyle);
            $nextText = '';
            foreach ($nodes as $node) {
                $textContent = '';
                $instrText = $xmlReader->getValue('w:instrText', $node);
                if ($xmlReader->elementExists('w:fldChar', $node)) {
                    $fldCharType = $xmlReader->getAttribute('w:fldCharType', $node, 'w:fldChar');
                    if ('begin' == $fldCharType) {
                        $ignoreText = true;
                    } elseif ('end' == $fldCharType) {
                        $ignoreText = false;
                    }
                }
                $fontStyle = $this->readFontStyle($xmlReader, $node);
                //恢复丢失的br标签
                if ($xmlReader->elementExists('w:br', $node)) {
                    $br = $xmlReader->getElement('w:br', $node);
                    $type = $xmlReader->getAttribute('w:type', $br);
                    $paragraph->addTextBreak(NULL, $fontStyle, ['brType' => $type]);
                }
                if (null !== $instrText) {
                } else {
                    if (false === $ignoreText) {
                        $textContent = $xmlReader->getValue('w:t', $node);
                    }
                }
                if ($textContent) $paragraph->addText($textContent, $fontStyle);
            }
            //$parent->addPreserveText(htmlspecialchars($textContent, ENT_QUOTES, 'UTF-8'), $fontStyle, $paragraphStyle);
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

            //修复分页标签重复记录问题
            $is_pagebreak = false;
            if ($textRunContainers) {
                $br = $xmlReader->getElement('w:r/w:br', $domNode);
                if ($br !== null) {
                    $type = $xmlReader->getAttribute('w:type', $br);
                    if ($type == 'page') {
                        $is_pagebreak = true;
                    }
                }
            }
            if (0 === $textRunContainers) {
                $parent->addTextBreak(NULL, NULL, $paragraphStyle);
            } else {
                if (!$is_pagebreak) {
                    $nodes = $xmlReader->getElements('*', $domNode);
                    $paragraph = $parent->addTextRun($paragraphStyle);
                    foreach ($nodes as $node) {
                        $this->readRun($xmlReader, $node, $paragraph, $docPart, $paragraphStyle);
                    }
                }
            }
        }
    }

    /**
     * Returns the depth of the Heading, returns 0 for a Title.
     *
     * @param array $paragraphStyle
     *
     * @return null|number
     */
    private function getHeadingDepth(?array $paragraphStyle = null)
    {
        if (is_array($paragraphStyle) && isset($paragraphStyle['styleName'])) {
            if ('Title' === $paragraphStyle['styleName']) {
                return 0;
            }

            $headingMatches = [];
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
     * @param \PhpOffice\PhpWord\Element\AbstractContainer $parent
     * @param string $docPart
     * @param mixed $paragraphStyle
     *
     * @todo Footnote paragraph style
     */
    protected function readRun(XMLReader $xmlReader, DOMElement $domNode, $parent, $docPart, $paragraphStyle = null): void
    {

        if (in_array($domNode->nodeName, ['w:ins', 'w:del', 'w:smartTag', 'w:hyperlink'])) {
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
     * Parses nodes under w:r.
     *
     * @param string $docPart
     * @param mixed $paragraphStyle
     * @param mixed $fontStyle
     */
    protected function readRunChild(XMLReader $xmlReader, DOMElement $node, AbstractContainer $parent, $docPart, $paragraphStyle = null, $fontStyle = null): void
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
            if (null !== $target) {
                if ('External' == $this->getTargetMode($docPart, $rId)) {
                    $imageSource = $target;
                } else {
                    $imageSource = "zip://{$this->docFile}#{$target}";
                }
                $parent->addImage($imageSource);
            }
        } elseif ($node->nodeName == 'w:drawing') {

            if (in_array($docPart, ['header1', 'footer1'])) {
                $_fontStyle = $this->readFontStyle($xmlReader, $node->parentNode);
                $this->readDrawing($xmlReader, $node, $parent, $docPart, $_fontStyle);
            } else {

                // Office 2011 Image
                $xmlReader->registerNamespace('wp', 'http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing');
                $xmlReader->registerNamespace('r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');
                $xmlReader->registerNamespace('pic', 'http://schemas.openxmlformats.org/drawingml/2006/picture');
                $xmlReader->registerNamespace('a', 'http://schemas.openxmlformats.org/drawingml/2006/main');

                $name = $xmlReader->getAttribute('name', $node, 'wp:inline/a:graphic/a:graphicData/pic:pic/pic:nvPicPr/pic:cNvPr');

                $embedId = $xmlReader->getAttribute('r:embed', $node, 'wp:inline/a:graphic/a:graphicData/pic:pic/pic:blipFill/a:blip');
                if ($name === null && $embedId === null) { // some Converters puts images on a different path
                    $name = $xmlReader->getAttribute('name', $node, 'wp:anchor/a:graphic/a:graphicData/pic:pic/pic:nvPicPr/pic:cNvPr');
                    $embedId = $xmlReader->getAttribute('r:embed', $node, 'wp:anchor/a:graphic/a:graphicData/pic:pic/pic:blipFill/a:blip');
                }
                $target = $this->getMediaTarget($docPart, $embedId);
                if (null !== $target) {
                    $imageSource = "zip://{$this->docFile}#{$target}";
                    $parent->addImage($imageSource, null, false, $name);
                }
            }
        } elseif ($node->nodeName == 'w:object') {
            // Object
            $rId = $xmlReader->getAttribute('r:id', $node, 'o:OLEObject');
            // $rIdIcon = $xmlReader->getAttribute('r:id', $domNode, 'w:object/v:shape/v:imagedata');
            $target = $this->getMediaTarget($docPart, $rId);
            if (null !== $target) {
                $textContent = "&lt;Object: {$target}>";
                $parent->addText($textContent, $fontStyle, $paragraphStyle);
            }
        } elseif ($node->nodeName == 'w:br') {
            $parent->addTextBreak();
        } elseif ($node->nodeName == 'w:tab') {
            $parent->addText("\t", $fontStyle);
        } elseif ($node->nodeName == 'mc:AlternateContent') {
            if ($node->hasChildNodes()) {
                // Get fallback instead of mc:Choice to make sure it is compatible
                $fallbackElements = $node->getElementsByTagName('Fallback');

                if ($fallbackElements->length) {
                    $fallback = $fallbackElements->item(0);
                    // TextRun
                    $textContent = htmlspecialchars($fallback->nodeValue, ENT_QUOTES, 'UTF-8');

                    $parent->addText($textContent, $fontStyle, $paragraphStyle);
                }
            }
        } elseif ($node->nodeName == 'w:t' || $node->nodeName == 'w:delText') {
            // TextRun
            $textContent = htmlspecialchars($xmlReader->getValue('.', $node), ENT_QUOTES, 'UTF-8');

            if ($runParent->nodeName == 'w:hyperlink') {
                $rId = $xmlReader->getAttribute('r:id', $runParent);
                $target = $this->getMediaTarget($docPart, $rId);
                if (null !== $target) {
                    $parent->addLink($target, $textContent, $fontStyle, $paragraphStyle);
                } else {
                    $parent->addText($textContent, $fontStyle, $paragraphStyle);
                }
            } else {
                /** @var AbstractElement $element */
                $element = $parent->addText($textContent, $fontStyle, $paragraphStyle);
                if (in_array($runParent->nodeName, ['w:ins', 'w:del'])) {
                    $type = ($runParent->nodeName == 'w:del') ? TrackChange::DELETED : TrackChange::INSERTED;
                    $author = $runParent->getAttribute('w:author');
                    $date = DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $runParent->getAttribute('w:date'));
                    $element->setChangeInfo($type, $author, $date);
                }
            }
        } elseif ($node->nodeName == 'w:softHyphen') {
            $element = $parent->addText("\u{200c}", $fontStyle, $paragraphStyle);
        }
    }

    /**
     * Read image
     */
    public function readImage(XMLReader $xmlReader, DOMElement $domNode, $parent, $docPart)
    {
        // Office 2011 Image
        $xmlReader->registerNamespace('wp', 'http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing');
        $xmlReader->registerNamespace('r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');
        $xmlReader->registerNamespace('pic', 'http://schemas.openxmlformats.org/drawingml/2006/picture');
        $xmlReader->registerNamespace('a', 'http://schemas.openxmlformats.org/drawingml/2006/main');

        $name = $xmlReader->getAttribute('name', $domNode, 'wp:inline/a:graphic/a:graphicData/pic:pic/pic:nvPicPr/pic:cNvPr');

        $embedId = $xmlReader->getAttribute('r:embed', $domNode, 'wp:inline/a:graphic/a:graphicData/pic:pic/pic:blipFill/a:blip');
        if ($name === null && $embedId === null) { // some Converters puts images on a different path
            $name = $xmlReader->getAttribute('name', $domNode, 'wp:anchor/a:graphic/a:graphicData/pic:pic/pic:nvPicPr/pic:cNvPr');
            $embedId = $xmlReader->getAttribute('r:embed', $domNode, 'wp:anchor/a:graphic/a:graphicData/pic:pic/pic:blipFill/a:blip');
        }
        $target = $this->getMediaTarget($docPart, $embedId);
        $imageStyle = [];
        if (null !== $target) {
            $imageSource = "zip://{$this->docFile}#{$target}";
            $imageStyle['source'] = $imageSource;
            $imageStyle['name'] = $name;
        }
        return $imageStyle;
    }

    /**
     * Read w:drawing
     *
     * @param mixed $parent
     * @param string $docPart
     * @author <presleylee@qq.com>
     * @since 2023/8/11 2:06 下午
     */
    protected function readDrawing(XMLReader $xmlReader, DOMElement $domNode, $parent, $docPart, $fontStyle = null) :void
    {
        // Drawing style
        $drawingStyle = null;
        $inlineNode = $xmlReader->getElement('wp:inline', $domNode);
        if ($inlineNode !== null) {
            $drawingStyle['inline'] = [];
            //图片与容器四边界的距离
            $drawingStyle['inline']['distT'] = (int)$xmlReader->getAttribute('distT', $inlineNode);
            $drawingStyle['inline']['distB'] = (int)$xmlReader->getAttribute('distB', $inlineNode);
            $drawingStyle['inline']['distL'] = (int)$xmlReader->getAttribute('distL', $inlineNode);
            $drawingStyle['inline']['distR'] = (int)$xmlReader->getAttribute('distR', $inlineNode);

            //对象的大小。
            $extentNode = $xmlReader->getElement('wp:extent', $inlineNode);
            if ($extentNode !== null) {
                $drawingStyle['extent'] = [
                    'cx' => (int)$xmlReader->getAttribute('cx', $extentNode),
                    'cy' => (int)$xmlReader->getAttribute('cy', $extentNode)
                ];
            }

            //对象的效果范围。
            $effctExtentNode = $xmlReader->getElement('wp:effectExtent', $inlineNode);
            if ($effctExtentNode !== null) {
                $drawingStyle['effectExtent'] = [
                    'l' => (int)$xmlReader->getAttribute('l', $effctExtentNode),
                    't' => (int)$xmlReader->getAttribute('t', $effctExtentNode),
                    'r' => (int)$xmlReader->getAttribute('r', $effctExtentNode),
                    'b' => (int)$xmlReader->getAttribute('b', $effctExtentNode),
                ];
            }

            //绘图对象的文档属性，包括编号、名称和描述。
            $docPrNode = $xmlReader->getElement('wp:docPr', $inlineNode);
            if ($docPrNode !== null) {
                $drawingStyle['docPr'] = [
                    'id' => (int)$xmlReader->getAttribute('id', $docPrNode),
                    'name' => $xmlReader->getAttribute('name', $docPrNode),
                    'descr' => $xmlReader->getAttribute('descr', $docPrNode)
                ];
            }

            //图形帧的非视觉属性
            $xmlReader->registerNamespace('pic', 'http://schemas.openxmlformats.org/drawingml/2006/picture');
            $xmlReader->registerNamespace('a', 'http://schemas.openxmlformats.org/drawingml/2006/main');
            $nvGraphicFPrNode = $xmlReader->getElement('wp:cNvGraphicFramePr', $inlineNode);
            if ($nvGraphicFPrNode !== null) {
                $graphicFChildNode = $xmlReader->getElement('a:graphicFrameLocks', $nvGraphicFPrNode);
                if ($graphicFChildNode !== null) {
                    $drawingStyle['nvGraphicFPr'] = [
                        'xmlns:a' => $xmlReader->getAttribute('xmlns:a', $graphicFChildNode),
                        'noChangeAspect' => (int)$xmlReader->getAttribute('noChangeAspect', $graphicFChildNode)
                    ];
                }
            }


            $picNode = $xmlReader->getElement('a:graphic/a:graphicData/pic:pic', $inlineNode);
            if ($picNode !== null) {
                $drawingStyle['graphic']['val'] = $xmlReader->getAttribute('xmlns:a', $inlineNode, 'a:graphic');
                $drawingStyle['graphic']['graphicUri'] = $xmlReader->getAttribute('uri', $inlineNode, 'a:graphic/a:graphicData');
                $drawingStyle['graphic']['pic'] = $xmlReader->getAttribute('xmlns:pic', $picNode);
                $nvPicPrNode = $xmlReader->getElement('pic:nvPicPr', $picNode);
                if ($nvPicPrNode !== null) {
                    $drawingStyle['graphic']['nvPicPr'] = [
                        'id' => (int)$xmlReader->getAttribute('id', $nvPicPrNode, 'pic:cNvPr'),
                        'name' => $xmlReader->getAttribute('name', $nvPicPrNode, 'pic:cNvPr'),
                        'descr' => $xmlReader->getAttribute('descr', $nvPicPrNode, 'pic:cNvPr'),
                        'cNvPicPr' => $xmlReader->getAttribute('noChangeAspect', $nvPicPrNode, 'pic:cNvPicPr/a:picLocks'),
                    ];
                }

                $blipFillNode = $xmlReader->getElement('pic:blipFill', $picNode);
                if ($blipFillNode !== null) {
                    $drawingStyle['graphic']['blipFill'] = [
                        'blip' => $xmlReader->getAttribute('r:embed', $blipFillNode, 'a:blip'),
                        'fillRect' => $xmlReader->elementExists('a:fillRect', $blipFillNode),
                    ];
                }

                $spPrNode = $xmlReader->getElement('pic:spPr', $picNode);
                if ($spPrNode !== null) {
                    $drawingStyle['graphic']['spPr'] = [
                        'xfrm_off_x' => $xmlReader->getAttribute('x', $spPrNode, 'a:xfrm/a:off'),
                        'xfrm_off_y' => $xmlReader->getAttribute('x', $spPrNode, 'a:xfrm/a:off'),
                        'xfrm_ext_cx' => $xmlReader->getAttribute('cx', $spPrNode, 'a:xfrm/a:ext'),
                        'xfrm_ext_cy' => $xmlReader->getAttribute('cy', $spPrNode, 'a:xfrm/a:ext'),
                        'prstGeom_prst' => $xmlReader->getAttribute('prst', $spPrNode, 'a:prstGeom'),
                    ];
                }
            }

            $drawingStyle['image'] = $this->readImage($xmlReader, $domNode, $parent, $docPart);
        }

        if($drawingStyle){
            $parent->addDrawing($drawingStyle, $fontStyle);
        }
    }

    /**
     * Read w:tbl.
     *
     * @param mixed $parent
     * @param string $docPart
     */
    protected function readTable(XMLReader $xmlReader, DOMElement $domNode, $parent, $docPart = 'document'): void
    {
        // Table style
        $tblStyle = null;
        if ($xmlReader->elementExists('w:tblPr', $domNode)) {
            $tblStyle = $this->readTableStyle($xmlReader, $domNode);
        }

        /** @var \PhpOffice\PhpWord\Element\Table $table Type hint */
        $table = $parent->addTable($tblStyle);
        $tblNodes = $xmlReader->getElements('*', $domNode); //gridSpan
        foreach ($tblNodes as $tblNode) {
            if ('w:tblGrid' == $tblNode->nodeName) { // Column
                // @todo Do something with table columns
            } elseif ('w:tr' == $tblNode->nodeName) { // Row
                $rowHeight = $xmlReader->getAttribute('w:val', $tblNode, 'w:trPr/w:trHeight');
                $rowHRule = $xmlReader->getAttribute('w:hRule', $tblNode, 'w:trPr/w:trHeight');
                $rowHRule = $rowHRule == 'exact';
                $rowStyle = [
                    'tblHeader' => $xmlReader->elementExists('w:trPr/w:tblHeader', $tblNode),
                    'cantSplit' => $xmlReader->elementExists('w:trPr/w:cantSplit', $tblNode),
                    'exactHeight' => $rowHRule,
                ];

                $row = $table->addRow($rowHeight, $rowStyle);
                $rowNodes = $xmlReader->getElements('*', $tblNode);
                foreach ($rowNodes as $rowNode) {
                    if ('w:trPr' == $rowNode->nodeName) { // Row style
                        // @todo Do something with row style
                    } elseif ('w:tc' == $rowNode->nodeName) { // Cell
                        $cellWidth = $xmlReader->getAttribute('w:w', $rowNode, 'w:tcPr/w:tcW');
                        $cellStyle = null;
                        $cellStyleNode = $xmlReader->getElement('w:tcPr', $rowNode);
                        if (null !== $cellStyleNode) {
                            $cellStyle = $this->readCellStyle($xmlReader, $cellStyleNode);
                        }
                        $cell = $row->addCell($cellWidth, $cellStyle);
                        $cellNodes = $xmlReader->getElements('*', $rowNode);
                        foreach ($cellNodes as $cellNode) {
                            if ('w:p' == $cellNode->nodeName) { // Paragraph
                                $this->readParagraph($xmlReader, $cellNode, $cell, $docPart);
                            } elseif ($cellNode->nodeName == 'w:tbl') { // Table
                                $this->readTable($xmlReader, $cellNode, $cell, $docPart);
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
     * @return null|array
     */
    protected function readParagraphStyle(XMLReader $xmlReader, DOMElement $domNode, $docPart = 'document')
    {
        if (!$xmlReader->elementExists('w:pPr', $domNode)) {
            return null;
        }

        $styleNode = $xmlReader->getElement('w:pPr', $domNode);
        $styleDefs = [
            'styleName' => [self::READ_VALUE, ['w:pStyle', 'w:name']],
            'alignment' => [self::READ_VALUE, 'w:jc'],
            'basedOn' => [self::READ_VALUE, 'w:basedOn'],
            'next' => [self::READ_VALUE, 'w:next'],
            'firstLine' => [self::READ_VALUE, 'w:ind', 'w:firstLine'],
            'left' => [self::READ_VALUE, 'w:ind', 'w:left'],
            'right' => [self::READ_VALUE, 'w:ind', 'w:right'],
            'firstLineChars' => [self::READ_VALUE, 'w:ind', 'w:firstLineChars'],
            'indLeftChar' => [self::READ_VALUE, 'w:ind', 'w:leftChars'],
            'hanging' => [self::READ_VALUE, 'w:ind', 'w:hanging'],
            'hangingChars' => [self::READ_VALUE, 'w:ind', 'w:hangingChars'],
            'spaceLine' => [self::READ_VALUE, 'w:spacing', 'w:line'],
            'spaceLineRule' => [self::READ_VALUE, 'w:spacing', 'w:lineRule'],
            'spaceAfter' => [self::READ_VALUE, 'w:spacing', 'w:after'],
            'spaceBefore' => [self::READ_VALUE, 'w:spacing', 'w:before'],
            'widowControl' => [self::READ_FALSE, 'w:widowControl'],
            'keepNext' => [self::READ_TRUE,  'w:keepNext'],
            'keepLines' => [self::READ_TRUE,  'w:keepLines'],
            'pageBreakBefore' => [self::READ_TRUE,  'w:pageBreakBefore'],
            'contextualSpacing' => [self::READ_TRUE,  'w:contextualSpacing'],
            'bidi' => [self::READ_TRUE,  'w:bidi'],
            'suppressAutoHyphens' => [self::READ_TRUE,  'w:suppressAutoHyphens'],
        ];

        $paragraphStyle = $this->readStyleDefs($xmlReader, $styleNode, $styleDefs);
        if ( $xmlReader->elementExists('w:bookmarkStart', $domNode) || $xmlReader->elementExists('w:bookmarkEnd', $domNode)) {
            $styleDefs = [
                'bookmarkStartID' => [self::READ_VALUE,  'w:bookmarkStart', 'w:id'],
                'bookmarkStartName' => [self::READ_VALUE,  'w:bookmarkStart', 'w:name'],
                'bookmarkEnd' => [self::READ_VALUE,  'w:bookmarkEnd', 'w:id'],
            ];
            $result = $this->readStyleDefs($xmlReader, $domNode, $styleDefs, 0);
            $paragraphStyle = array_merge($paragraphStyle, $result??[]);
        }
        $paragraphStyle['font'] = $this->readFontStyle($xmlReader, $styleNode);
        if ( $xmlReader->elementExists('w:tabs', $styleNode)) {
            $tabNodes = $xmlReader->getElements('w:tabs/w:tab', $styleNode);
            $tabs = [];
            foreach ($tabNodes as $node) {
                $_tab = [];
                $val = $xmlReader->getAttribute('w:val', $node) ;
                $pos = $xmlReader->getAttribute('w:pos', $node) ;
                $leader = $xmlReader->getAttribute('w:leader', $node) ;
                if ($val !== null) $_tab['type'] = $val;
                if ($pos !== null) $_tab['position'] = $pos;
                if ($leader !== null) $_tab['leader'] = $leader;
                if ($_tab) {
                    $tabs[] = new Tab($_tab['type']??null, $_tab['position']??0, $_tab['leader']??null);
                }
            }
            if ($tabs) $paragraphStyle['tabs'] = $tabs;
        }

        if (in_array($docPart, ['header1', 'footer1'])){
            $pBdr = $xmlReader->getElement('w:pBdr', $styleNode);
            $styleDefs = [
                'bottom_bStyle' => [self::READ_VALUE, 'w:bottom', 'w:val'],
                'bottom_bColor' => [self::READ_VALUE, 'w:bottom', 'w:color'],
                'bottom_bSz' => [self::READ_VALUE, 'w:bottom', 'w:sz'],
                'bottom_bspace' => [self::READ_VALUE, 'w:bottom', 'w:space'],
                'top_bStyle' => [self::READ_VALUE, 'w:top', 'w:val'],
                'top_bColor' => [self::READ_VALUE, 'w:bottom', 'w:color'],
                'top_bSz' => [self::READ_VALUE, 'w:bottom', 'w:sz'],
                'top_bspace' => [self::READ_VALUE, 'w:bottom', 'w:space'],
            ];
        }

        return $paragraphStyle;
    }

    /**
     * Read w:latentStyles.
     *
     * @return null|array
     */
    protected function readLatentStyle(XMLReader $xmlReader, DOMElement $domNode)
    {
        $latentStyle = [];
        $latentStyle['count'] = $xmlReader->getAttribute('w:count', $domNode);
        $latentStyle['defQFormat'] = $xmlReader->getAttribute('w:defQFormat', $domNode);
        $latentStyle['defUnhideWhenUsed'] = $xmlReader->getAttribute('w:defUnhideWhenUsed', $domNode);
        $latentStyle['defSemiHidden'] = $xmlReader->getAttribute('w:defSemiHidden', $domNode);
        $latentStyle['defUIPriority'] = $xmlReader->getAttribute('w:defUIPriority', $domNode);
        $latentStyle['defLockedState'] = $xmlReader->getAttribute('w:defLockedState', $domNode);
        $latentStyle['lsdExceptions'] = [];
        return $latentStyle;
    }


    /**
     * Read w:rPr.
     *
     * @return null|array
     */
    protected function readFontStyle(XMLReader $xmlReader, DOMElement $domNode)
    {
        if (null === $domNode) {
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
        $styleDefs = [
            'styleName' => [self::READ_VALUE, 'w:rStyle'],
            'kerning' => [self::READ_VALUE, 'w:kern', 'w:val'],
            'name' => [self::READ_VALUE, 'w:rFonts', 'w:eastAsia'],
            'ascii' => [self::READ_VALUE, 'w:rFonts', 'w:ascii'],
            'hAnsi' => [self::READ_VALUE, 'w:rFonts', 'w:hAnsi'],
            'cs' => [self::READ_VALUE, 'w:rFonts', 'w:cs'],
            'hint' => [self::READ_VALUE, 'w:rFonts', 'w:hint'],
            'size' => [self::READ_SIZE,  'w:sz'],
            'sizeCs' => [self::READ_SIZE,  'w:szCs'],
            'color' => [self::READ_VALUE, 'w:color', 'w:val'],
            'themeColor' => [self::READ_VALUE,  'w:color', 'w:themeColor'],
            'themeShade' => [self::READ_VALUE,  'w:color', 'w:themeShade'],
            'underline' => [self::READ_VALUE, 'w:u'],
            'uValue' => [self::READ_VALUE, 'w:u', 'w:val'],
            'uColor' => [self::READ_VALUE, 'w:u', 'w:color'],
            'bold' => [self::READ_TRUE,  'w:b'],
            'italic' => [self::READ_TRUE,  'w:i'],
            'strikethrough' => [self::READ_TRUE,  'w:strike'],
            'doubleStrikethrough' => [self::READ_TRUE,  'w:dstrike'],
            'smallCaps' => [self::READ_TRUE,  'w:smallCaps'],
            'allCaps' => [self::READ_TRUE,  'w:caps'],
            'superScript' => [self::READ_EQUAL, 'w:vertAlign', 'w:val', 'superscript'],
            'subScript' => [self::READ_EQUAL, 'w:vertAlign', 'w:val', 'subscript'],
            'fgColor' => [self::READ_VALUE, 'w:highlight'],
            'rtl' => [self::READ_TRUE,  'w:rtl'],
            'lang' => [self::READ_VALUE, 'w:lang', 'w:val'],
            'langEA' => [self::READ_VALUE, 'w:lang', 'w:eastAsia'],
            'langBidi' => [self::READ_VALUE, 'w:lang', 'w:bidi'],
            'position' => [self::READ_VALUE, 'w:position'],
            'hidden' => [self::READ_TRUE,  'w:vanish'],
        ];

        $fontStyles = $this->readStyleDefs($xmlReader, $styleNode, $styleDefs);
        if (isset($fontStyles['langEA']) || isset($fontStyles['langBidi'])) {
            $lang = [];
            $lang['latin'] = $fontStyles['lang']??null;
            isset($fontStyles['langEA']) && $lang['eastAsia'] = $fontStyles['langEA'];
            isset($fontStyles['langBidi']) && $lang['bidirectional'] = $fontStyles['langBidi'];
            $fontStyles['lang'] = $lang;
        }

        if ($domNode->tagName == 'w:r') {
            $tabNode = $xmlReader->elementExists('w:tab', $domNode);
            if ($tabNode != null) {
                $fontStyles['tab'] = 1;
            }
        }
        return $fontStyles;
    }

    /**
     * Read w:tblPr.
     *
     * @return null|array|string
     *
     * @todo Capture w:tblStylePr w:type="firstRow"
     */
    protected function readTableStyle(XMLReader $xmlReader, DOMElement $domNode)
    {
        $style = null;
        $margins = ['top', 'left', 'bottom', 'right'];
        $borders = array_merge($margins, ['insideH', 'insideV']);

        if ($xmlReader->elementExists('w:tblPr', $domNode)) {
            $styleNode = $xmlReader->getElement('w:tblPr', $domNode);
            $styleDefs = [];
            if ($xmlReader->elementExists('w:tblStyle', $styleNode)) {
                $styleDefs['tblStyle'] = [self::READ_VALUE, 'w:tblStyle', 'w:value'];
            }
            foreach ($margins as $side) {
                $ucfSide = ucfirst($side);
                $styleDefs["cellMargin$ucfSide"] = [self::READ_VALUE, "w:tblCellMar/w:$side", 'w:w'];
            }
            foreach ($borders as $side) {
                $ucfSide = ucfirst($side);
                $styleDefs["border{$ucfSide}Size"] = [self::READ_VALUE, "w:tblBorders/w:$side", 'w:sz'];
                $styleDefs["border{$ucfSide}Color"] = [self::READ_VALUE, "w:tblBorders/w:$side", 'w:color'];
                $styleDefs["border{$ucfSide}Style"] = [self::READ_VALUE, "w:tblBorders/w:$side", 'w:val'];
            }

            $styleDefs['layout'] = [self::READ_VALUE, 'w:tblLayout', 'w:type'];
            $styleDefs['bidiVisual'] = [self::READ_TRUE, 'w:bidiVisual'];
            $styleDefs['cellSpacing'] = [self::READ_VALUE, 'w:tblCellSpacing', 'w:w'];
            $style = $this->readStyleDefs($xmlReader, $styleNode, $styleDefs);

            $tablePositionNode = $xmlReader->getElement('w:tblpPr', $styleNode);
            if ($tablePositionNode !== null) {
                $style['position'] = $this->readTablePosition($xmlReader, $tablePositionNode);
            }

            $tableWidthNode = $xmlReader->getElement('w:tblW', $styleNode);
            if($tableWidthNode != null) {
                $style['tblWidth'] = $this->readTableWidth($xmlReader, $tableWidthNode);
            }

            $indentNode = $xmlReader->getElement('w:tblInd', $styleNode);
            if ($indentNode !== null) {
                $style['indent'] = $this->readTableIndent($xmlReader, $indentNode);
            }
        }
        if ($xmlReader->elementExists('w:tblGrid', $domNode)) {
            $tblGridNode = $xmlReader->getElement('w:tblGrid', $domNode);
            $style['tblGrid'] = $this->readTableGrid($xmlReader, $tblGridNode);
        }
        return $style;
    }

    /**
     * Read w:tblpPr.
     *
     * @return array
     */
    private function readTablePosition(XMLReader $xmlReader, DOMElement $domNode)
    {
        $styleDefs = [
            'leftFromText' => [self::READ_VALUE, '.', 'w:leftFromText'],
            'rightFromText' => [self::READ_VALUE, '.', 'w:rightFromText'],
            'topFromText' => [self::READ_VALUE, '.', 'w:topFromText'],
            'bottomFromText' => [self::READ_VALUE, '.', 'w:bottomFromText'],
            'vertAnchor' => [self::READ_VALUE, '.', 'w:vertAnchor'],
            'horzAnchor' => [self::READ_VALUE, '.', 'w:horzAnchor'],
            'tblpXSpec' => [self::READ_VALUE, '.', 'w:tblpXSpec'],
            'tblpX' => [self::READ_VALUE, '.', 'w:tblpX'],
            'tblpYSpec' => [self::READ_VALUE, '.', 'w:tblpYSpec'],
            'tblpY' => [self::READ_VALUE, '.', 'w:tblpY'],
        ];

        return $this->readStyleDefs($xmlReader, $domNode, $styleDefs);
    }

    /**
     * Read w:tblWidth.
     *
     * @return TblWidthComplexType
     */
    private function readTableWidth(XMLReader $xmlReader, DOMElement $domNode)
    {
        $styleDefs = [
            'value' => [self::READ_VALUE, '.', 'w:w'],
            'type' => [self::READ_VALUE, '.', 'w:type'],
        ];
        $styleDefs = $this->readStyleDefs($xmlReader, $domNode, $styleDefs);

        return new TblWidthComplexType((int) $styleDefs['value']??0, $styleDefs['type']??'');
    }

    /**
     * Read w:tblInd.
     *
     * @return TblIndent
     */
    private function readTableIndent(XMLReader $xmlReader, DOMElement $domNode)
    {
        $styleDefs = [
            'value' => [self::READ_VALUE, '.', 'w:w'],
            'type' => [self::READ_VALUE, '.', 'w:type'],
        ];
        $styleDefs = $this->readStyleDefs($xmlReader, $domNode, $styleDefs);

        return new TblIndent((int) $styleDefs['value']??0, $styleDefs['type']??'');
    }

    public function readTableGrid(XMLReader $xmlReader, DOMElement $domNode)
    {
        $gridCols = $xmlReader->getElements('w:gridCol', $domNode);
        $tblGrids = [];
        foreach ($gridCols as $col) {
            $value = $xmlReader->getAttribute('w:w', $col);
            if ($value !== null && $value) {
                $tblGrids[] = new TblGrid((int) $value??0);
            }
        }
        return $tblGrids;
    }

    /**
     * Read w:tcPr.
     *
     * @return array
     */
    private function readCellStyle(XMLReader $xmlReader, DOMElement $domNode)
    {
        $styleDefs = [
            'valign' => [self::READ_VALUE, 'w:vAlign'],
            'textDirection' => [self::READ_VALUE, 'w:textDirection'],
            'gridSpan' => [self::READ_VALUE, 'w:gridSpan'],
            'vMerge' => [self::READ_VALUE, 'w:vMerge', null, null, 'continue'],
            'bgColor' => [self::READ_VALUE, 'w:shd', 'w:fill'],
        ];

        return $this->readStyleDefs($xmlReader, $domNode, $styleDefs);
    }

    /**
     * Returns the first child element found.
     *
     * @param null|array|string $elements
     *
     * @return null|string
     */
    private function findPossibleElement(XMLReader $xmlReader, ?DOMElement $parentNode = null, $elements = null)
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
     * Returns the first attribute found.
     *
     * @param array|string $attributes
     *
     * @return null|string
     */
    private function findPossibleAttribute(XMLReader $xmlReader, DOMElement $node, $attributes)
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
     * 读取样式定义
     *
     * @param DOMElement $parentNode
     * @param array $styleDefs
     *
     * @ignoreScrutinizerPatch
     *
     * @return array
     */
    protected function readStyleDefs(XMLReader $xmlReader, ?DOMElement $parentNode = null, $styleDefs = [])
    {
        $styles = [];

        foreach ($styleDefs as $styleProp => $styleVal) {
            [$method, $element, $attribute, $expected, $default] = array_pad($styleVal, 5, null);
            $element = $this->findPossibleElement($xmlReader, $parentNode, $element);
            if ($element === null) {
                continue;
            }
            if ($xmlReader->elementExists($element, $parentNode)) {
                $node = $xmlReader->getElement($element, $parentNode);
                $attribute = $this->findPossibleAttribute($xmlReader, $node, $attribute);
                // Use w:val as default if no attribute assigned
                $attribute = ($attribute === null) ? 'w:val' : $attribute;
                $attributeValue = $xmlReader->getAttribute($attribute, $node) ?? $default;
                $styleValue = $this->readStyleDef($method, $attributeValue, $expected);
                if ($styleValue !== null) {
                    $styles[$styleProp] = $styleValue;
                }
            }
        }
        return $styles;
    }

    /**
     * Return style definition based on conversion method.
     *
     * @param string $method
     *
     * @ignoreScrutinizerPatch
     *
     * @param null|string $attributeValue
     * @param mixed $expected
     *
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
     * Parses the value of the on/off value, null is considered true as it means the w:val attribute was not present.
     *
     * @see http://www.datypic.com/sc/ooxml/t-w_ST_OnOff.html
     *
     * @param string $value
     *
     * @return bool
     */
    private function isOn($value = null)
    {
        return $value === null || $value === '1' || $value === 'true' || $value === 'on';
    }

    /**
     * Returns the target of image, object, or link as stored in ::readMainRels.
     *
     * @param string $docPart
     * @param string $rId
     *
     * @return null|string
     */
    private function getMediaTarget($docPart, $rId)
    {
        $target = null;

        if (isset($this->rels[$docPart], $this->rels[$docPart][$rId])) {
            $target = $this->rels[$docPart][$rId]['target'];
        }

        return $target;
    }

    /**
     * Returns the target mode.
     *
     * @param string $docPart
     * @param string $rId
     *
     * @return null|string
     */
    private function getTargetMode($docPart, $rId)
    {
        $mode = null;

        if (isset($this->rels[$docPart], $this->rels[$docPart][$rId])) {
            $mode = $this->rels[$docPart][$rId]['targetMode'];
        }

        return $mode;
    }
}
