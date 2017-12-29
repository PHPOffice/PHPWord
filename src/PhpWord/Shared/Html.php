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
 * @copyright   2010-2017 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Shared;

use PhpOffice\PhpWord\Element\AbstractContainer;
use PhpOffice\PhpWord\Element\Cell;
use PhpOffice\PhpWord\Element\Row;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\SimpleType\Jc;

/**
 * Common Html functions
 *
 * @SuppressWarnings(PHPMD.UnusedPrivateMethod) For readWPNode
 */
class Html
{
    /**
     * Add HTML parts.
     *
     * Note: $stylesheet parameter is removed to avoid PHPMD error for unused parameter
     *
     * @param \PhpOffice\PhpWord\Element\AbstractContainer $element Where the parts need to be added
     * @param string $html The code to parse
     * @param bool $fullHTML If it's a full HTML, no need to add 'body' tag
     * @param bool $preserveWhiteSpace If false, the whitespaces between nodes will be removed
     */
    public static function addHtml($element, $html, $fullHTML = false, $preserveWhiteSpace = true)
    {
        /*
         * @todo parse $stylesheet for default styles.  Should result in an array based on id, class and element,
         * which could be applied when such an element occurs in the parseNode function.
         */

        // Preprocess: remove all line ends, decode HTML entity,
        // fix ampersand and angle brackets and add body tag for HTML fragments
        $html = str_replace(array("\n", "\r"), '', $html);
        $html = str_replace(array('&lt;', '&gt;', '&amp;'), array('_lt_', '_gt_', '_amp_'), $html);
        $html = html_entity_decode($html, ENT_QUOTES, 'UTF-8');
        $html = str_replace('&', '&amp;', $html);
        $html = str_replace(array('_lt_', '_gt_', '_amp_'), array('&lt;', '&gt;', '&amp;'), $html);

        if (false === $fullHTML) {
            $html = '<body>' . $html . '</body>';
        }

        // Load DOM
        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = $preserveWhiteSpace;
        $dom->loadXML($html);
        $node = $dom->getElementsByTagName('body');

        self::parseNode($node->item(0), $element);
    }

    /**
     * parse Inline style of a node
     *
     * @param \DOMNode $node Node to check on attributes and to compile a style array
     * @param array $styles is supplied, the inline style attributes are added to the already existing style
     * @return array
     */
    protected static function parseInlineStyle($node, $styles = array())
    {
        if (XML_ELEMENT_NODE == $node->nodeType) {
            $attributes = $node->attributes; // get all the attributes(eg: id, class)

            foreach ($attributes as $attribute) {
                switch ($attribute->name) {
                    case 'style':
                        $styles = self::parseStyle($attribute, $styles);
                        break;
                }
            }
        }

        return $styles;
    }

