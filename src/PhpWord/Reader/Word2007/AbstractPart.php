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
 * Abstract part reader
 *
 * This class is inherited by ODText reader
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
    const READ_TRUE  = 'attributeTrue';             // Read `true` when element exists
    const READ_FALSE = 'attributeFalse';            // Read `false` when element exists
    const READ_SIZE  = 'attributeMultiplyByTwo';    // Read special attribute value for Font::$size

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
     * @param \PhpOffice\PhpWord\Shared\XMLReader $xmlReader
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
            'styleName'       => array(self::READ_VALUE, 'w:pStyle'),
            'align'           => array(self::READ_VALUE, 'w:jc'),
            'basedOn'         => array(self::READ_VALUE, 'w:basedOn'),
            'next'            => array(self::READ_VALUE, 'w:next'),
            'indent'          => array(self::READ_VALUE, 'w:ind', 'w:left'),
            'hanging'         => array(self::READ_VALUE, 'w:ind', 'w:hanging'),
            'spaceAfter'      => array(self::READ_VALUE, 'w:spacing', 'w:after'),
            'spaceBefore'     => array(self::READ_VALUE, 'w:spacing', 'w:before'),
            'widowControl'    => array(self::READ_FALSE, 'w:widowControl'),
            'keepNext'        => array(self::READ_TRUE,  'w:keepNext'),
            'keepLines'       => array(self::READ_TRUE,  'w:keepLines'),
            'pageBreakBefore' => array(self::READ_TRUE,  'w:pageBreakBefore'),
        );

        return $this->readStyleDefs($xmlReader, $styleNode, $styleDefs);
    }

    /**
     * Read w:rPr
     *
     * @param \PhpOffice\PhpWord\Shared\XMLReader $xmlReader
     * @param \DOMElement $domNode
     * @return array
     */
    protected function readFontStyle(XMLReader $xmlReader, \DOMElement $domNode)
    {
        if (is_null($domNode)) {
            return null;
        }
        // Hyperlink has an extra w:r child
        if ($domNode->nodeName == 'w:hyperlink') {
            $domNode = $xmlReader->getElement('w:r', $domNode);
        }
        if (!$xmlReader->elementExists('w:rPr', $domNode)) {
            return null;
        }

        $styleNode = $xmlReader->getElement('w:rPr', $domNode);
        $styleDefs = array(
            'styleName'           => array(self::READ_VALUE, 'w:rStyle'),
            'name'                => array(self::READ_VALUE, 'w:rFonts', 'w:ascii'),
            'hint'                => array(self::READ_VALUE, 'w:rFonts', 'w:hint'),
            'size'                => array(self::READ_SIZE,  'w:sz'),
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
        );

        return $this->readStyleDefs($xmlReader, $styleNode, $styleDefs);
    }

    /**
     * Read w:tblPr
     *
     * @param \PhpOffice\PhpWord\Shared\XMLReader $xmlReader
     * @param \DOMElement $domNode
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
                $styleNode = $xmlReader->getElement('w:tblPr', $domNode);
                // $styleDefs['styleName'] = array(self::READ_VALUE, 'w:tblStyle');
                foreach ($margins as $side) {
                    $ucfSide = ucfirst($side);
                    $styleDefs["cellMargin$ucfSide"] = array(self::READ_VALUE, "w:tblCellMar/w:$side", 'w:w');
                }
                foreach ($borders as $side) {
                    $ucfSide = ucfirst($side);
                    $styleDefs["border{$ucfSide}Size"] = array(self::READ_VALUE, "w:tblBorders/w:$side", 'w:sz');
                    $styleDefs["border{$ucfSide}Color"] = array(self::READ_VALUE, "w:tblBorders/w:$side", 'w:color');
                }
                $style = $this->readStyleDefs($xmlReader, $styleNode, $styleDefs);
            }
        }

        return $style;
    }

    /**
     * Read style definition
     *
     * @param \PhpOffice\PhpWord\Shared\XMLReader $xmlReader
     * @param \DOMElement $domNode
     * @param array $styleDefs
     * @return array
     */
    protected function readStyleDefs(XMLReader $xmlReader, \DOMElement $parentNode, $styleDefs)
    {
        $styles = array();

        foreach ($styleDefs as $styleProp => $styleVal) {
            @list($method, $element, $attribute, $expected) = $styleVal;

            if ($xmlReader->elementExists($element, $parentNode)) {
                $node = $xmlReader->getElement($element, $parentNode);

                // Use w:val as default if no attribute assigned
                $attribute = ($attribute === null) ? 'w:val' : $attribute;
                $attributeValue = $xmlReader->getAttribute($attribute, $node);

                // Assign style value based on conversion model
                if ($method == self::READ_VALUE) {
                    $styles[$styleProp] = $attributeValue;
                } elseif ($method == self::READ_SIZE) {
                    $styles[$styleProp] = $attributeValue / 2;
                } elseif ($method == self::READ_TRUE) {
                    $styles[$styleProp] = true;
                } elseif ($method == self::READ_FALSE) {
                    $styles[$styleProp] = false;
                } elseif ($method == self::READ_EQUAL && $attributeValue == $expected) {
                    $styles[$styleProp] = true;
                }
            }
        }

        return $styles;
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
