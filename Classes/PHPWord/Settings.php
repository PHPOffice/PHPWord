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
}
