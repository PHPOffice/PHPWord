<?php
/**
 * PHPWord
 *
 * Copyright (c) 2014 PHPWord
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPWord
 * @package    PHPWord
 * @copyright  Copyright (c) 2014 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.8.0
 */

/**
 * Class PHPWord_DocumentProperties
 */
class PHPWord_DocumentProperties
{
    /** Constants */
    const PROPERTY_TYPE_BOOLEAN = 'b';
    const PROPERTY_TYPE_INTEGER = 'i';
    const PROPERTY_TYPE_FLOAT = 'f';
    const PROPERTY_TYPE_DATE = 'd';
    const PROPERTY_TYPE_STRING = 's';
    const PROPERTY_TYPE_UNKNOWN = 'u';

    /**
     * Creator
     *
     * @var string
     */
    private $_creator;

    /**
     * LastModifiedBy
     *
     * @var string
     */
    private $_lastModifiedBy;

    /**
     * Created
     *
     * @var datetime
     */
    private $_created;

    /**
     * Modified
     *
     * @var datetime
     */
    private $_modified;

    /**
     * Title
     *
     * @var string
     */
    private $_title;

    /**
     * Description
     *
     * @var string
     */
    private $_description;

    /**
     * Subject
     *
     * @var string
     */
    private $_subject;

    /**
     * Keywords
     *
     * @var string
     */
    private $_keywords;

    /**
     * Category
     *
     * @var string
     */
    private $_category;

    /**
     * Company
     *
     * @var string
     */
    private $_company;

    /**
     * Manager
     *
     * @var string
     */
    private $_manager;

    /**
     * Custom Properties
     *
     * @var string
     */
    private $_customProperties = array();

    /**
     * Create new PHPWord_DocumentProperties
     */
    public function __construct()
    {
        $this->_creator        = '';
        $this->_lastModifiedBy = $this->_creator;
        $this->_created        = time();
        $this->_modified       = time();
        $this->_title          = '';
        $this->_subject        = '';
        $this->_description    = '';
        $this->_keywords       = '';
        $this->_category       = '';
        $this->_company        = '';
        $this->_manager        = '';
    }

    /**
     * Get Creator
     *
     * @return string
     */
    public function getCreator()
    {
        return $this->_creator;
    }

    /**
     * Set Creator
     *
     * @param string $pValue
     * @return PHPWord_DocumentProperties
     */
    public function setCreator($pValue = '')
    {
        $this->_creator = $pValue;
        return $this;
    }

    /**
     * Get Last Modified By
     *
     * @return string
     */
    public function getLastModifiedBy()
    {
        return $this->_lastModifiedBy;
    }

    /**
     * Set Last Modified By
     *
     * @param string $pValue
     * @return PHPWord_DocumentProperties
     */
    public function setLastModifiedBy($pValue = '')
    {
        $this->_lastModifiedBy = $pValue;
        return $this;
    }

    /**
     * Get Created
     *
     * @return datetime
     */
    public function getCreated()
    {
        return $this->_created;
    }

    /**
     * Set Created
     *
     * @param datetime $pValue
     * @return PHPWord_DocumentProperties
     */
    public function setCreated($pValue = null)
    {
        if (is_null($pValue)) {
            $pValue = time();
        }
        $this->_created = $pValue;
        return $this;
    }

    /**
     * Get Modified
     *
     * @return datetime
     */
    public function getModified()
    {
        return $this->_modified;
    }

    /**
     * Set Modified
     *
     * @param datetime $pValue
     * @return PHPWord_DocumentProperties
     */
    public function setModified($pValue = null)
    {
        if (is_null($pValue)) {
            $pValue = time();
        }
        $this->_modified = $pValue;
        return $this;
    }

    /**
     * Get Title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Set Title
     *
     * @param string $pValue
     * @return PHPWord_DocumentProperties
     */
    public function setTitle($pValue = '')
    {
        $this->_title = $pValue;
        return $this;
    }

    /**
     * Get Description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * Set Description
     *
     * @param string $pValue
     * @return PHPWord_DocumentProperties
     */
    public function setDescription($pValue = '')
    {
        $this->_description = $pValue;
        return $this;
    }

