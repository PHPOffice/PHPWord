<?php
declare(strict_types=1);
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

namespace PhpOffice\PhpWord\Shared;

use DOMNode;
use PhpOffice\PhpWord\Element\AbstractContainer;
use PhpOffice\PhpWord\Element\Row;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Shared\HtmlDpi as Dpi;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\LineSpacingRule;
use PhpOffice\PhpWord\SimpleType\NumberFormat;
use PhpOffice\PhpWord\Style\BorderSide;
use PhpOffice\PhpWord\Style\BorderStyle;
use PhpOffice\PhpWord\Style\Colors\BasicColor;
use PhpOffice\PhpWord\Style\Image;
use PhpOffice\PhpWord\Style\Lengths\Absolute;
use PhpOffice\PhpWord\Style\Lengths\Length;
use PhpOffice\PhpWord\Style\Lengths\Percent;
use PhpOffice\PhpWord\Style\Paragraph;

/**
 * Common Html functions
 *
 * @SuppressWarnings(PHPMD.UnusedPrivateMethod) For readWPNode
 */
class Html
{
    protected static $listIndex = 0;
    protected static $xpath;
    protected static $options;

    /**
     * Add HTML parts.
     *
     * Note: $stylesheet parameter is removed to avoid PHPMD error for unused parameter
     * Warning: Do not pass user-generated HTML here, as that would allow an attacker to read arbitrary
     * files or perform server-side request forgery by passing local file paths or URLs in <img>.
     *
     * @param \PhpOffice\PhpWord\Element\AbstractContainer $element Where the parts need to be added
     * @param string $html The code to parse
     * @param bool $fullHTML If it's a full HTML, no need to add 'body' tag
     * @param bool $preserveWhiteSpace If false, the whitespaces between nodes will be removed
     * @param null|mixed $options
     */
    public static function addHtml($element, $html, $fullHTML = false, $preserveWhiteSpace = true, $options = null)
    {
        /*
         * @todo parse $stylesheet for default styles.  Should result in an array based on id, class and element,
         * which could be applied when such an element occurs in the parseNode function.
         */
        self::$options = $options;

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
        $orignalLibEntityLoader = libxml_disable_entity_loader(true);
        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = $preserveWhiteSpace;
        $dom->loadXML($html);
        self::$xpath = new \DOMXPath($dom);
        $node = $dom->getElementsByTagName('body');

        self::parseNode($node->item(0), $element);
        libxml_disable_entity_loader($orignalLibEntityLoader);
    }