    /**
     * Parse a node and add a corresponding element to the parent element.
     *
     * @param \DOMNode $node node to parse
     * @param \PhpOffice\PhpWord\Element\AbstractContainer $element object to add an element corresponding with the node
     * @param array $styles Array with all styles
     * @param array $data Array to transport data to a next level in the DOM tree, for example level of listitems
     */
    protected static function parseNode($node, $element, $styles = array(), $data = array())
    {
        // Populate styles array
        $styleTypes = array('font', 'paragraph', 'list', 'table', 'row', 'cell');
        foreach ($styleTypes as $styleType) {
            if (!isset($styles[$styleType])) {
                $styles[$styleType] = array();
            }
        }

        // Node mapping table
        $nodes = array(
                              // $method        $node   $element    $styles     $data   $argument1      $argument2
            'p'         => array('Paragraph',   $node,  $element,   $styles,    null,   null,           null),
            'h1'        => array('Heading',     null,   $element,   $styles,    null,   'Heading1',     null),
            'h2'        => array('Heading',     null,   $element,   $styles,    null,   'Heading2',     null),
            'h3'        => array('Heading',     null,   $element,   $styles,    null,   'Heading3',     null),
            'h4'        => array('Heading',     null,   $element,   $styles,    null,   'Heading4',     null),
            'h5'        => array('Heading',     null,   $element,   $styles,    null,   'Heading5',     null),
            'h6'        => array('Heading',     null,   $element,   $styles,    null,   'Heading6',     null),
            '#text'     => array('Text',        $node,  $element,   $styles,    null,   null,           null),
            'strong'    => array('Property',    null,   null,       $styles,    null,   'bold',         true),
            'b'         => array('Property',    null,   null,       $styles,    null,   'bold',         true),
            'em'        => array('Property',    null,   null,       $styles,    null,   'italic',       true),
            'i'         => array('Property',    null,   null,       $styles,    null,   'italic',       true),
            'u'         => array('Property',    null,   null,       $styles,    null,   'underline',    'single'),
            'sup'       => array('Property',    null,   null,       $styles,    null,   'superScript',  true),
            'sub'       => array('Property',    null,   null,       $styles,    null,   'subScript',    true),
            'span'      => array('Span',        $node,  null,       $styles,    null,   null,           null),
            'table'     => array('Table',       $node,  $element,   $styles,    null,   null,           null),
            'tr'        => array('Row',         $node,  $element,   $styles,    null,   null,           null),
            'td'        => array('Cell',        $node,  $element,   $styles,    null,   null,           null),
            'th'        => array('Cell',        $node,  $element,   $styles,    null,   null,           null),
            'ul'        => array('List',        null,   null,       $styles,    $data,  3,              null),
            'ol'        => array('List',        null,   null,       $styles,    $data,  7,              null),
            'li'        => array('ListItem',    $node,  $element,   $styles,    $data,  null,           null),
            'img'       => array('Image',       $node,  $element,   $styles,    null,   null,           null),
            'br'        => array('LineBreak',   null,   $element,   $styles,    null,   null,           null),
        );

        $newElement = null;
        $keys = array('node', 'element', 'styles', 'data', 'argument1', 'argument2');

        if (isset($nodes[$node->nodeName])) {
            // Execute method based on node mapping table and return $newElement or null
            // Arguments are passed by reference
            $arguments = array();
            $args = array();
            list($method, $args[0], $args[1], $args[2], $args[3], $args[4], $args[5]) = $nodes[$node->nodeName];
            for ($i = 0; $i <= 5; $i++) {
                if ($args[$i] !== null) {
                    $arguments[$keys[$i]] = &$args[$i];
                }
            }
            $method = "parse{$method}";
            $newElement = call_user_func_array(array('PhpOffice\PhpWord\Shared\Html', $method), $arguments);

            // Retrieve back variables from arguments
            foreach ($keys as $key) {
                if (array_key_exists($key, $arguments)) {
                    $$key = $arguments[$key];
                }
            }
        }

        if ($newElement === null) {
            $newElement = $element;
        }

        self::parseChildNodes($node, $newElement, $styles, $data);
    }

    /**
     * Parse child nodes.
     *
     * @param \DOMNode $node
     * @param \PhpOffice\PhpWord\Element\AbstractContainer $element
     * @param array $styles
     * @param array $data
     */
    private static function parseChildNodes($node, $element, $styles, $data)
    {
        if ('li' != $node->nodeName) {
            $cNodes = $node->childNodes;
            if (!empty($cNodes)) {
                foreach ($cNodes as $cNode) {
                    if ($element instanceof AbstractContainer || $element instanceof Table || $element instanceof Row) {
                        self::parseNode($cNode, $element, $styles, $data);
                    }
                }
            }
        }
    }

    /**
     * Parse paragraph node
     *
     * @param \DOMNode $node
     * @param \PhpOffice\PhpWord\Element\AbstractContainer $element
     * @param array &$styles
     * @return \PhpOffice\PhpWord\Element\TextRun
     */
    private static function parseParagraph($node, $element, &$styles)
    {
        $styles['paragraph'] = self::recursiveParseStylesInHierarchy($node, $styles['paragraph']);
        $newElement = $element->addTextRun($styles['paragraph']);

        return $newElement;
    }