    /**
     * Get Subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->_subject;
    }

    /**
     * Set Subject
     *
     * @param string $pValue
     * @return PHPWord_DocumentProperties
     */
    public function setSubject($pValue = '')
    {
        $this->_subject = $pValue;
        return $this;
    }

    /**
     * Get Keywords
     *
     * @return string
     */
    public function getKeywords()
    {
        return $this->_keywords;
    }

    /**
     * Set Keywords
     *
     * @param string $pValue
     * @return PHPWord_DocumentProperties
     */
    public function setKeywords($pValue = '')
    {
        $this->_keywords = $pValue;
        return $this;
    }

    /**
     * Get Category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->_category;
    }

    /**
     * Set Category
     *
     * @param string $pValue
     * @return PHPWord_DocumentProperties
     */
    public function setCategory($pValue = '')
    {
        $this->_category = $pValue;
        return $this;
    }

    /**
     * Get Company
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->_company;
    }

    /**
     * Set Company
     *
     * @param string $pValue
     * @return PHPWord_DocumentProperties
     */
    public function setCompany($pValue = '')
    {
        $this->_company = $pValue;
        return $this;
    }

    /**
     * Get Manager
     *
     * @return string
     */
    public function getManager()
    {
        return $this->_manager;
    }

    /**
     * Set Manager
     *
     * @param string $pValue
     * @return PHPExcel_DocumentProperties
     */
    public function setManager($pValue = '')
    {
        $this->_manager = $pValue;
        return $this;
    }

    /**
     * Get a List of Custom Property Names
     *
     * @return array of string
     */
    public function getCustomProperties()
    {
        return array_keys($this->_customProperties);
    }

    /**
     * Check if a Custom Property is defined
     *
     * @param string $propertyName
     * @return boolean
     */
    public function isCustomPropertySet($propertyName)
    {
        return isset($this->_customProperties[$propertyName]);
    }

    /**
     * Get a Custom Property Value
     *
     * @param string $propertyName
     * @return string
     */
    public function getCustomPropertyValue($propertyName)
    {
        if (isset($this->_customProperties[$propertyName])) {
            return $this->_customProperties[$propertyName]['value'];
        }

    }

    /**
     * Get a Custom Property Type
     *
     * @param string $propertyName
     * @return string
     */
    public function getCustomPropertyType($propertyName)
    {
        if (isset($this->_customProperties[$propertyName])) {
            return $this->_customProperties[$propertyName]['type'];
        }

    }

    /**
     * Set a Custom Property
     *
     * @param string $propertyName
     * @param mixed $propertyValue
     * @param string $propertyType
     *   'i': Integer
     *   'f': Floating Point
     *   's': String
     *   'd': Date/Time
     *   'b': Boolean
     * @return PHPExcel_DocumentProperties
     */
    public function setCustomProperty($propertyName, $propertyValue = '', $propertyType = null)
    {
        if (($propertyType === null) || (!in_array($propertyType, array(
            self::PROPERTY_TYPE_INTEGER,
            self::PROPERTY_TYPE_FLOAT,
            self::PROPERTY_TYPE_STRING,
            self::PROPERTY_TYPE_DATE,
            self::PROPERTY_TYPE_BOOLEAN
        )))) {
            if ($propertyValue === null) {
                $propertyType = self::PROPERTY_TYPE_STRING;
            } elseif (is_float($propertyValue)) {
                $propertyType = self::PROPERTY_TYPE_FLOAT;
            } elseif (is_int($propertyValue)) {
                $propertyType = self::PROPERTY_TYPE_INTEGER;
            } elseif (is_bool($propertyValue)) {
                $propertyType = self::PROPERTY_TYPE_BOOLEAN;
            } else {
                $propertyType = self::PROPERTY_TYPE_STRING;
            }
        }

        $this->_customProperties[$propertyName] = array(
            'value' => $propertyValue,
            'type' => $propertyType
        );
        return $this;
    }

