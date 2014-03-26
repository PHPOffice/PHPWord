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
 * Autoloader
 */
class Autoloader
{
    const NAMESPACE_PREFIX = 'PhpOffice\\PhpWord\\';

    /**
     * Register
     *
     * @return void
     */
    public static function register()
    {
        spl_autoload_register(array(new self, 'autoload'));
    }

    /**
     * Autoload
     *
     * @param string $class
     */
    public static function autoload($class)
    {
        $prefixLength = strlen(self::NAMESPACE_PREFIX);
        if (0 === strncmp(self::NAMESPACE_PREFIX, $class, $prefixLength)) {
            $file = str_replace('\\', DIRECTORY_SEPARATOR, substr($class, $prefixLength));
            $file = realpath(__DIR__ . (empty($file) ? '' : DIRECTORY_SEPARATOR) . $file . '.php');
            if (file_exists($file)) {
                require_once $file;
            }
        }
    }
}
