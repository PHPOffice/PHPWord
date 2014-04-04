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
    /**    Available Zip library classes */
    const PCLZIP     = 'PhpOffice\\PhpWord\\Shared\\ZipArchive';
    const ZIPARCHIVE = 'ZipArchive';

    /**
     * Compatibility option for XMLWriter
     *
     * @var boolean
     */
    private static $xmlWriterCompatibility = true;

    /**
     * Name of the class used for Zip file management
     *  e.g.
     *      ZipArchive
     *
     * @var string
     */
    private static $zipClass = self::ZIPARCHIVE;

    /**
     * Set the compatibility option used by the XMLWriter
     *
     * @param boolean $compatibility  This sets the setIndent and setIndentString for better compatibility
     * @return  boolean Success or failure
     */
    public static function setCompatibility($compatibility)
    {
        if (is_bool($compatibility)) {
            self::$xmlWriterCompatibility = $compatibility;
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
        return self::$xmlWriterCompatibility;
    }

    /**
     * Set the Zip handler Class that PHPWord should use for Zip file management (PCLZip or ZipArchive)
     *
     * @param  string $zipClass  The Zip handler class that PHPWord should use for Zip file management
     *   e.g. Settings::PCLZip or Settings::ZipArchive
     * @return boolean Success or failure
     */
    public static function setZipClass($zipClass)
    {
        if (($zipClass === self::PCLZIP) ||
            ($zipClass === self::ZIPARCHIVE)) {
            self::$zipClass = $zipClass;
            return true;
        }
        return false;
    } // function setZipClass()

    /**
     * Return the name of the Zip handler Class that PHPWord is configured to use (PCLZip or ZipArchive)
     *  or Zip file management
     *
     * @return string Name of the Zip handler Class that PHPWord is configured to use
     *  for Zip file management
     *  e.g. Settings::PCLZip or Settings::ZipArchive
     */
    public static function getZipClass()
    {
        return self::$zipClass;
    } // function getZipClass()
}