    /**
     * Convert document propery based on type
     *
     * @param   mixed   $propertyValue
     * @param   string  $propertyType
     * @return  mixed
     */
    public static function convertProperty($propertyValue, $propertyType)
    {
        switch ($propertyType) {
            case 'empty': //    Empty
                return '';
                break;
            case 'null': //    Null
                return null;
                break;
            case 'i1': //    1-Byte Signed Integer
            case 'i2': //    2-Byte Signed Integer
            case 'i4': //    4-Byte Signed Integer
            case 'i8': //    8-Byte Signed Integer
            case 'int': //    Integer
                return (int) $propertyValue;
                break;
            case 'ui1': //    1-Byte Unsigned Integer
            case 'ui2': //    2-Byte Unsigned Integer
            case 'ui4': //    4-Byte Unsigned Integer
            case 'ui8': //    8-Byte Unsigned Integer
            case 'uint': //    Unsigned Integer
                return abs((int) $propertyValue);
                break;
            case 'r4': //    4-Byte Real Number
            case 'r8': //    8-Byte Real Number
            case 'decimal': //    Decimal
                return (float) $propertyValue;
                break;
            case 'lpstr': //    LPSTR
            case 'lpwstr': //    LPWSTR
            case 'bstr': //    Basic String
                return $propertyValue;
                break;
            case 'date': //    Date and Time
            case 'filetime': //    File Time
                return strtotime($propertyValue);
                break;
            case 'bool': //    Boolean
                return ($propertyValue == 'true') ? true : false;
                break;
            case 'cy': //    Currency
            case 'error': //    Error Status Code
            case 'vector': //    Vector
            case 'array': //    Array
            case 'blob': //    Binary Blob
            case 'oblob': //    Binary Blob Object
            case 'stream': //    Binary Stream
            case 'ostream': //    Binary Stream Object
            case 'storage': //    Binary Storage
            case 'ostorage': //    Binary Storage Object
            case 'vstream': //    Binary Versioned Stream
            case 'clsid': //    Class ID
            case 'cf': //    Clipboard Data
                return $propertyValue;
                break;
        }

        return $propertyValue;
    }

    /**
     * Convert document property type
     *
     * @param   string  $propertyType
     * @return  mixed
     */
    public static function convertPropertyType($propertyType)
    {
        switch ($propertyType) {
            case 'i1': //    1-Byte Signed Integer
            case 'i2': //    2-Byte Signed Integer
            case 'i4': //    4-Byte Signed Integer
            case 'i8': //    8-Byte Signed Integer
            case 'int': //    Integer
            case 'ui1': //    1-Byte Unsigned Integer
            case 'ui2': //    2-Byte Unsigned Integer
            case 'ui4': //    4-Byte Unsigned Integer
            case 'ui8': //    8-Byte Unsigned Integer
            case 'uint': //    Unsigned Integer
                return self::PROPERTY_TYPE_INTEGER;
                break;
            case 'r4': //    4-Byte Real Number
            case 'r8': //    8-Byte Real Number
            case 'decimal': //    Decimal
                return self::PROPERTY_TYPE_FLOAT;
                break;
            case 'empty': //    Empty
            case 'null': //    Null
            case 'lpstr': //    LPSTR
            case 'lpwstr': //    LPWSTR
            case 'bstr': //    Basic String
                return self::PROPERTY_TYPE_STRING;
                break;
            case 'date': //    Date and Time
            case 'filetime': //    File Time
                return self::PROPERTY_TYPE_DATE;
                break;
            case 'bool': //    Boolean
                return self::PROPERTY_TYPE_BOOLEAN;
                break;
            case 'cy': //    Currency
            case 'error': //    Error Status Code
            case 'vector': //    Vector
            case 'array': //    Array
            case 'blob': //    Binary Blob
            case 'oblob': //    Binary Blob Object
            case 'stream': //    Binary Stream
            case 'ostream': //    Binary Stream Object
            case 'storage': //    Binary Storage
            case 'ostorage': //    Binary Storage Object
            case 'vstream': //    Binary Versioned Stream
            case 'clsid': //    Class ID
            case 'cf': //    Clipboard Data
                return self::PROPERTY_TYPE_UNKNOWN;
                break;
        }
        return self::PROPERTY_TYPE_UNKNOWN;
    }
}