    /**
     * Parse heading node
     *
     * @param \PhpOffice\PhpWord\Element\AbstractContainer $element
     * @param array &$styles
     * @param string $argument1 Name of heading style
     * @return \PhpOffice\PhpWord\Element\TextRun
     *
     * @todo Think of a clever way of defining header styles, now it is only based on the assumption, that
     * Heading1 - Heading6 are already defined somewhere
     */
    private static function parseHeading($element, &$styles, $argument1)
    {
        $styles['paragraph'] = $argument1;
        $newElement = $element->addTextRun($styles['paragraph']);

        return $newElement;
    }

    /**
     * Parse text node
     *
     * @param \DOMNode $node
     * @param \PhpOffice\PhpWord\Element\AbstractContainer $element
     * @param array &$styles
     */
    private static function parseText($node, $element, &$styles)
    {
        $styles['font'] = self::recursiveParseStylesInHierarchy($node, $styles['font']);

        //alignment applies on paragraph, not on font. Let's copy it there
        if (isset($styles['font']['alignment'])) {
            $styles['paragraph']['alignment'] = $styles['font']['alignment'];
        }

        if (is_callable(array($element, 'addText'))) {
            $element->addText($node->nodeValue, $styles['font'], $styles['paragraph']);
        }
    }

    /**
     * Parse property node
     *
     * @param array &$styles
     * @param string $argument1 Style name
     * @param string $argument2 Style value
     */
    private static function parseProperty(&$styles, $argument1, $argument2)
    {
        $styles['font'][$argument1] = $argument2;
    }

    /**
     * Parse span node
     *
     * @param \DOMNode $node
     * @param array &$styles
     */
    private static function parseSpan($node, &$styles)
    {
        self::parseInlineStyle($node, $styles['font']);
    }

    /**
     * Parse table node
     *
     * @param \DOMNode $node
     * @param \PhpOffice\PhpWord\Element\AbstractContainer $element
     * @param array &$styles
     * @return Table $element
     *
     * @todo As soon as TableItem, RowItem and CellItem support relative width and height
     */
    private static function parseTable($node, $element, &$styles)
    {
        $elementStyles = self::parseInlineStyle($node, $styles['table']);

        $newElement = $element->addTable($elementStyles);

        // $attributes = $node->attributes;
        // if ($attributes->getNamedItem('width') !== null) {
        // $newElement->setWidth($attributes->getNamedItem('width')->value);
        // }

        // if ($attributes->getNamedItem('height') !== null) {
        // $newElement->setHeight($attributes->getNamedItem('height')->value);
        // }
        // if ($attributes->getNamedItem('width') !== null) {
        // $newElement=$element->addCell($width=$attributes->getNamedItem('width')->value);
        // }

        return $newElement;
    }

    /**
     * Parse a table row
     *
     * @param \DOMNode $node
     * @param \PhpOffice\PhpWord\Element\Table $element
     * @param array &$styles
     * @return Row $element
     */
    private static function parseRow($node, $element, &$styles)
    {
        $rowStyles = self::parseInlineStyle($node, $styles['row']);
        if ($node->parentNode->nodeName == 'thead') {
            $rowStyles['tblHeader'] = true;
        }

        return $element->addRow(null, $rowStyles);
    }

    /**
     * Parse table cell
     *
     * @param \DOMNode $node
     * @param \PhpOffice\PhpWord\Element\Table $element
     * @param array &$styles
     * @return Cell $element
     */
    private static function parseCell($node, $element, &$styles)
    {
        $cellStyles = self::recursiveParseStylesInHierarchy($node, $styles['cell']);

        $colspan = $node->getAttribute('colspan');
        if (!empty($colspan)) {
            $cellStyles['gridSpan'] = $colspan - 0;
        }

        return $element->addCell(null, $cellStyles);
    }

