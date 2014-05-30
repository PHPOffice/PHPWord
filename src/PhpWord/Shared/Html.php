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

namespace PhpOffice\PhpWord\Shared;

/**
 * Common Html functions
 */
class Html
{
    /**
     * Add HTML parts
     *
     * Note: $stylesheet parameter is removed to avoid PHPMD error for unused parameter
     *
     * @param \PhpOffice\PhpWord\Element\AbstractContainer $object Where the parts need to be added
     * @param string $html the code to parse
     */
    public static function addHtml($object, $html)
    {
        /*
         * @todo parse $stylesheet for default styles.  Should result in an array based on id, class and element,
         * which could be applied when such an element occurs in the parseNode function.
         */
        $html = str_replace(array("\n", "\r"), '', $html);

        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = true;
        $dom->loadXML('<body>' . html_entity_decode($html) . '</body>');

        $node = $dom->getElementsByTagName('body');

        self::parseNode($node->item(0), $object);
    }

    /**
     * parse Inline style of a node
     *
     * @param \DOMNode $node Node to check on attributes and to compile a style array
     * @param array $style is supplied, the inline style attributes are added to the already existing style
     * @return array
     */
    protected static function parseInlineStyle($node, $style = array())
    {
        if ($node->nodeType == XML_ELEMENT_NODE) {
            $attributes = $node->attributes; // get all the attributes(eg: id, class)

            foreach ($attributes as $attribute) {
                switch ($attribute->name) {
                    case 'style':
                        $properties = explode(';', trim($attribute->value, " \t\n\r\0\x0B;"));
                        foreach ($properties as $property) {
                            list ($cKey, $cValue) = explode(':', $property, 2);
                            $cValue = trim($cValue);
                            switch (trim($cKey)) {
                                case 'text-decoration':
                                    switch ($cValue) {
                                        case 'underline':
                                            $style['underline'] = 'single';
                                            break;
                                        case 'line-through':
                                            $style['strikethrough'] = true;
                                            break;
                                    }
                                    break;
                                case 'text-align':
                                    $style['align'] = $cValue;
                                    break;
                                case 'color':
                                    $style['color'] = trim($cValue, "#");
                                    break;
                                case 'background-color':
                                    $style['bgColor'] = trim($cValue, "#");
                                    break;
                            }
                        }
                        break;
                }
            }
        }

        return $style;
    }

