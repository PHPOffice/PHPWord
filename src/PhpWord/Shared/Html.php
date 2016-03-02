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

use PhpOffice\PhpWord\Element\AbstractContainer;

/**
 * Common Html functions
 *
 * @SuppressWarnings(PHPMD.UnusedPrivateMethod) For readWPNode
 */
class Html
{
	/**
	 * an array of arrays containing the loaded rules from the entire dom; 
	 * expanded by ',' in the selector and by ';' in the body of selector
	 * 
	 *	array(
	 *		...
	 *		[xx] => Array
	 *		(
	 *		   [selector] => th > div
	 *		   [style] => Array
	 *			   (
	 *				   [0] => margin:5pt
	 *				   [1] => text-align: center
	 *			   )
	 *
	 *		),
	 *		...
	 *	)
	 *	 
	 * @see \Xprt64\Css\Parser::loadRulesFromDom()
	 * @var array
	 */
	protected	static $cssRules	=	array();
	
	/**
	 *
	 * @var \Xprt64\Css\Parser
	 */
	protected	static	$cssParser;
	
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

        if ($fullHTML === false) {
            $html = '<body>' . $html . '</body>';
        }
		
        // Load DOM
        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = true;
        $dom->loadHTML($html);
		
		self::$cssParser	=	new	\Xprt64\Css\Parser($dom);
		
		self::$cssParser->loadRulesFromDom();
		
		self::$cssRules	=	self::$cssParser->getRules();
		
		$node = $dom->getElementsByTagName('body');
		
		$body	=	$node->item(0);
		
		$element->setStyle(self::parseInlineStyle($body, array(), null));
			
