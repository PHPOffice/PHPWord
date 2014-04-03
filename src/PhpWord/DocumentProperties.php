<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord;

/**
 * Document properties
 */
class DocumentProperties
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
    private $creator;

    /**
     * LastModifiedBy
     *
     * @var string
     */
    private $lastModifiedBy;

    /**
     * Created
     *
     * @var int
     */
    private $created;

    /**
     * Modified
     *
     * @var int
     */
    private $modified;

    /**
     * Title
     *
     * @var string
     */
    private $title;

    /**
     * Description
     *
     * @var string
     */
    private $description;

    /**
     * Subject
     *
     * @var string
     */
    private $subject;

    /**
     * Keywords
     *
     * @var string
     */
    private $keywords;

    /**
     * Category
     *
     * @var string
     */
    private $category;

    /**
     * Company
     *
     * @var string
     */
    private $company;

    /**
     * Manager
     *
     * @var string
     */
    private $manager;

    /**
     * Custom Properties
     *
     * @var array
     */
    private $customProperties = array();

    /**
     * Create new DocumentProperties
     */
    public function __construct()
    {
        $this->creator        = '';
        $this->lastModifiedBy = $this->creator;
        $this->created        = time();
        $this->modified       = time();
        $this->title          = '';
        $this->subject        = '';
        $this->description    = '';
        $this->keywords       = '';
        $this->category       = '';
        $this->company        = '';
        $this->manager        = '';
    }

    /**
     * Get Creator
     *
     * @return string
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * Set Creator
     *
     * @param  string $pValue
     * @return \PhpOffice\PhpWord\DocumentProperties
     */
    public function setCreator($pValue = '')
    {
        $this->creator = $pValue;
        return $this;
    }

    /**
     * Get Last Modified By
     *
     * @return string
     */
    public function getLastModifiedBy()
    {
        return $this->lastModifiedBy;
    }

    /**
     * Set Last Modified By
     *
     * @param  string $pValue
     * @return \PhpOffice\PhpWord\DocumentProperties
     */
    public function setLastModifiedBy($pValue = '')
    {
        $this->lastModifiedBy = $pValue;
        return $this;
    }

    /**
     * Get Created
     *
     * @return int
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set Created
     *
     * @param  int $pValue
     * @return \PhpOffice\PhpWord\DocumentProperties
     */
    public function setCreated($pValue = null)
    {
        if (is_null($pValue)) {
            $pValue = time();
        }
        $this->created = $pValue;
        return $this;
    }

    /**
     * Get Modified
     *
     * @return int
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * Set Modified
     *
     * @param  int $pValue
     * @return \PhpOffice\PhpWord\DocumentProperties
     */
    public function setModified($pValue = null)
    {
        if (is_null($pValue)) {
            $pValue = time();
        }
        $this->modified = $pValue;
        return $this;
    }

    /**
     * Get Title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set Title
     *
     * @param  string $pValue
     * @return \PhpOffice\PhpWord\DocumentProperties
     */
    public function setTitle($pValue = '')
    {
        $this->title = $pValue;
        return $this;
    }

    /**
     * Get Description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set Description
     *
     * @param  string $pValue
     * @return \PhpOffice\PhpWord\DocumentProperties
     */
    public function setDescription($pValue = '')
    {
        $this->description = $pValue;
        return $this;
    }

    /**
     * Get Subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set Subject
     *
     * @param  string $pValue
     * @return \PhpOffice\PhpWord\DocumentProperties
     */
    public function setSubject($pValue = '')
    {
        $this->subject = $pValue;
        return $this;
    }

    /**
     * Get Keywords
     *
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set Keywords
     *
     * @param string $pValue
     * @return \PhpOffice\PhpWord\DocumentProperties
     */
    public function setKeywords($pValue = '')
    {
        $this->keywords = $pValue;
        return $this;
    }

    /**
     * Get Category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set Category
     *
     * @param string $pValue
     * @return \PhpOffice\PhpWord\DocumentProperties
     */
    public function setCategory($pValue = '')
    {
        $this->category = $pValue;
        return $this;
    }

    /**
     * Get Company
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set Company
     *
     * @param string $pValue
     * @return \PhpOffice\PhpWord\DocumentProperties
     */
    public function setCompany($pValue = '')
    {
        $this->company = $pValue;
        return $this;
    }

    /**
     * Get Manager
     *
     * @return string
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Set Manager
     *
     * @param string $pValue
     * @return \PhpOffice\PhpWord\DocumentProperties
     */
    public function setManager($pValue = '')
    {
        $this->manager = $pValue;
        return $this;
    }

    /**
     * Get a List of Custom Property Names
     *
     * @return array of string
     */
    public function getCustomProperties()
    {
        return array_keys($this->customProperties);
    }

    /**
     * Check if a Custom Property is defined
     *
     * @param string $propertyName
     * @return boolean
     */
    public function isCustomPropertySet($propertyName)
    {
        return isset($this->customProperties[$propertyName]);
    }

    /**
     * Get a Custom Property Value
     *
     * @param string $propertyName
     * @return string
     */
    public function getCustomPropertyValue($propertyName)
    {
        if (isset($this->customProperties[$propertyName])) {
            return $this->customProperties[$propertyName]['value'];
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
        if (isset($this->customProperties[$propertyName])) {
            return $this->customProperties[$propertyName]['type'];
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
     * @return \PhpOffice\PhpWord\DocumentProperties
     */
    public function setCustomProperty($propertyName, $propertyValue = '', $propertyType = null)
    {
        $propertyTypes = array(
            self::PROPERTY_TYPE_INTEGER,
            self::PROPERTY_TYPE_FLOAT,
            self::PROPERTY_TYPE_STRING,
            self::PROPERTY_TYPE_DATE,
            self::PROPERTY_TYPE_BOOLEAN
        );
        if (($propertyType === null) || (!in_array($propertyType, $propertyTypes))) {
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

        $this->customProperties[$propertyName] = array(
            'value' => $propertyValue,
            'type' => $propertyType
        );
        return $this;
    }

    /**
     * Convert document property based on type
     *
     * @param string $propertyValue
     * @param string $propertyType
     * @return mixed
     */
    public static function convertProperty($propertyValue, $propertyType)
    {
        switch ($propertyType) {
            case 'empty': //    Empty
                return '';
            case 'null': //    Null
                return null;
            case 'i1': //    1-Byte Signed Integer
            case 'i2': //    2-Byte Signed Integer
            case 'i4': //    4-Byte Signed Integer
            case 'i8': //    8-Byte Signed Integer
            case 'int': //    Integer
                return (int) $propertyValue;
            case 'ui1': //    1-Byte Unsigned Integer
            case 'ui2': //    2-Byte Unsigned Integer
            case 'ui4': //    4-Byte Unsigned Integer
            case 'ui8': //    8-Byte Unsigned Integer
            case 'uint': //    Unsigned Integer
                return abs((int) $propertyValue);
            case 'r4': //    4-Byte Real Number
            case 'r8': //    8-Byte Real Number
            case 'decimal': //    Decimal
                return (float) $propertyValue;
            case 'lpstr': //    LPSTR
            case 'lpwstr': //    LPWSTR
            case 'bstr': //    Basic String
                return $propertyValue;
            case 'date': //    Date and Time
            case 'filetime': //    File Time
                return strtotime($propertyValue);
            case 'bool': //    Boolean
                return ($propertyValue == 'true') ? true : false;
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
        }

        return $propertyValue;
    }

    /**
     * Convert document property type
     *
     * @param string $propertyType
     * @return string
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
            case 'r4': //    4-Byte Real Number
            case 'r8': //    8-Byte Real Number
            case 'decimal': //    Decimal
                return self::PROPERTY_TYPE_FLOAT;
            case 'empty': //    Empty
            case 'null': //    Null
            case 'lpstr': //    LPSTR
            case 'lpwstr': //    LPWSTR
            case 'bstr': //    Basic String
                return self::PROPERTY_TYPE_STRING;
            case 'date': //    Date and Time
            case 'filetime': //    File Time
                return self::PROPERTY_TYPE_DATE;
            case 'bool': //    Boolean
                return self::PROPERTY_TYPE_BOOLEAN;
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
        }
        return self::PROPERTY_TYPE_UNKNOWN;
    }
}