    /**
     * Recursively parses styles on parent nodes
     * TODO if too slow, add caching of parent nodes, !! everything is static here so watch out for concurrency !!
     *
     * @param \DOMNode $node
     * @param array &$styles
     */
    private static function recursiveParseStylesInHierarchy(\DOMNode $node, array $style)
    {
        $parentStyle = self::parseInlineStyle($node, array());
        $style = array_merge($parentStyle, $style);
        if ($node->parentNode != null && XML_ELEMENT_NODE == $node->parentNode->nodeType) {
            $style = self::recursiveParseStylesInHierarchy($node->parentNode, $style);
        }

        return $style;
    }

    /**
     * Parse list node
     *
     * @param array &$styles
     * @param array &$data
     * @param string $argument1 List type
     */
    private static function parseList(&$styles, &$data, $argument1)
    {
        if (isset($data['listdepth'])) {
            $data['listdepth']++;
        } else {
            $data['listdepth'] = 0;
        }
        $styles['list']['listType'] = $argument1;
    }

    /**
     * Parse list item node
     *
     * @param \DOMNode $node
     * @param \PhpOffice\PhpWord\Element\AbstractContainer $element
     * @param array &$styles
     * @param array $data
     *
     * @todo This function is almost the same like `parseChildNodes`. Merged?
     * @todo As soon as ListItem inherits from AbstractContainer or TextRun delete parsing part of childNodes
     */
    private static function parseListItem($node, $element, &$styles, $data)
    {
        $cNodes = $node->childNodes;
        if (!empty($cNodes)) {
            $text = '';
            foreach ($cNodes as $cNode) {
                if ($cNode->nodeName == '#text') {
                    $text = $cNode->nodeValue;
                }
            }
            //ideally we should be parsing child nodes for any style, for now just take the text
            if ('' == trim($text) && '' != trim($node->textContent)) {
                $text = trim($node->textContent);
            }
            $element->addListItem($text, $data['listdepth'], $styles['font'], $styles['list'], $styles['paragraph']);
        }
    }

    /**
     * Parse style
     *
     * @param \DOMAttr $attribute
     * @param array $styles
     * @return array
     */
    private static function parseStyle($attribute, $styles)
    {
        $properties = explode(';', trim($attribute->value, " \t\n\r\0\x0B;"));
        foreach ($properties as $property) {
            list($cKey, $cValue) = explode(':', $property, 2);
            $cValue = trim($cValue);
            switch (trim($cKey)) {
                case 'text-decoration':
                    switch ($cValue) {
                        case 'underline':
                            $styles['underline'] = 'single';
                            break;
                        case 'line-through':
                            $styles['strikethrough'] = true;
                            break;
                    }
                    break;
                case 'text-align':
                    switch ($cValue) {
                        case 'left':
                            $styles['alignment'] = Jc::START;
                            break;
                        case 'right':
                            $styles['alignment'] = Jc::END;
                            break;
                        case 'center':
                            $styles['alignment'] = Jc::CENTER;
                            break;
                        case 'justify':
                            $styles['alignment'] = Jc::BOTH;
                            break;
                    }
                    break;
                case 'font-size':
                    $styles['size'] = Converter::cssToPoint($cValue);
                    break;
                case 'font-family':
                    $cValue = array_map('trim', explode(',', $cValue));
                    $styles['name'] = ucwords($cValue[0]);
                    break;
                case 'color':
                    $styles['color'] = trim($cValue, '#');
                    break;
                case 'background-color':
                    $styles['bgColor'] = trim($cValue, '#');
                    break;
                case 'font-weight':
                    $tValue = false;
                    if (preg_match('#bold#', $cValue)) {
                        $tValue = true; // also match bolder
                    }
                    $styles['bold'] = $tValue;
                    break;
                case 'font-style':
                    $tValue = false;
                    if (preg_match('#(?:italic|oblique)#', $cValue)) {
                        $tValue = true;
                    }
                    $styles['italic'] = $tValue;
                    break;
                case 'border-color':
                    $styles['color'] = trim($cValue, '#');
                    break;
                case 'border-width':
                    $styles['borderSize'] = Converter::cssToPoint($cValue);
                    break;
                case 'border-style':
                    $styles['borderStyle'] = self::mapBorderStyle($cValue);
                    break;
                case 'width':
                    if (preg_match('/([0-9]+[a-z]+)/', $cValue, $matches)) {
                        $styles['width'] = Converter::cssToTwip($matches[1]);
                        $styles['unit'] = \PhpOffice\PhpWord\Style\Table::WIDTH_TWIP;
                    } elseif (preg_match('/([0-9]+)%/', $cValue, $matches)) {
                        $styles['width'] = $matches[1] * 50;
                        $styles['unit'] = \PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT;
                    } elseif (preg_match('/([0-9]+)/', $cValue, $matches)) {
                        $styles['width'] = $matches[1];
                        $styles['unit'] = \PhpOffice\PhpWord\Style\Table::WIDTH_AUTO;
                    }
                    break;
                case 'border':
                    if (preg_match('/([0-9]+[^0-9]*)\s+(\#[a-fA-F0-9]+)\s+([a-z]+)/', $cValue, $matches)) {
                        $styles['borderSize'] = Converter::cssToPoint($matches[1]);
                        $styles['borderColor'] = trim($matches[2], '#');
                        $styles['borderStyle'] = self::mapBorderStyle($matches[3]);
                    }
                    break;
            }
        }

        return $styles;
    }

