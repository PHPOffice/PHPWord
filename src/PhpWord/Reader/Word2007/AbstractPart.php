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
 * Abstract part reader
 */
abstract class AbstractPart
{
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
     * Read part
     */
    abstract public function read(PhpWord &$phpWord);

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
     * Set relationships
     *
     * @param array $value
     */
    public function setRels($value)
    {
        $this->rels = $value;
    }

    /**
     * Read w:r
     *
     * @param \PhpOffice\PhpWord\Shared\XMLReader $xmlReader
     * @param \DOMElement $domNode
     * @param mixed $parent
     * @param string $docPart
     * @param mixed $paragraphStyle
     *
     * @todo Footnote paragraph style
     */
    protected function readRun(XMLReader $xmlReader, \DOMElement $domNode, &$parent, $docPart, $paragraphStyle = null)
    {
        if (!in_array($domNode->nodeName, array('w:r', 'w:hyperlink'))) {
            return;
        }
        $fontStyle = $this->readFontStyle($xmlReader, $domNode);

        // Link
        if ($domNode->nodeName == 'w:hyperlink') {
            $rId = $xmlReader->getAttribute('r:id', $domNode);
            $textContent = $xmlReader->getValue('w:r/w:t', $domNode);
            $target = $this->getMediaTarget($docPart, $rId);
            if (!is_null($target)) {
                $parent->addLink($target, $textContent, $fontStyle, $paragraphStyle);
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
                    $imageSource = "zip://{$this->docFile}#{$target}";
                    $parent->addImage($imageSource);
                }

            // Object
            } elseif ($xmlReader->elementExists('w:object', $domNode)) {
                $rId = $xmlReader->getAttribute('r:id', $domNode, 'w:object/o:OLEObject');
                // $rIdIcon = $xmlReader->getAttribute('r:id', $domNode, 'w:object/v:shape/v:imagedata');
                $target = $this->getMediaTarget($docPart, $rId);
                if (!is_null($target)) {
                    $textContent = "<Object: {$target}>";
                    $parent->addText($textContent, $fontStyle, $paragraphStyle);
                }

            // TextRun
            } else {
                $textContent = $xmlReader->getValue('w:t', $domNode);
                $parent->addText($textContent, $fontStyle, $paragraphStyle);
            }
        }
    }

    /**
     * Read w:pPr
     *
     * @return string|array|null
     */
    protected function readParagraphStyle(XMLReader $xmlReader, \DOMElement $domNode)
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
    protected function readFontStyle(XMLReader $xmlReader, \DOMElement $domNode)
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
    protected function readTableStyle(XMLReader $xmlReader, \DOMElement $domNode)
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
