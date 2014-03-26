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
 * @copyright  Copyright (c) 2014 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.9.0
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
