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

/*
 * <w:fldSimple w:instr=" NUMWORDS \# "€ #.##0,00;(€ #.##0,00)" \* Arabic \* MERGEFORMAT ">
    <w:r>
    <w:rPr>
    <w:noProof/>
    </w:rPr>
    <w:t>5</w:t>
    </w:r>
    </w:fldSimple>

 */


namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Shared\String;

/**
 * Field element
 */
class Field extends AbstractElement
{
    /** @const */
    
    //self::$fieldsArray;
    protected $fieldsArray = array(
    	'PAGE'=>array(
    	   'properties'=>array(
    	       'format' => array('Arabic', 'ArabicDash', 'alphabetic', 'ALPHABETIC', 'roman', 'ROMAN'),
    	   ),
    	   'options'=>array() 
        ),
        'NUMPAGES'=>array(
    	   'properties'=>array(
    	       'format' => array('Arabic', 'ArabicDash', 'alphabetic', 'ALPHABETIC', 'roman', 'ROMAN'),
    	       'numformat' => array('0', '0,00', '#.##0', '#.##0,00', '€ #.##0,00(€ #.##0,00)', '0%', '0,00%')
    	   ),
    	   'options'=>array() 
        )
    );
    
    /**
     * Field type
     *
     * @var string
     */
    protected $type;

    /**
     * Field properties
     *
     * @var array
     */
    protected $properties = array();

    /**
     * Field options
     *
     * @var array
     */
    protected $options = array();

    /**
     * Create a new Field Element
     *
     * @param string $type
     * @param mixed $properties
     * @param mixed $options
     */
    public function __construct($type = null, $properties = array(), $options = array())
    {
        $this->setType($type);
        $this->setProperties($properties);
        $this->setOptions($options);
    }

    /**
     * Set Field type
     *
     * @param string
     * @return string
     */
    public function setType($type = null)
    {
        if (isset($type)) {
            if (array_key_exists($type, $this->fieldsArray)) {
                $this->type = $type;
            } else {
                throw new \InvalidArgumentException("Invalid type");
            }
        }
        return $this->type;
    }

    /**
     * Get Field type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set Field properties
     *
     * @param array
     * @return self
     */
    public function setProperties($properties = array())
    {
        if (is_array($properties)) {
//CREATE FUNCTION, WHICH MATCHES SUBARRAY
            
            if (array_key_exists($properties, $this->fieldsArray[$this->type])) {
                $this->properties=array_merge($this->properties, $properties);
            } else {
                throw new \InvalidArgumentException("Invalid property");
            }
        }
        return self;
    }

    /**
     * Get Field properties
     *
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * Set Field options
     *
     * @param array
     * @return self
     */
    public function setOptions($options = array())
    {
        if (is_array($options)) {
            if (array_key_exists($options, self::$fieldsArray[$this->type])) {
                $this->options=array_merge($this->options, $options);
            } else {
                throw new \InvalidArgumentException("Invalid option");
            }
        }
        return self;
    }
    
    /**
     * Get Field properties
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
    
}