    /**
     * Parse image node
     *
     * @param \DOMNode $node
     * @param \PhpOffice\PhpWord\Element\AbstractContainer $element
     *
     * @return \PhpOffice\PhpWord\Element\Image
     **/
    private static function parseImage($node, $element)
    {
        $style = array();
        $src = null;
        foreach ($node->attributes as $attribute) {
            switch ($attribute->name) {
                case 'src':
                    $src = $attribute->value;
                    break;
                case 'width':
                    $width = $attribute->value;
                    $style['width'] = $width;
                    break;
                case 'height':
                    $height = $attribute->value;
                    $style['height'] = $height;
                    break;
                case 'style':
                    $styleattr = explode(';', $attribute->value);
                    foreach ($styleattr as $attr) {
                        if (strpos($attr, ':')) {
                            list($k, $v) = explode(':', $attr);
                            switch ($k) {
                                case 'float':
                                    if (trim($v) == 'right') {
                                        $style['hPos'] = \PhpOffice\PhpWord\Style\Image::POS_RIGHT;
                                        $style['hPosRelTo'] = \PhpOffice\PhpWord\Style\Image::POS_RELTO_PAGE;
                                        $style['pos'] = \PhpOffice\PhpWord\Style\Image::POS_RELATIVE;
                                        $style['wrap'] = \PhpOffice\PhpWord\Style\Image::WRAP_TIGHT;
                                        $style['overlap'] = true;
                                    }
                                    if (trim($v) == 'left') {
                                        $style['hPos'] = \PhpOffice\PhpWord\Style\Image::POS_LEFT;
                                        $style['hPosRelTo'] = \PhpOffice\PhpWord\Style\Image::POS_RELTO_PAGE;
                                        $style['pos'] = \PhpOffice\PhpWord\Style\Image::POS_RELATIVE;
                                        $style['wrap'] = \PhpOffice\PhpWord\Style\Image::WRAP_TIGHT;
                                        $style['overlap'] = true;
                                    }
                                    break;
                            }
                        }
                    }
                    break;
            }
        }
        $newElement = $element->addImage($src, $style);

        return $newElement;
    }

    /**
     * Transforms a CSS border style into a word border style
     *
     * @param string $cssBorderStyle
     * @return null|string
     */
    private static function mapBorderStyle($cssBorderStyle)
    {
        switch ($cssBorderStyle) {
            case 'none':
            case 'dashed':
            case 'dotted':
            case 'double':
                return $cssBorderStyle;
            default:
                return 'single';
        }
    }

    /**
     * Parse line break
     *
     * @param \PhpOffice\PhpWord\Element\AbstractContainer $element
     */
    private static function parseLineBreak($element)
    {
        $element->addTextBreak();
    }
}
