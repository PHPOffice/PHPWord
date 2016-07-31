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
 * @copyright   2010-2016 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Shared;

use PhpOffice\PhpWord\Element\AbstractContainer;

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
     * @return void
     */
    public static function addHtml($element, $html, $fullHTML = false)
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
        $dom->preserveWhiteSpace = true;
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
     * @return void
     */
    protected static function parseNode($node, $element, $styles = array(), $data = array())
    {
        // Populate styles array
        $styleTypes = array('font', 'paragraph', 'list');
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
            'em'        => array('Property',    null,   null,       $styles,    null,   'italic',       true),
            'sup'       => array('Property',    null,   null,       $styles,    null,   'superScript',  true),
            'sub'       => array('Property',    null,   null,       $styles,    null,   'subScript',    true),
            'table'     => array('Table',       $node,  $element,   $styles,    null,   'addTable',     true),
            'tr'        => array('Table',       $node,  $element,   $styles,    null,   'addRow',       true),
            'td'        => array('Table',       $node,  $element,   $styles,    null,   'addCell',      true),
            'ul'        => array('List',        null,   null,       $styles,    $data,  3,              null),
            'ol'        => array('List',        null,   null,       $styles,    $data,  7,              null),
            'li'        => array('ListItem',    $node,  $element,   $styles,    $data,  null,           null),
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
     * @return void
     */
    private static function parseChildNodes($node, $element, $styles, $data)
    {
        if ('li' != $node->nodeName) {
            $cNodes = $node->childNodes;
            if (count($cNodes) > 0) {
                foreach ($cNodes as $cNode) {
                    if ($element instanceof AbstractContainer) {
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
        $styles['paragraph'] = self::parseInlineStyle($node, $styles['paragraph']);
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
     * @return null
     */
    private static function parseText($node, $element, &$styles)
    {
        $styles['font'] = self::parseInlineStyle($node, $styles['font']);

        // Commented as source of bug #257. `method_exists` doesn't seems to work properly in this case.
        // @todo Find better error checking for this one
        // if (method_exists($element, 'addText')) {
            $element->addText($node->nodeValue, $styles['font'], $styles['paragraph']);
        // }

        return null;
    }

    /**
     * Parse property node
     *
     * @param array &$styles
     * @param string $argument1 Style name
     * @param string $argument2 Style value
     * @return null
     */
    private static function parseProperty(&$styles, $argument1, $argument2)
    {
        $styles['font'][$argument1] = $argument2;

        return null;
    }

    /**
     * Parse table node
     *
     * @param \DOMNode $node
     * @param \PhpOffice\PhpWord\Element\AbstractContainer $element
     * @param array &$styles
     * @param string $argument1 Method name
     * @return \PhpOffice\PhpWord\Element\AbstractContainer $element
     *
     * @todo As soon as TableItem, RowItem and CellItem support relative width and height
     */
    private static function parseTable($node, $element, &$styles, $argument1)
    {
        $styles['paragraph'] = self::parseInlineStyle($node, $styles['paragraph']);

        $newElement = $element->$argument1();

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
     * Parse list node
     *
     * @param array &$styles
     * @param array &$data
     * @param string $argument1 List type
     * @return null
     */
    private static function parseList(&$styles, &$data, $argument1)
    {
        if (isset($data['listdepth'])) {
            $data['listdepth']++;
        } else {
            $data['listdepth'] = 0;
        }
        $styles['list']['listType'] = $argument1;

        return null;
    }

    /**
     * Parse list item node
     *
     * @param \DOMNode $node
     * @param \PhpOffice\PhpWord\Element\AbstractContainer $element
     * @param array &$styles
     * @param array $data
     * @return null
     *
     * @todo This function is almost the same like `parseChildNodes`. Merged?
     * @todo As soon as ListItem inherits from AbstractContainer or TextRun delete parsing part of childNodes
     */
    private static function parseListItem($node, $element, &$styles, $data)
    {
        $cNodes = $node->childNodes;
        if (count($cNodes) > 0) {
            $text = '';
            foreach ($cNodes as $cNode) {
                if ($cNode->nodeName == '#text') {
                    $text = $cNode->nodeValue;
                }
            }
            $element->addListItem($text, $data['listdepth'], $styles['font'], $styles['list'], $styles['paragraph']);
        }

        return null;
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
                    $styles['alignment'] = $cValue; // todo: any mapping?
                    break;
                case 'color':
                    $styles['color'] = trim($cValue, "#");
                    break;
                case 'background-color':
                    $styles['bgColor'] = trim($cValue, "#");
                    break;
            }
        }

        return $styles;
    }
}
