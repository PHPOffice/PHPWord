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
 * Endnote collection
 *
 * @since 0.10.0
 */
class Endnotes
{
    /**
     * Elements
     *
     * @var array
     */
    private static $elements = array();

    /**
     * Add new element
     *
     * @param \PhpOffice\PhpWord\Element\Endnote $element
     * @return integer Reference ID
     */
    public static function addElement($element)
    {
        $rId = self::countElements() + 1;
        self::$elements[$rId] = $element;

        return $rId;
    }

    /**
     * Set element
     *
     * @param integer $index
     * @param \PhpOffice\PhpWord\Element\Endnote $element
     */
    public static function setElement($index, $element)
    {
        if (array_key_exists($index, self::$elements)) {
            self::$elements[$index] = $element;
        }
    }

    /**
     * Get element by index
     *
     * @param integer $index
     * @return \PhpOffice\PhpWord\Element\Endnote
     */
    public static function getElement($index)
    {
        if (array_key_exists($index, self::$elements)) {
            return self::$elements[$index];
        } else {
            return null;
        }
    }

    /**
     * Get elements
     *
     * @return array
     */
    public static function getElements()
    {
        return self::$elements;
    }

    /**
     * Get element count
     *
     * @return integer
     */
    public static function countElements()
    {
        return count(self::$elements);
    }

    /**
     * Reset elements
     */
    public static function resetElements()
    {
        self::$elements = array();
    }
}
