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
 * PHPWord_Settings
 */
class PHPWord_Settings
{
    /**    constants */

    /**    Available Zip library classes */
    const PCLZIP     = 'PHPWord_Shared_ZipArchive';
    const ZIPARCHIVE = 'ZipArchive';

    /**
     * Compatibility option for XMLWriter
     *
     * @var boolean
     */
    private static $_xmlWriterCompatibility = true;

    /**
     * Name of the class used for Zip file management
     *  e.g.
     *      ZipArchive
     *
     * @var string
     */
    private static $_zipClass = self::ZIPARCHIVE;

    /**
     * Set the compatibility option used by the XMLWriter
     *
     * @param  boolean $compatibility  This sets the setIndent and setIndentString for better compatibility
     * @return boolean Success or failure
     */
    public static function setCompatibility($compatibility)
    {
        if (is_bool($compatibility)) {
            self::$_xmlWriterCompatibility = $compatibility;
            return true;
        }
        return false;
    } // function setCompatibility()

    /**
     * Return the compatibility option used by the XMLWriter
     *
     * @return boolean Compatibility
     */
    public static function getCompatibility()
    {
        return self::$_xmlWriterCompatibility;
    } // function getCompatibility()

    /**
     * Set the Zip handler Class that PHPWord should use for Zip file management (PCLZip or ZipArchive)
     *
     * @param  string $zipClass  The Zip handler class that PHPWord should use for Zip file management
     *   e.g. PHPWord_Settings::PCLZip or PHPWord_Settings::ZipArchive
     * @return boolean Success or failure
     */
    public static function setZipClass($zipClass)
    {
        if (($zipClass === self::PCLZIP) ||
            ($zipClass === self::ZIPARCHIVE)) {
            self::$_zipClass = $zipClass;
            return TRUE;
        }
        return FALSE;
    } // function setZipClass()

    /**
     * Return the name of the Zip handler Class that PHPWord is configured to use (PCLZip or ZipArchive)
     *  or Zip file management
     *
     * @return string Name of the Zip handler Class that PHPWord is configured to use
     *  for Zip file management
     *  e.g. PHPWord_Settings::PCLZip or PHPWord_Settings::ZipArchive
     */
    public static function getZipClass()
    {
        return self::$_zipClass;
    } // function getZipClass()
}

