<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Style;

/**
 * List item style
 */
class ListItem
{
    const TYPE_NUMBER = 7;
    const TYPE_NUMBER_NESTED = 8;
    const TYPE_ALPHANUM = 9;
    const TYPE_BULLET_FILLED = 3;
    const TYPE_BULLET_EMPTY = 5;
    const TYPE_SQUARE_FILLED = 1;

    /**
     * List Type
     */
    private $_listType;

    /**
     * Create a new ListItem Style
     */
    public function __construct()
    {
        $this->_listType = self::TYPE_BULLET_FILLED;
    }

    /**
     * Set style value
     *
     * @param string $key
     * @param string $value
     */
    public function setStyleValue($key, $value)
    {
        $this->$key = $value;
    }

    /**
     * Set List Type
     *
     * @param int $pValue
     */
    public function setListType($pValue = self::TYPE_BULLET_FILLED)
    {
        $this->_listType = $pValue;
    }

    /**
     * Get List Type
     */
    public function getListType()
    {
        return $this->_listType;
    }
}