		self::parseNode($body, $element);
    }
	
	/**
     * parse Inline style of a node
     *
     * @param \DOMNode $node Node to check on attributes and to compile a style array
     * @param array the styles of the parent node
     * @return array
     */
    protected static function parseInlineStyle($node, $styles, $parentNode)
    {
		$has_style	=	false;
		
		if ($node->nodeType == XML_ELEMENT_NODE) {
            $attributes = $node->attributes; // get all the attributes(eg: id, class)

			
            foreach ($attributes as $attribute) {
				
				$v	=	$attribute->value;
				
                switch (strtolower($attribute->name)) {
					//style attribute (style="....")
                    case 'style':
						$styles		= self::parseStyle($attribute, $styles, $node, $parentNode);
						$has_style	= true;
                        break;
					
					//direct word attributes
                   case 'data-word':
						$word_styles_p	=	explode(';', trim($attribute->value, " \t\n\r\0\x0B;"));
						foreach($word_styles_p as $x)
						{
							list($k, $v)	=	explode(':', $x);
							$k	=	trim($k, " \t\n\r");
							$v	=	trim($v, " \t\n\r");
							
							if($k)
								$styles[$k]	=	$v;
						}
						
						$has_style	= true;
                        break;
					
					case 'align':
						if('justify' == $v)
							$v	=	'both';

						$styles["align"]	=	$v;
						break;
						
					case 'colspan':
						$styles["gridSpan"]	=	$v;
						break;
					
					case 'width':
						$styles["width"]	=	(float)($v)*0.75;
						$styles['unit']	=	'pt';
						break;
					
  					case 'height':
						$styles["height"]	=	(float)($v)*0.75;
						$styles['unit']	=	'pt';
						break;
                }
            }
        }
		
		/**
		 * no style attribute? then get inherited attributes, at least
		 */
		if(!$has_style)
			$styles = self::parseStyle(null, $styles, $node, $parentNode);
	
        return $styles;
    }

    /**
     * Parse a node and add a corresponding element to the parent element.
     *
     * @param \DOMNode $node node to parse
     * @param \PhpOffice\PhpWord\Element\AbstractContainer $element object to add an element corresponding with the node
     * @param array $styles Array with all styles
     * @param array $data Array to transport data to a next level in the DOM tree, for example level of listitems
	 * @param \DOMNode $parentNode
     * @return void
     */
    protected static function parseNode($node, $element, $styles = array(), $data = array(), $parentNode = null)
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
            'p'         => array('Paragraph',   $node,   $element,   $styles,    null,   null,           null),
            'div'       => array('Paragraph',   $node,   $element,   $styles,    null,   null,           null),
            'h1'        => array('Heading',     $node,   $element,   $styles,    null,   'Heading1',     null),
            'h2'        => array('Heading',     $node,   $element,   $styles,    null,   'Heading2',     null),
            'h3'        => array('Heading',     $node,   $element,   $styles,    null,   'Heading3',     null),
            'h4'        => array('Heading',     $node,   $element,   $styles,    null,   'Heading4',     null),
            'h5'        => array('Heading',     $node,   $element,   $styles,    null,   'Heading5',     null),
            'h6'        => array('Heading',     $node,   $element,   $styles,    null,   'Heading6',     null),
            '#text'     => array('Text',        $node,	 $element,   $styles,    null,   null,           null),
            'strong'    => array('Property',    $node,   $element,   $styles,    null,   'bold',         true),
            'b'			=> array('Property',    $node,   $element,   $styles,    null,   'bold',         true),
            'em'        => array('Property',    $node,   $element,   $styles,    null,   'italic',       true),
            'i'         => array('Property',    $node,   $element,   $styles,    null,   'italic',       true),
            'sup'       => array('Property',    $node,   $element,   $styles,    null,   'superScript',  true),
            'sub'       => array('Property',    $node,   $element,   $styles,    null,   'subScript',    true),
            'table'     => array('Table',       $node,   $element,   $styles,    null,   'addTable',     true),
            'tr'        => array('Table',       $node,   $element,   $styles,    null,   'addRow',       true),
            'td'        => array('Table',       $node,   $element,   $styles,    null,   'addCell',      true),
            'th'        => array('Table',       $node,   $element,   $styles,    null,   'addCell',      true),
            'ul'        => array('List',        $node,   $element,   $styles,    $data,  3,              null),
            'ol'        => array('List',        $node,   $element,   $styles,    $data,  7,              null),
            'li'        => array('ListItem',    $node,   $element,   $styles,    $data,  null,           null),
            'img'       => array('Image',		$node,   $element,   $styles,    $data,  null,           null),
            'header'    => array('Header',		$node,   $element,   $styles,    $data,  null,           null),
            'footer'    => array('Footer',		$node,   $element,   $styles,    $data,  null,           null),
            'pre'		=> array('Pre',			$node,   $element,   $styles,    $data,  null,           null),//preserved text
            'hr'		=> array('Hr',			$node,   $element,   $styles,    $data,  null,           null),
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
               // if ($args[$i] !== null) {
                    $arguments[$keys[$i]] = &$args[$i];
               // }
            }
			
			$arguments['parentNode']	=	$parentNode;
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

        self::parseChildNodes($node, $newElement, $styles, $data, $parentNode);
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
    private static function parseChildNodes($node, $element, $styles, $data, $parentNode)
    {
		if ($node->nodeName != 'li') {
           $cNodes = $node->childNodes;
            if ($cNodes->length > 0) {
                foreach ($cNodes as $cNode) {
					
					$parentNode	=	$node;
					
					if( $cNode->nodeName == '#text' && !trim($cNode->nodeValue))
					{
						if( $cNode->nextSibling && self::isInlineElement($cNode->nextSibling))
						{
							;//empty statement
						}
						else if( $cNode->previousSibling && !self::isInlineElement($cNode->previousSibling))
						{
							continue;//skip empty text nodes
						}
					}
					
				    if ($element instanceof AbstractContainer ) {
                       self::parseNode($cNode, $element, $styles, $data, $node);
                    }
                }
            }
        }
    }
	
	/**
	 * 
	 * @param \DOMNode $node
	 * @param \PhpOffice\PhpWord\Element\AbstractContainer $element
	 * @param array $styles
	 * @param mixed $data
	 * @param mixed $argument1
	 * @param mixed $argument2
	 * @param \DOMNode $parentNode
	 * @return \PhpOffice\PhpWord\Element\AbstractContainer
	 */
    private static function parseHeader($node, $element, $styles, $data, $argument1, $argument2, $parentNode)
    {
		$header	=	$element->addHeader();

        return $header;
    }
	
	/**
	 * 
	 * @param \DOMNode $node
	 * @param \PhpOffice\PhpWord\Element\AbstractContainer $element
	 * @param array $styles
	 * @param mixed $data
	 * @param mixed $argument1
	 * @param mixed $argument2
	 * @param \DOMNode $parentNode
	 * @return \PhpOffice\PhpWord\Element\AbstractContainer
	 */
    private static function parseFooter($node, $element, $styles, $data, $argument1, $argument2, $parentNode)
    {
		$styles = self::parseInlineStyle($node, array(), $parentNode);
		
		$footer	=	$element->addFooter();

		$footer->setStyle($styles);//@todo doesn't apply footer style, why?
	
        return $footer;
    }
	
	/**
	 * Parse preserved text
	 * @param \DOMNode $node
	 * @param \PhpOffice\PhpWord\Element\AbstractContainer $element
	 * @param array $styles
	 * @param mixed $data
	 * @param mixed $argument1
	 * @param mixed $argument2
	 * @param \DOMNode $parentNode
	 * @return \PhpOffice\PhpWord\Element\AbstractContainer
	 */
    private static function parsePre($node, $element, $styles, $data, $argument1, $argument2, $parentNode)
    {
		$styles = self::parseInlineStyle($node, null, $parentNode);

		$newElement	=	$element->addPreserveText($node->nodeValue, $styles, $styles);
		
        return $newElement;
    }
	
	/**
	 * Parse preserved text
	 * @todo Simulate a horizontal rule as table?
	 * 
	 * @param \DOMNode $node
	 * @param \PhpOffice\PhpWord\Element\AbstractContainer $element
	 * @param array $styles
	 * @param mixed $data
	 * @param mixed $argument1
	 * @param mixed $argument2
	 * @param \DOMNode $parentNode
	 * @return \PhpOffice\PhpWord\Element\AbstractContainer
	 */
    private static function parseHr($node, $element, $styles, $data, $argument1, $argument2, $parentNode)
    {
    }
	

    /**
     * Parse paragraph node
     *
	 * @param \DOMNode $node
	 * @param \PhpOffice\PhpWord\Element\AbstractContainer $element
	 * @param array $styles
	 * @param mixed $data
	 * @param mixed $argument1
	 * @param mixed $argument2
	 * @param \DOMNode $parentNode
	 * @return \PhpOffice\PhpWord\Element\AbstractContainer
     */
    private static function parseParagraph($node, $element, $styles, $data, $argument1, $argument2, $parentNode)
    {
        $styles['paragraph'] = self::parseInlineStyle($node, $styles['paragraph'], $parentNode);

        $newElement = $element->addTextRun($styles['paragraph'], $styles['paragraph']);

        return $newElement;
    }

    /**
     * Parse heading node
     *
	 * @param \DOMNode $node
	 * @param \PhpOffice\PhpWord\Element\AbstractContainer $element
	 * @param array $styles
	 * @param mixed $data
	 * @param mixed $argument1
	 * @param mixed $argument2
	 * @param \DOMNode $parentNode
	 * @return \PhpOffice\PhpWord\Element\AbstractContainer
     *
     * @todo Think of a clever way of defining header styles, now it is only based on the assumption, that
     * Heading1 - Heading6 are already defined somewhere
     */
    private static function parseHeading($node, $element, $styles, $data, $argument1, $argument2, $parentNode)
    {
		//($element, &$styles, $argument1)
        $styles['paragraph'] = $argument1;
        $newElement = $element->addTextRun($styles['paragraph']);

        return $newElement;
    }

    /**
     * Parse text node
     *
	 * @param \DOMNode $node
	 * @param \PhpOffice\PhpWord\Element\AbstractContainer $element
	 * @param array $styles
	 * @param mixed $data
	 * @param mixed $argument1
	 * @param mixed $argument2
	 * @param \DOMNode $parentNode
     * @return null
     */
    private static function parseText($node, $element, $styles, $data, $argument1, $argument2, $parentNode)
    {
	    $styles['font'] = self::parseInlineStyle($node, $styles['font'], $parentNode);
  		
        // Commented as source of bug #257. `method_exists` doesn't seems to work properly in this case.
        // @todo Find better error checking for this one
        // if (method_exists($element, 'addText')) {
			$text	=	str_replace('&', '&amp;', $node->nodeValue);
			$text	=	preg_replace('#(\s)(\s+)#ims', '$1', $text);
			//$text	=	rtrim($text);
			
            $element->addText($text, $styles['font'], $styles['paragraph']);
        // }

        return null;
    }

    /**
     * Parse image
     *
 	 * @param \DOMNode $node
	 * @param \PhpOffice\PhpWord\Element\AbstractContainer $element
	 * @param array $styles
	 * @param mixed $data
	 * @param mixed $argument1
	 * @param mixed $argument2
	 * @param \DOMNode $parentNode
     * @return null
     */
    private static function parseImage($node, $element, $styles, $data, $argument1, $argument2, $parentNode)
    {
	    $styles = self::parseInlineStyle($node, $styles, $parentNode);
   
		if(!$styles['wrap'])
			$styles['wrap']	=	'behind';

		$element->addImage(\My_Url::abs($node->getAttribute('src')), $styles);

		return null;
    }

    /**
     * Parse property node
     *
	 * @param \DOMNode $node
	 * @param \PhpOffice\PhpWord\Element\AbstractContainer $element
	 * @param array $styles
	 * @param mixed $data
	 * @param \DOMNode $parentNode
     * @param array &$styles
     * @param string $argument1 Style name
     * @param string $argument2 Style value
     * @return null
     */
    private static function parseProperty($node, $element, &$styles, $data, $argument1, $argument2, $parentNode)
    {
		$styles['font'] = self::parseInlineStyle($node, array(), $parentNode);
		
        $styles['font'][$argument1] = $argument2;
		
        return null;
    }

    /**
     * Parse table node
     *
 	 * @param \DOMNode $node
	 * @param \PhpOffice\PhpWord\Element\AbstractContainer $element
	 * @param array $styles
	 * @param mixed $data
	 * @param mixed $argument1
	 * @param mixed $argument2
	 * @param \DOMNode $parentNode
	 * @return \PhpOffice\PhpWord\Element\AbstractContainer
     */
    private static function parseTable($node, $element, $styles, $data, $argument1, $argument2, $parentNode)
    {
		$styles['paragraph'] = self::parseInlineStyle($node, $styles['paragraph'], $parentNode);
		
        $table	=	$newElement = $element->$argument1($styles['paragraph']);
		
		foreach($node->childNodes as $tr_node)
		{
			if('tr' != $tr_node->nodeName)
				continue;
			
			$row_style	=	self::parseInlineStyle($tr_node, array(), $node);
			
			$row	=	$table->addRow(null, $row_style);
			
			foreach($tr_node->childNodes as $td_node)
			{
				if('td' != $td_node->nodeName && 'th' != $td_node->nodeName)
					continue;
				
				$cell_style	=	self::parseInlineStyle($td_node, array(), $tr_node);
				
				$cell	=	$row->addCell($cell_style['width'], $cell_style);
				
				foreach($td_node->childNodes as $sub_node)
				{
					self::parseNode($sub_node, $cell, (array)$cell_style, array(), $td_node);
				}
			}
		}

        return $newElement;
    }
	
   /**
     * Parse list node
     *
	 * @param \DOMNode $node
	 * @param \PhpOffice\PhpWord\Element\AbstractContainer $element
	 * @param array $styles
	 * @param mixed $data
	 * @param mixed $argument1
	 * @param mixed $argument2
	 * @param \DOMNode $parentNode
    * @return null
     */
    private static function parseList($node, $element, $styles, $data, $argument1, $argument2, $parentNode)
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
	 * @param array $styles
	 * @param mixed $data
	 * @param mixed $argument1
	 * @param mixed $argument2
	 * @param \DOMNode $parentNode
	 * @return \PhpOffice\PhpWord\Element\AbstractContainer
     *
     * @todo This function is almost the same like `parseChildNodes`. Merged?
     * @todo As soon as ListItem inherits from AbstractContainer or TextRun delete parsing part of childNodes
     */
    private static function parseListItem($node, $element, $styles, $data, $argument1, $argument2, $parentNode)
    {
		$styles = self::parseInlineStyle($node, $styles, $parentNode);
		
        $cNodes = $node->childNodes;
        if ($cNodes->length > 0) {
            $text = '';
            foreach ($cNodes as $cNode) {
                if ($cNode->nodeName == '#text') {
                    $text = $cNode->nodeValue;
                }
            }
			
            $element->addListItem($text, $data['listdepth'], $styles, $styles, $styles);
        }

        return null;
    }

    /**
     * Parse style
     *
     * @param \DOMAttr $attribute
     * @param array $styles
	 * @param \DOMNode $parentNode
     * @return array
     */
    private static function parseStyle($attribute, $styles, $node, $parentNode)
    {
		$tagname	=	$node->nodeName;
		
		$properties	=	array();
		
		if($parentNode)
		{
			if($parentNode->computedCssStyles)
			{
				foreach($parentNode->computedCssStyles as $k => $v)
				{
					if(self::isInheritedStyleAttribute($k))
						$properties[$k]	=	$v;
				}
			}
		}
		
		$css_file_rules	=	self::$cssParser->getStylesFromCssRules($node);
		
		if($css_file_rules)
		{
			foreach($css_file_rules as $k => $v)
			{
				$properties[$k]	=	$v;
			}
		}
		$own	=	explode(';', trim($attribute->value, " \t\n\r\0\x0B;"));
		
        foreach( $own as $property)
		{
			list($k, $v)	=	explode(':', $property);
			$k	=	trim($k);
			$v	=	trim($v);
			if(!$k)
				continue;
		
			$properties[$k]	=	$v;
		}
		
		$node->computedCssStyles	=	$properties;
		
        foreach ($properties as $k => $v) {

			if('b' == $tagname || 'strong' == $tagname)
				$styles['bold']	=	true;

			switch($k)
			{
				case 'background-color':
					$styles["bgColor"]	=	self::cssColorToWordColor($v);
					break;

				case 'page-break-inside':
				case 'break-inside':
					$styles["cantSplit"]	=	(0 === stripos($v, 'avoid'));
					break;

				case 'border':
				case 'border-left':
				case 'border-right':
				case 'border-top':
				case 'border-bottom':
					list($unused, $pos)	=	explode('-', $k, 2);
					$pos	=	ucfirst($pos);

					list($b_w, $b_style, $b_c)	=	preg_split('#\s+#', $v);

					if($pos)
					{
						$styles["border{$pos}Size"]	=	$b_w * 1;
						$styles["border{$pos}Color"]=	self::cssColorToWordColor($b_c);
					}
					else
					{
						foreach(explode('|', 'Top|Right|Bottom|Left') as $pos)
						{
							$styles["border{$pos}Size"]	=	$b_w * 1;
							$styles["border{$pos}Color"]=	self::cssColorToWordColor($b_c);
						}
					}
					break;

				case 'color':
					$styles["color"]	=	self::cssColorToWordColor($v);
					break;

				case 'display':
					$styles["tblHeader"]	=	($v == 'table-header-group');
					break;

				case 'float':
					$styles["align"]	=	$v;
					break;

				case 'font-size':
					$styles["size"]	=	self::cssToPoint($v);
					break;

				case 'font-family':
					$styles["name"]	=	reset(explode(',', $v));
					break;

				case 'font-weight':

					$styles["bold"]	=	($v == 'bold' ? true: false);
					break;

				case 'font-style':
					$styles["italic"]	=	($v == 'italic');
					break;

				case 'height':
					$styles["exactHeight"]	=	self::cssToTwips($v);
					break;
				
				case 'list-style-type':
					$styles["format"]	=	lcfirst(implode('', array_map('ucfirst', explode('-', $v)) ));
					break;
				
				case 'line-height':
					$styles["lineHeight"]	=	$v;//(float)$v * 100;
					break;

				case 'left':
					$styles["left"]	=	self::cssToTwips($v);
					break;

				case 'margin-left':
				case 'margin-right':
				case 'margin-top':
				case 'margin-bottom':
					if('table' == $tagname)
					{
						list($_, $pos)	=	explode('-', $k);
						$pos	=	ucfirst($pos);
						$styles["cellMargin{$pos}"]		=	self::cssToTwips($v);
					}
					else if('p' == $tagname || 'div' == $tagname)
					{
						if('margin-left'	==	$k)
							$styles["indent"]			=	self::cssToTwips($v)/720;
						if('margin-top'	==	$k)
							$styles["spaceBefore"]		=	self::cssToTwips($v);
						if('margin-bottom'	==	$k)
							$styles["spaceAfter"]		=	self::cssToTwips($v);
					}
					else if('li' == $tagname)
					{
						if('margin-left'	==	$k)
							$styles["marginLeft"]		=	self::cssToTwips($v);
						if('margin-top'	==	$k)
							$styles["spaceBefore"]		=	self::cssToTwips($v);
						if('margin-bottom'	==	$k)
							$styles["spaceAfter"]		=	self::cssToTwips($v);
						if('margin-right'	==	$k)
							$styles["marginRight"]		=	self::cssToTwips($v);
					}
					else
					{
						if('margin-left'	==	$k)
							$styles["marginLeft"]		=	self::cssToTwips($v);
						if('margin-top'	==	$k)
							$styles["marginTop"]		=	self::cssToTwips($v);
						if('margin-bottom'	==	$k)
							$styles["marginBottom"]		=	self::cssToTwips($v);
						if('margin-right'	==	$k)
							$styles["marginRight"]		=	self::cssToTwips($v);
					}
					break;

				case 'margin':
					list($top, $right, $bottom, $left)	=	preg_split('#\s+#ims', $v);

					if(null === $left)
					{
						if(null === $bottom)
						{
							if(null === $right)
							{
								$right	=	$bottom	=	$left	=	$top;
							}
							else
							{
								$bottom	=	$top;
								$left	=	$right;
							}
						}
						else
						{
							$left	=	$right;
						}
					}
					if('table' == $tagname)
					{
						$styles["cellMarginTop"]		=	self::cssToTwips($top);
						$styles["cellMarginRight"]		=	self::cssToTwips($right);
						$styles["cellMarginBottom"]		=	self::cssToTwips($bottom);
						$styles["cellMarginLeft"]		=	self::cssToTwips($left);
					}
					elseif('p' == $tagname || 'div' == $tagname)
					{
						$styles["spaceBefore"]		=	self::cssToTwips($top);
						$styles["spaceAfter"]		=	self::cssToTwips($bottom);
						$styles["indent"]			=	self::cssToTwips($left)/720;
					}
					elseif('li' == $tagname)
					{
						$styles["spaceBefore"]		=	self::cssToTwips($top);
						$styles["spaceAfter"]		=	self::cssToTwips($bottom);
						$styles["left"]		=	self::cssToTwips($left);
						$styles["marginRight"]		=	self::cssToTwips($right);
					}
					else
					{
						$styles["marginTop"]		=	self::cssToTwips($top);
						$styles["marginRight"]		=	self::cssToTwips($right);
						$styles["marginBottom"]		=	self::cssToTwips($bottom);
						$styles["marginLeft"]		=	self::cssToTwips($left);
					}
					break;

				case 'page-break-inside':
					$styles["cantSplit"]	=	($v == 'avoid');
					break;

				case 'padding-bottom':
					$styles["spaceAfter"]	=	self::cssToTwips($v);
					break;

				case 'position':
					if('img' == $tagname)
					{
						$styles["pos"]	=	$v;
						$styles["hPos"]	=	$v;
						$styles["vPos"]	=	$v;
					}
					break;

				case 'text-decoration':
					$styles["underline"]		=	($v == 'underline');
					$styles["strikethrough"]	=	($v == 'line-through');
					break;

				case 'text-align':
					if('justify' == $v)
						$v	=	'both';
					$styles["align"]	=	$v;
					break;

				case 'text-transform':
					$styles["allCaps"]		=	($v == 'uppercase');
					$styles["smallCaps"]	=	($v == 'lowercase');
					break;

				case 'text-indent':
					if('li' == $tagname)
						$styles["left"]		=	self::cssToTwips($v);
					else
						$styles["indent"]	=	self::cssToTwips($v)/720;
					break;

				case 'top':
						$styles["top"]	=	self::cssToTwips($v);
					break;

				case 'vertical-align':
					if('middle' == $v)
						$v	=	'center';

					$styles["valign"]		=	$v;
					break;

				case 'width':
					if('table' == $tagname)
					{
						if(preg_match('#\%$#ims', $v))
						{
							$styles["width"]	=	100*50;
							$styles['unit']	=	\PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT;
						}
						else
						{
							$styles["width"]	= self::cssToTwips((float)$v);
							$styles['unit']	=	\PhpOffice\PhpWord\Style\Table::WIDTH_TWIP;
						}
					}
					elseif('td' == $tagname || 'th' == $tagname)
					{
						$styles["width"]	=	self::cssToTwips($v);
						$styles['unit']	=	\PhpOffice\PhpWord\Style\Table::WIDTH_TWIP;
					}
					break;

				case 'wrap':
					$styles["wrap"]		=	$v;
					break;
			}
        }

        return $styles;
    }
	
	/**
	 * Returns true if this is an inheritable css property
	 * 
	 * @param string $name
	 * @return boolean
	 */
	public static	function isInheritedStyleAttribute($name)
	{
		return	in_array($name, array( 
			'azimuth', 
			'border-collapse', 'border-spacing',
			'caption-side', 'color', 'cursor', 
			'direction',
			'elevation', 'empty-cells',
			'font-family', 'font-size', 'font-style', 'font-variant', 'font-weight', 'font',
			'letter-spacing', 'line-height', 'list-style-image', 'list-style-position', 'list-style-type', 'list-style',
			'orphans',
			'pitch-range', 'pitch',
			'quotes',
			'richness',
			'speak-header', 'speak-numeral', 'speak-punctuation', 'speak', 'speak-rate', 'stress',
			'text-align', 'text-indent', 'text-transform',
			'visibility', 'voice-family', 'volume',
			'white-space', 'widows', 'word-spacing',
		));
	}
	/**
	 * Returns true if $node is an inline css element
	 * @param type $node
	 * @return boolean
	 */
	public static function isInlineElement($node)
	{
		$tag	=	$node->nodeName;
		
		return in_array($tag, array('b', 'em', 'strong', 'i', 'span', '#text',));
	}	

	/**
	 * Transform from a css value to em
	 * @param string $css_px
	 * @return float
	 */
	static	function cssToEm($css_px)
	{
		if(preg_match('#em$#', $css_px))
			return	(float)$css_px;
		
		else if(preg_match('#px$#', $css_px))
			return	((float)$css_px)/12;

		return	(float)$css_px;
	}

	/**
	 * Transform from a css value to point
	 * @param string $css_px
	 * @return float
	 */
	static	function cssToPoint($css_px)
	{
		if(preg_match('#em$#', $css_px))
			return	(float)$css_px*12;
		
		else if(preg_match('#px$#', $css_px))
			return	((float)$css_px)*0.75;

		return	(float)$css_px;
	}

	/**
	 * Transform from a css value to twips
	 * @param string $css_px
	 * @return float
	 */
	static	function cssToTwips($css_px)
	{
		if(preg_match('#em$#', $css_px))
			return	(float)$css_px/12*15;
		
		else if(preg_match('#px$#', $css_px))
			return	((float)$css_px)*15;
		
		else if(preg_match('#pt$#', $css_px))
			return	((float)$css_px)*20;

		return	(float)$css_px;
	}

	/**
	 * Transform css color to word color
	 * @param string $css_color
	 * @return string
	 */
	static	function cssColorToWordColor($css_color)
	{
		if(preg_match('/#([a-f0-9]{6})/ims', $css_color, $m))
		{
			return $m[1];
		}
		else if(preg_match('#rgb\((\d+),\s*(\d+),\s*(\d+)\)#', $m))
		{
			return	str_pad(base_convert($m[1], 10, 16), 2, '0', 'STR_PAD_RIGHT')
				.	str_pad(base_convert($m[2], 10, 16), 2, '0', 'STR_PAD_RIGHT')
				.	str_pad(base_convert($m[3], 10, 16), 2, '0', 'STR_PAD_RIGHT');
		}
		else
			return $css_color;
	}	
}