    /**
     * parse a node and add a corresponding element to the object
     *
     * @param \DOMNode $node node to parse
     * @param \PhpOffice\PhpWord\Element\AbstractContainer $object object to add an element corresponding with the node
     * @param array $styles Array with all styles
     * @param array $data Array to transport data to a next level in the DOM tree, for example level of listitems
     */
    protected static function parseNode(
        $node,
        $object,
        $styles = array('fontStyle' => array(), 'paragraphStyle' => array(), 'listStyle' => array()),
        $data = array()
    ) {
        $newobject = null;
        switch ($node->nodeName) {
            case 'p':
                $styles['paragraphStyle'] = self::parseInlineStyle($node, $styles['paragraphStyle']);
                $newobject = $object->addTextRun($styles['paragraphStyle']);
                break;

            /**
             * @todo Think of a clever way of defining header styles, now it is only based on the assumption, that
             * Heading1 - Heading6 are already defined somewhere
             */
            case 'h1':
                $styles['paragraphStyle'] = 'Heading1';
                $newobject = $object->addTextRun($styles['paragraphStyle']);
                break;
            case 'h2':
                $styles['paragraphStyle'] = 'Heading2';
                $newobject = $object->addTextRun($styles['paragraphStyle']);
                break;
            case 'h3':
                $styles['paragraphStyle'] = 'Heading3';
                $newobject = $object->addTextRun($styles['paragraphStyle']);
                break;
            case 'h4':
                $styles['paragraphStyle'] = 'Heading4';
                $newobject = $object->addTextRun($styles['paragraphStyle']);
                break;
            case 'h5':
                $styles['paragraphStyle'] = 'Heading5';
                $newobject = $object->addTextRun($styles['paragraphStyle']);
                break;
            case 'h6':
                $styles['paragraphStyle'] = 'Heading6';
                $newobject = $object->addTextRun($styles['paragraphStyle']);
                break;
            case '#text':
                $styles['fontStyle'] = self::parseInlineStyle($node, $styles['fontStyle']);
                if (method_exists($object, 'addText')) {
                    $object->addText($node->nodeValue, $styles['fontStyle'], $styles['paragraphStyle']);
                }
                break;
            case 'strong':
                $styles['fontStyle']['bold'] = true;
                break;
            case 'em':
                $styles['fontStyle']['italic'] = true;
                break;
            case 'sup':
                $styles['fontStyle']['superScript'] = true;
                break;
            case 'sub':
                $styles['fontStyle']['subScript'] = true;
                break;

            /**
             * @todo As soon as TableItem, RowItem and CellItem support relative width and height
             */
            case 'table':
                $styles['paragraphStyle'] = self::parseInlineStyle($node, $styles['paragraphStyle']);
                $newobject = $object->addTable();
                // if ($attributes->getNamedItem('width') !== null) {
                    // $newobject->setWidth($attributes->getNamedItem('width')->value);
                // }
                break;
            case 'tr':
                /** @var \PhpOffice\PhpWord\Element\Table $object Type hint */
                $styles['paragraphStyle'] = self::parseInlineStyle($node, $styles['paragraphStyle']);
                $newobject = $object->addRow();
                // if ($attributes->getNamedItem('height') !== null) {
                    // $newobject->setHeight($attributes->getNamedItem('height')->value);
                // }
                break;
            case 'td':
                /** @var \PhpOffice\PhpWord\Element\Row $object Type hint */
                $styles['paragraphStyle'] = self::parseInlineStyle($node, $styles['paragraphStyle']);
                // if ($attributes->getNamedItem('width') !== null) {
                    // $newobject=$object->addCell($width=$attributes->getNamedItem('width')->value);
                // } else {
                    // $newobject=$object->addCell();
                // }
                $newobject = $object->addCell();
                break;
            case 'ul':
                if (isset($data['listdepth'])) {
                    $data['listdepth'] ++;
                } else {
                    $data['listdepth'] = 0;
                }
                $styles['listStyle']['listType'] = 3; // TYPE_BULLET_FILLED = 3;
                break;
            case 'ol':
                if (isset($data['listdepth'])) {
                    $data['listdepth'] ++;
                } else {
                    $data['listdepth'] = 0;
                }
                $styles['listStyle']['listType'] = 7; // TYPE_NUMBER = 7;
                break;

            /**
             * @todo As soon as ListItem inherits from AbstractContainer or TextRun delete parsing part of childNodes
             */
            case 'li':
                $cNodes = $node->childNodes;
                if (count($cNodes) > 0) {
                    $text = '';
                    foreach ($cNodes as $cNode) {
                        if ($cNode->nodeName == '#text') {
                            $text = $cNode->nodeValue;
                        }
                    }
                    $object->addListItem(
                        $text,
                        $data['listdepth'],
                        $styles['fontStyle'],
                        $styles['listStyle'],
                        $styles['paragraphStyle']
                    );
                }
        }

        if ($newobject === null) {
            $newobject = $object;
        }

        /**
         * @todo As soon as ListItem inherits from AbstractContainer or TextRun delete condition
         */
        if ($node->nodeName != 'li') {
            $cNodes = $node->childNodes;
            if (count($cNodes) > 0) {
                foreach ($cNodes as $cNode) {
                    if ($newobject instanceof \PhpOffice\PhpWord\Element\AbstractContainer) {
                        self::parseNode($cNode, $newobject, $styles, $data);
                    }
                }
            }
        }
    }
}
