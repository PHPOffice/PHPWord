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
 * Settings
 */
class Settings
{
    /**
     * Compatibility option for XMLWriter
     *
     * @var boolean
     */
    private static $_xmlWriterCompatibility = true;

    /**
     * Set the compatibility option used by the XMLWriter
     *
     * @param boolean $compatibility  This sets the setIndent and setIndentString for better compatibility
     * @return  boolean Success or failure
     */
    public static function setCompatibility($compatibility)
    {
        if (is_bool($compatibility)) {
            self::$_xmlWriterCompatibility = $compatibility;
            return true;
        }
        return false;
    }

    /**
     * Return the compatibility option used by the XMLWriter
     *
     * @return boolean Compatibility
     */
    public static function getCompatibility()
    {
        return self::$_xmlWriterCompatibility;
    }
}