    /**
     * parse Inline style of a node
     *
     * @param \DOMNode $node Node to check on attributes and to compile a style array
     * @param array $styles is supplied, the inline style attributes are added to the already existing style
     * @return array
     */
    protected static function parseInlineStyle($node, $styles, bool $inherited = false)
    {
        if (XML_ELEMENT_NODE == $node->nodeType) {
            $attributes = $node->attributes; // get all the attributes(eg: id, class)

            foreach ($attributes as $attribute) {
                switch ($attribute->name) {
                    case 'style':
                        $styles = self::parseStyle($node, $attribute, $styles, $inherited);
                        break;
                    case 'align':
                        $styles['alignment'] = self::mapAlign($attribute->value);
                        break;
                    case 'lang':
                        $styles['lang'] = $attribute->value;
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
            'font'      => array('Span',        $node,  null,       $styles,    null,   null,           null),
            'table'     => array('Table',       $node,  $element,   $styles,    null,   null,           null),
            'tr'        => array('Row',         $node,  $element,   $styles,    null,   null,           null),
            'td'        => array('Cell',        $node,  $element,   $styles,    null,   null,           null),
            'th'        => array('Cell',        $node,  $element,   $styles,    null,   null,           null),
            'ul'        => array('List',        $node,  $element,   $styles,    $data,  null,           null),
            'ol'        => array('List',        $node,  $element,   $styles,    $data,  null,           null),
            'li'        => array('ListItem',    $node,  $element,   $styles,    $data,  null,           null),
            'img'       => array('Image',       $node,  $element,   $styles,    null,   null,           null),
            'br'        => array('LineBreak',   null,   $element,   $styles,    null,   null,           null),
            'a'         => array('Link',        $node,  $element,   $styles,    null,   null,           null),
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

        static::parseChildNodes($node, $newElement, $styles, $data);
    }

    /**
     * Parse child nodes.
     *
     * @param \DOMNode $node
     * @param \PhpOffice\PhpWord\Element\AbstractContainer $element
     * @param array $styles
     * @param array $data
     */
    protected static function parseChildNodes($node, $element, $styles, $data)
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
    protected static function parseParagraph($node, $element, &$styles)
    {
        $styles['paragraph'] = self::recursiveParseStylesInHierarchy($node, $styles['paragraph']);

        $fontAttributes = array(
            'letter-spacing',
            'hidden',
            'underline',
            'strikethrough',
            'color',
            'bgColor',
            'bold',
            'italic',
        );
        foreach ($fontAttributes as $fontAttribute) {
            if (isset($styles['paragraph'][$fontAttribute])) {
                $styles['font'][$fontAttribute] = $styles['paragraph'][$fontAttribute];
                unset($styles['paragraph'][$fontAttribute]);
            }
        }

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
    protected static function parseHeading($element, &$styles, $argument1)
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
    protected static function parseText($node, $element, &$styles)
    {
        $styles['font'] = self::recursiveParseStylesInHierarchy($node, $styles['font']);

        //alignment applies on paragraph, not on font. Let's move it there
        if (isset($styles['font']['alignment']) && is_array($styles['paragraph'])) {
            $styles['paragraph']['alignment'] = $styles['font']['alignment'];
            unset($styles['font']['alignment']);
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
    protected static function parseProperty(&$styles, $argument1, $argument2)
    {
        $styles['font'][$argument1] = $argument2;
    }

    /**
     * Parse span node
     *
     * @param \DOMNode $node
     * @param array &$styles
     */
    protected static function parseSpan($node, &$styles)
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
    protected static function parseTable($node, $element, &$styles)
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
    protected static function parseRow($node, $element, &$styles)
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
     * @return \PhpOffice\PhpWord\Element\Cell|\PhpOffice\PhpWord\Element\TextRun $element
     */
    protected static function parseCell($node, $element, &$styles)
    {
        $cellStyles = self::recursiveParseStylesInHierarchy($node, $styles['cell']);

        $colspan = $node->getAttribute('colspan');
        if (!empty($colspan)) {
            $cellStyles['gridSpan'] = $colspan - 0;
        }
        $cell = $element->addCell(null, $cellStyles);

        if (self::shouldAddTextRun($node)) {
            return $cell->addTextRun(self::parseInlineStyle($node, $styles['paragraph'], true));
        }

        return $cell;
    }

    /**
     * Checks if $node contains an HTML element that cannot be added to TextRun
     *
     * @return bool Returns true if the node contains an HTML element that cannot be added to TextRun
     */
    protected static function shouldAddTextRun(\DOMNode $node)
    {
        $containsBlockElement = self::$xpath->query('.//table|./p|./ul|./ol', $node)->length > 0;
        if ($containsBlockElement) {
            return false;
        }

        return true;
    }

    /**
     * Recursively parses styles on parent nodes
     * TODO if too slow, add caching of parent nodes, !! everything is static here so watch out for concurrency !!
     *
     * @param array &$styles
     */
    protected static function recursiveParseStylesInHierarchy(\DOMNode $node, array $style, bool $inherited = false)
    {
        $parentStyle = self::parseInlineStyle($node, array(), $inherited);
        $style = array_merge($parentStyle, $style);
        if ($node->parentNode != null && XML_ELEMENT_NODE == $node->parentNode->nodeType) {
            $style = self::recursiveParseStylesInHierarchy($node->parentNode, $style, true);
        }

        return $style;
    }

    /**
     * Parse list node
     *
     * @param \DOMNode $node
     * @param \PhpOffice\PhpWord\Element\AbstractContainer $element
     * @param array &$styles
     * @param array &$data
     */
    protected static function parseList($node, $element, &$styles, &$data)
    {
        $isOrderedList = $node->nodeName === 'ol';
        if (isset($data['listdepth'])) {
            $data['listdepth']++;
        } else {
            $data['listdepth'] = 0;
            $styles['list'] = 'listStyle_' . self::$listIndex++;
            $element->getPhpWord()->addNumberingStyle($styles['list'], self::getListStyle($isOrderedList));
        }
        if ($node->parentNode->nodeName === 'li') {
            return $element->getParent();
        }
    }

    /**
     * @param bool $isOrderedList
     * @return array
     */
    protected static function getListStyle($isOrderedList)
    {
        if ($isOrderedList) {
            return array(
                'type'   => 'multilevel',
                'levels' => array(
                    array('format' => NumberFormat::DECIMAL,      'text' => '%1.', 'alignment' => 'left',  'tabPos' => Absolute::from('twip', 720),  'left' => Absolute::from('twip', 720),  'hanging' => Absolute::from('twip', 360)),
                    array('format' => NumberFormat::LOWER_LETTER, 'text' => '%2.', 'alignment' => 'left',  'tabPos' => Absolute::from('twip', 1440), 'left' => Absolute::from('twip', 1440), 'hanging' => Absolute::from('twip', 360)),
                    array('format' => NumberFormat::LOWER_ROMAN,  'text' => '%3.', 'alignment' => 'right', 'tabPos' => Absolute::from('twip', 2160), 'left' => Absolute::from('twip', 2160), 'hanging' => Absolute::from('twip', 180)),
                    array('format' => NumberFormat::DECIMAL,      'text' => '%4.', 'alignment' => 'left',  'tabPos' => Absolute::from('twip', 2880), 'left' => Absolute::from('twip', 2880), 'hanging' => Absolute::from('twip', 360)),
                    array('format' => NumberFormat::LOWER_LETTER, 'text' => '%5.', 'alignment' => 'left',  'tabPos' => Absolute::from('twip', 3600), 'left' => Absolute::from('twip', 3600), 'hanging' => Absolute::from('twip', 360)),
                    array('format' => NumberFormat::LOWER_ROMAN,  'text' => '%6.', 'alignment' => 'right', 'tabPos' => Absolute::from('twip', 4320), 'left' => Absolute::from('twip', 4320), 'hanging' => Absolute::from('twip', 180)),
                    array('format' => NumberFormat::DECIMAL,      'text' => '%7.', 'alignment' => 'left',  'tabPos' => Absolute::from('twip', 5040), 'left' => Absolute::from('twip', 5040), 'hanging' => Absolute::from('twip', 360)),
                    array('format' => NumberFormat::LOWER_LETTER, 'text' => '%8.', 'alignment' => 'left',  'tabPos' => Absolute::from('twip', 5760), 'left' => Absolute::from('twip', 5760), 'hanging' => Absolute::from('twip', 360)),
                    array('format' => NumberFormat::LOWER_ROMAN,  'text' => '%9.', 'alignment' => 'right', 'tabPos' => Absolute::from('twip', 6480), 'left' => Absolute::from('twip', 6480), 'hanging' => Absolute::from('twip', 180)),
                ),
            );
        }

        return array(
            'type'   => 'hybridMultilevel',
            'levels' => array(
                array('format' => NumberFormat::BULLET, 'text' => '', 'alignment' => 'left', 'tabPos' => Absolute::from('twip', 720),  'left' => Absolute::from('twip', 720),  'hanging' => Absolute::from('twip', 360), 'font' => 'Symbol',      'hint' => 'default'),
                array('format' => NumberFormat::BULLET, 'text' => 'o',  'alignment' => 'left', 'tabPos' => Absolute::from('twip', 1440), 'left' => Absolute::from('twip', 1440), 'hanging' => Absolute::from('twip', 360), 'font' => 'Courier New', 'hint' => 'default'),
                array('format' => NumberFormat::BULLET, 'text' => '', 'alignment' => 'left', 'tabPos' => Absolute::from('twip', 2160), 'left' => Absolute::from('twip', 2160), 'hanging' => Absolute::from('twip', 360), 'font' => 'Wingdings',   'hint' => 'default'),
                array('format' => NumberFormat::BULLET, 'text' => '', 'alignment' => 'left', 'tabPos' => Absolute::from('twip', 2880), 'left' => Absolute::from('twip', 2880), 'hanging' => Absolute::from('twip', 360), 'font' => 'Symbol',      'hint' => 'default'),
                array('format' => NumberFormat::BULLET, 'text' => 'o',  'alignment' => 'left', 'tabPos' => Absolute::from('twip', 3600), 'left' => Absolute::from('twip', 3600), 'hanging' => Absolute::from('twip', 360), 'font' => 'Courier New', 'hint' => 'default'),
                array('format' => NumberFormat::BULLET, 'text' => '', 'alignment' => 'left', 'tabPos' => Absolute::from('twip', 4320), 'left' => Absolute::from('twip', 4320), 'hanging' => Absolute::from('twip', 360), 'font' => 'Wingdings',   'hint' => 'default'),
                array('format' => NumberFormat::BULLET, 'text' => '', 'alignment' => 'left', 'tabPos' => Absolute::from('twip', 5040), 'left' => Absolute::from('twip', 5040), 'hanging' => Absolute::from('twip', 360), 'font' => 'Symbol',      'hint' => 'default'),
                array('format' => NumberFormat::BULLET, 'text' => 'o',  'alignment' => 'left', 'tabPos' => Absolute::from('twip', 5760), 'left' => Absolute::from('twip', 5760), 'hanging' => Absolute::from('twip', 360), 'font' => 'Courier New', 'hint' => 'default'),
                array('format' => NumberFormat::BULLET, 'text' => '', 'alignment' => 'left', 'tabPos' => Absolute::from('twip', 6480), 'left' => Absolute::from('twip', 6480), 'hanging' => Absolute::from('twip', 360), 'font' => 'Wingdings',   'hint' => 'default'),
            ),
        );
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
    protected static function parseListItem($node, $element, &$styles, $data)
    {
        $cNodes = $node->childNodes;
        if (!empty($cNodes)) {
            $listRun = $element->addListItemRun($data['listdepth'], $styles['list'], $styles['paragraph']);
            foreach ($cNodes as $cNode) {
                self::parseNode($cNode, $listRun, $styles, $data);
            }
        }
    }

    /**
     * Parse style
     *
     * @param \DOMAttr $attribute
     * @param array $styles
     * @return array
     */
    protected static function parseStyle(DOMNode $node, $attribute, $styles, bool $inherited)
    {
        $properties = explode(';', trim($attribute->value, " \t\n\r\0\x0B;"));

        foreach ($properties as $property) {
            list($property, $value) = array_pad(explode(':', $property, 2), 2, '');
            self::mapStyleDeclaration($node, trim($property), trim($value), $styles, $inherited);
        }

        return $styles;
    }

    private static function mapStyleDeclaration(DOMNode $node, string $property, string $value, array &$styles, bool $inherited)
    {
        switch ($property) {
            case 'text-decoration':
                switch ($value) {
                    case 'underline':
                        $styles['underline'] = 'single';
                        break;
                    case 'line-through':
                        $styles['strikethrough'] = true;
                        break;
                }
                break;
            case 'text-align':
                $styles['alignment'] = self::mapAlign($value);
                break;
            case 'display':
                // If `display: none` or `visibility: hidden` is set,
                // element should be hidden
                $styles['hidden'] = $value === 'none' || ($styles['hidden'] ?? false);
                break;
            case 'direction':
                $styles['rtl'] = $value === 'rtl';
                break;
            case 'font-size':
                $styles['size'] = self::cssToAbsolute($value);
                break;
            case 'font-family':
                $value = array_map('trim', explode(',', $value));
                $styles['name'] = ucwords($value[0]);
                break;
            case 'color':
                $styles['color'] = BasicColor::fromMixed(trim($value, '#'));
                break;
            case 'background-color':
                $styles['bgColor'] = BasicColor::fromMixed(trim($value, '#'));
                break;
            case 'line-height':
                $matches = array();
                if (preg_match('/([0-9]+\.?[0-9]*[a-z]+)/', $value, $matches)) {
                    //matches number with a unit, e.g. 12px, 15pt, 20mm, ...
                    $styles['spacingLineRule'] = LineSpacingRule::EXACT;
                    $styles['line-spacing'] = array('line' => self::cssToAbsolute($matches[1]));
                } elseif (preg_match('/([0-9]+\.?[0-9]*)%/', $value, $matches)) {
                    //matches percentages
                    $styles['line-height'] = new Percent((float) $matches[1]);
                } else {
                    //any other, wich is a multiplier. E.g. 1.2
                    $styles['line-height'] = new Percent($value * 100);
                }
                break;
            case 'letter-spacing':
                $styles['letter-spacing'] = self::cssToAbsolute($value);
                break;
            case 'text-indent':
                $styles['indentation']['firstLine'] = self::cssToAbsolute($value);
                break;
            case 'font-weight':
                $tValue = false;
                if (preg_match('#bold#', $value)) {
                    $tValue = true; // also match bolder
                }
                $styles['bold'] = $tValue;
                break;
            case 'font-style':
                $tValue = false;
                if (preg_match('#(?:italic|oblique)#', $value)) {
                    $tValue = true;
                }
                $styles['italic'] = $tValue;
                break;
            case 'margin-top':
                $styles['spaceBefore'] = self::cssToAbsolute($value);
                break;
            case 'margin-bottom':
                $styles['spaceAfter'] = self::cssToAbsolute($value);
                break;
            case 'border-color':
                if (!$inherited) {
                    self::mapBorderColor($node, $styles, $value);
                }
                break;
            case 'border-width':
                if (!$inherited) {
                    self::mapBorderWidth($node, $styles, $value);
                }
                break;
            case 'border-style':
                if (!$inherited) {
                    self::mapBorderStyle($node, $styles, $value);
                }
                break;
            case 'width':
                if (preg_match('/([0-9]+[a-z]+)/', $value, $matches)) {
                    $styles['width'] = self::cssToAbsolute($matches[1]);
                } elseif (preg_match('/([0-9]+)%/', $value, $matches)) {
                    $styles['width'] = Percent::fromMixed($matches[1]);
                } elseif (preg_match('/([0-9]+)/', $value, $matches)) {
                    $styles['width'] = Absolute::fromMixed('twip', $matches[1]);
                }
                break;
            case 'border':
                if (!$inherited && preg_match('/([0-9]+[^0-9]*)\s+(\#[a-fA-F0-9]+)\s+([a-z]+)/', $value, $matches)) {
                    self::mapBorderColor($node, $styles, $matches[2]);
                    self::mapBorderWidth($node, $styles, $matches[1]);
                    self::mapBorderStyle($node, $styles, $matches[3]);
                }
                break;
            case 'visibility':
                // If `display: none` or `visibility: hidden` is set,
                // element should be hidden
                $styles['hidden'] = $value === 'hidden' || ($styles['hidden'] ?? false);
                break;
        }
    }

    /**
     * Parse image node
     *
     * @param \DOMNode $node
     * @param \PhpOffice\PhpWord\Element\AbstractContainer $element
     *
     * @return \PhpOffice\PhpWord\Element\Image
     **/
    protected static function parseImage($node, $element)
    {
        $style = array();
        $src = null;
        foreach ($node->attributes as $attribute) {
            switch ($attribute->name) {
                case 'src':
                    $src = $attribute->value;
                    break;
                case 'width':
                    $style['width'] = Absolute::fromPixels(new Dpi(), (float) $attribute->value);
                    break;
                case 'height':
                    $style['height'] = Absolute::fromPixels(new Dpi(), (float) $attribute->value);
                    break;
                case 'style':
                    $styleattr = explode(';', $attribute->value);
                    foreach ($styleattr as $attr) {
                        if (strpos($attr, ':')) {
                            list($k, $v) = explode(':', $attr);
                            switch ($k) {
                                case 'float':
                                    if (trim($v) == 'right') {
                                        $style['hPos'] = Image::POS_RIGHT;
                                        $style['hPosRelTo'] = Image::POS_RELTO_PAGE;
                                        $style['pos'] = Image::POS_RELATIVE;
                                        $style['wrap'] = Image::WRAP_TIGHT;
                                        $style['overlap'] = true;
                                    }
                                    if (trim($v) == 'left') {
                                        $style['hPos'] = Image::POS_LEFT;
                                        $style['hPosRelTo'] = Image::POS_RELTO_PAGE;
                                        $style['pos'] = Image::POS_RELATIVE;
                                        $style['wrap'] = Image::WRAP_TIGHT;
                                        $style['overlap'] = true;
                                    }
                                    break;
                            }
                        }
                    }
                    break;
            }
        }
        $originSrc = $src;
        if (strpos($src, 'data:image') !== false) {
            $tmpDir = Settings::getTempDir() . '/';

            $match = array();
            preg_match('/data:image\/(\w+);base64,(.+)/', $src, $match);

            $src = $imgFile = $tmpDir . uniqid() . '.' . $match[1];

            $ifp = fopen($imgFile, 'wb');

            if ($ifp !== false) {
                fwrite($ifp, base64_decode($match[2]));
                fclose($ifp);
            }
        }
        $src = urldecode($src);

        if (!is_file($src)
            && !is_null(self::$options)
            && isset(self::$options['IMG_SRC_SEARCH'])
            && isset(self::$options['IMG_SRC_REPLACE'])) {
            $src = str_replace(self::$options['IMG_SRC_SEARCH'], self::$options['IMG_SRC_REPLACE'], $src);
        }

        if (!is_file($src)) {
            if ($imgBlob = @file_get_contents($src)) {
                $tmpDir = Settings::getTempDir() . '/';
                $match = array();
                preg_match('/.+\.(\w+)$/', $src, $match);
                $src = $tmpDir . uniqid() . '.' . $match[1];

                $ifp = fopen($src, 'wb');

                if ($ifp !== false) {
                    fwrite($ifp, $imgBlob);
                    fclose($ifp);
                }
            }
        }

        if (is_file($src)) {
            $newElement = $element->addImage($src, $style);
        } else {
            throw new \Exception("Could not load image $originSrc");
        }

        return $newElement;
    }

    /**
     * Transforms a CSS border style into a word border style
     * @param mixed $styles
     */
    protected static function mapBorderStyle(DOMNode $node, &$styles, string $cssStyles)
    {
        $cssStyles = self::expandBorderSides($node, $cssStyles);

        $mapping = array(
            'none'   => 'none',
            'hidden' => 'none',
            'dotted' => 'dotted',
            'dashed' => 'dashed',
            'solid'  => 'single',
            'double' => 'double',
            'groove' => 'threeDEngrave',
            'ridge'  => 'threeDEmboss',
            'inset'  => 'inset',
            'outset' => 'outset',
        );

        foreach ($cssStyles as $side => $cssStyle) {
            $existingBorder = self::getBorderSide($styles, $side);
            $existingBorder->setStyle(new BorderStyle($mapping[$cssStyle] ?? 'single'));
        }
    }

    /**
     * @see https://developer.mozilla.org/en-US/docs/Web/CSS/color_value
     */
    protected static $colorKeywords = array(
        'black'                => '000000',
        'silver'               => 'c0c0c0',
        'gray'                 => '808080',
        'white'                => 'ffffff',
        'maroon'               => '800000',
        'red'                  => 'ff0000',
        'purple'               => '800080',
        'fuchsia'              => 'ff00ff',
        'green'                => '008000',
        'lime'                 => '00ff00',
        'olive'                => '808000',
        'yellow'               => 'ffff00',
        'navy'                 => '000080',
        'blue'                 => '0000ff',
        'teal'                 => '008080',
        'aqua'                 => '00ffff',
        'orange'               => 'ffa500',
        'aliceblue'            => 'f0f8ff',
        'antiquewhite'         => 'faebd7',
        'aquamarine'           => '7fffd4',
        'azure'                => 'f0ffff',
        'beige'                => 'f5f5dc',
        'bisque'               => 'ffe4c4',
        'blanchedalmond'       => 'ffebcd',
        'blueviolet'           => '8a2be2',
        'brown'                => 'a52a2a',
        'burlywood'            => 'deb887',
        'cadetblue'            => '5f9ea0',
        'chartreuse'           => '7fff00',
        'chocolate'            => 'd2691e',
        'coral'                => 'ff7f50',
        'cornflowerblue'       => '6495ed',
        'cornsilk'             => 'fff8dc',
        'crimson'              => 'dc143c',
        'cyan'                 => '00ffff',
        'darkblue'             => '00008b',
        'darkcyan'             => '008b8b',
        'darkgoldenrod'        => 'b8860b',
        'darkgray'             => 'a9a9a9',
        'darkgreen'            => '006400',
        'darkgrey'             => 'a9a9a9',
        'darkkhaki'            => 'bdb76b',
        'darkmagenta'          => '8b008b',
        'darkolivegreen'       => '556b2f',
        'darkorange'           => 'ff8c00',
        'darkorchid'           => '9932cc',
        'darkred'              => '8b0000',
        'darksalmon'           => 'e9967a',
        'darkseagreen'         => '8fbc8f',
        'darkslateblue'        => '483d8b',
        'darkslategray'        => '2f4f4f',
        'darkslategrey'        => '2f4f4f',
        'darkturquoise'        => '00ced1',
        'darkviolet'           => '9400d3',
        'deeppink'             => 'ff1493',
        'deepskyblue'          => '00bfff',
        'dimgray'              => '696969',
        'dimgrey'              => '696969',
        'dodgerblue'           => '1e90ff',
        'firebrick'            => 'b22222',
        'floralwhite'          => 'fffaf0',
        'forestgreen'          => '228b22',
        'gainsboro'            => 'dcdcdc',
        'ghostwhite'           => 'f8f8ff',
        'gold'                 => 'ffd700',
        'goldenrod'            => 'daa520',
        'greenyellow'          => 'adff2f',
        'grey'                 => '808080',
        'honeydew'             => 'f0fff0',
        'hotpink'              => 'ff69b4',
        'indianred'            => 'cd5c5c',
        'indigo'               => '4b0082',
        'ivory'                => 'fffff0',
        'khaki'                => 'f0e68c',
        'lavender'             => 'e6e6fa',
        'lavenderblush'        => 'fff0f5',
        'lawngreen'            => '7cfc00',
        'lemonchiffon'         => 'fffacd',
        'lightblue'            => 'add8e6',
        'lightcoral'           => 'f08080',
        'lightcyan'            => 'e0ffff',
        'lightgoldenrodyellow' => 'fafad2',
        'lightgray'            => 'd3d3d3',
        'lightgreen'           => '90ee90',
        'lightgrey'            => 'd3d3d3',
        'lightpink'            => 'ffb6c1',
        'lightsalmon'          => 'ffa07a',
        'lightseagreen'        => '20b2aa',
        'lightskyblue'         => '87cefa',
        'lightslategray'       => '778899',
        'lightslategrey'       => '778899',
        'lightsteelblue'       => 'b0c4de',
        'lightyellow'          => 'ffffe0',
        'limegreen'            => '32cd32',
        'linen'                => 'faf0e6',
        'magenta'              => 'ff00ff',
        'mediumaquamarine'     => '66cdaa',
        'mediumblue'           => '0000cd',
        'mediumorchid'         => 'ba55d3',
        'mediumpurple'         => '9370db',
        'mediumseagreen'       => '3cb371',
        'mediumslateblue'      => '7b68ee',
        'mediumspringgreen'    => '00fa9a',
        'mediumturquoise'      => '48d1cc',
        'mediumvioletred'      => 'c71585',
        'midnightblue'         => '191970',
        'mintcream'            => 'f5fffa',
        'mistyrose'            => 'ffe4e1',
        'moccasin'             => 'ffe4b5',
        'navajowhite'          => 'ffdead',
        'oldlace'              => 'fdf5e6',
        'olivedrab'            => '6b8e23',
        'orangered'            => 'ff4500',
        'orchid'               => 'da70d6',
        'palegoldenrod'        => 'eee8aa',
        'palegreen'            => '98fb98',
        'paleturquoise'        => 'afeeee',
        'palevioletred'        => 'db7093',
        'papayawhip'           => 'ffefd5',
        'peachpuff'            => 'ffdab9',
        'peru'                 => 'cd853f',
        'pink'                 => 'ffc0cb',
        'plum'                 => 'dda0dd',
        'powderblue'           => 'b0e0e6',
        'rosybrown'            => 'bc8f8f',
        'royalblue'            => '4169e1',
        'saddlebrown'          => '8b4513',
        'salmon'               => 'fa8072',
        'sandybrown'           => 'f4a460',
        'seagreen'             => '2e8b57',
        'seashell'             => 'fff5ee',
        'sienna'               => 'a0522d',
        'skyblue'              => '87ceeb',
        'slateblue'            => '6a5acd',
        'slategray'            => '708090',
        'slategrey'            => '708090',
        'snow'                 => 'fffafa',
        'springgreen'          => '00ff7f',
        'steelblue'            => '4682b4',
        'tan'                  => 'd2b48c',
        'thistle'              => 'd8bfd8',
        'tomato'               => 'ff6347',
        'turquoise'            => '40e0d0',
        'violet'               => 'ee82ee',
        'wheat'                => 'f5deb3',
        'whitesmoke'           => 'f5f5f5',
        'yellowgreen'          => '9acd32',
        'rebeccapurple'        => '663399',
    );

    protected static function mapBorderColor(DOMNode $node, &$styles, string $cssColors)
    {
        $cssColors = self::expandBorderSides($node, $cssColors);

        foreach ($cssColors as $side => $cssColor) {
            $existingBorder = self::getBorderSide($styles, $side);
            if (array_key_exists($cssColor, self::$colorKeywords)) {
                $cssColor = self::$colorKeywords[$cssColor];
            }
            $existingBorder->setColor(BasicColor::fromMixed(ltrim($cssColor, '#')));
        }
    }

    protected static function mapBorderWidth(DOMNode $node, &$styles, string $cssSizes)
    {
        $cssSizes = self::expandBorderSides($node, $cssSizes);

        foreach ($cssSizes as $side => $cssSize) {
            $existingBorder = self::getBorderSide($styles, $side);
            $existingBorder->setSize(self::cssToAbsolute($cssSize));
        }
    }

    protected static function expandBorderSides(DOMNode $node, string $valuesString)
    {
        $sideMapping = array(
            'table' => array('top', 'end', 'bottom', 'start'),
            'th'    => array('top', 'end', 'bottom', 'start'),
            'td'    => array('top', 'end', 'bottom', 'start'),
            'p'     => array('top', 'right', 'bottom', 'left'),
        );
        if (!array_key_exists($node->nodeName, $sideMapping)) {
            trigger_error(sprintf('Node `%s` does not support borders', $node->nodeName), E_USER_WARNING);
        }

        $sides = $sideMapping[$node->nodeName];
        $values = explode(' ', $valuesString);
        if (count($values) > count($sides)) {
            trigger_error(sprintf('Provided `%s` style `%s` had more than %d values', $node->nodeName, $valuesString, count($sides)), E_USER_WARNING);
            $values = array_slice($values, 0, count($sides));
        }
        $values = array_combine(array_slice($sides, 0, count($values)), $values);
        if ($values === false) {
            throw new Exception(sprintf('Mismatch between number of items in `$sides` and `$values`. This should never happen. Provided values: `%s`, Sides: `%s`, Values: `%s`', $valuesString, serialize($sides), serialize($values)));
        }

        if (count($values) === 1) {
            $values[$sides[1]] = $values[$sides[0]];
            $values[$sides[2]] = $values[$sides[0]];
            $values[$sides[3]] = $values[$sides[0]];
        } elseif (count($values) === 2) {
            $values[$sides[2]] = $values[$sides[0]];
            $values[$sides[3]] = $values[$sides[1]];
        } elseif (count($values) === 3) {
            $values[$sides[3]] = $values[$sides[1]];
        }

        return $values;
    }

    protected static function getBorderSide(&$styles, string $side): BorderSide
    {
        if (($styles['bordersFromArray'][$side] ?? null) === null) {
            $styles['bordersFromArray'][$side] = new BorderSide();
        }

        return $styles['bordersFromArray'][$side];
    }

    /**
     * Transforms a HTML/CSS alignment into a \PhpOffice\PhpWord\SimpleType\Jc
     *
     * @param string $cssAlignment
     * @return string|null
     */
    protected static function mapAlign($cssAlignment)
    {
        switch ($cssAlignment) {
            case 'right':
                return Jc::END;
            case 'center':
                return Jc::CENTER;
            case 'justify':
                return Jc::BOTH;
            default:
                return Jc::START;
        }
    }

    /**
     * Parse line break
     *
     * @param \PhpOffice\PhpWord\Element\AbstractContainer $element
     */
    protected static function parseLineBreak($element)
    {
        $element->addTextBreak();
    }

    /**
     * Parse link node
     *
     * @param \DOMNode $node
     * @param \PhpOffice\PhpWord\Element\AbstractContainer $element
     * @param array $styles
     */
    protected static function parseLink($node, $element, &$styles)
    {
        $target = null;
        foreach ($node->attributes as $attribute) {
            switch ($attribute->name) {
                case 'href':
                    $target = $attribute->value;
                    break;
            }
        }
        $styles['font'] = self::parseInlineStyle($node, $styles['font']);

        if (strpos($target, '#') === 0) {
            return $element->addLink(substr($target, 1), $node->textContent, $styles['font'], $styles['paragraph'], true);
        }

        return $element->addLink($target, $node->textContent, $styles['font'], $styles['paragraph']);
    }

    /**
     * Transforms a size in CSS format (eg. 10px, 10cm, ...) to Length
     */
    public static function cssToLength(string $value): Length
    {
        if ($value === '0') {
            return new Absolute(0);
        }

        $matches = array();
        if (preg_match('/^[+-]?([0-9]+\.?[0-9]*)?(px|em|ex|%|in|cm|mm|pt|pc)$/i', $value, $matches)) {
            $size = (float) $matches[1];
            $unit = $matches[2];

            switch ($unit) {
                case 'pt':
                    return Absolute::from('pt', $size);
                case 'px':
                    return Absolute::fromPixels(new Dpi(), $size);
                case 'cm':
                    return Absolute::from('cm', $size);
                case 'mm':
                    return Absolute::from('mm', $size);
                case 'in':
                    return Absolute::from('in', $size);
                case 'pc':
                    return Absolute::from('pc', $size);
                case '%':
                    return new Percent($size);
                case 'em':
                    return new Percent($size * 100);
            }
        }

        return new Absolute(null);
    }

    /**
     * Transforms a size in CSS format (eg. 10px, 10cm, ...) to Absolute
     *
     * @return Absolute
     */
    public static function cssToAbsolute(string $value): Length
    {
        $size = self::cssToLength($value);
        if ($size instanceof Absolute) {
            return $size;
        }

        return new Absolute(null);
    }
}
