<?php
/**
 * PhpWord
 *
 * Copyright (c) 2014 PhpWord
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
 * @copyright  Copyright (c) 2014 PhpWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.8.0
 */

namespace PhpOffice\PhpWord;

if (!\defined('PHPWORD_BASE_DIR')) {
    \define('PHPWORD_BASE_DIR', \realpath(__DIR__) . \DIRECTORY_SEPARATOR);
}

class Autoloader
{
    const NAMESPACE_PREFIX = 'PhpOffice\\PhpWord\\';

    /**
     * @return void
     */
    public static function register()
    {
        \spl_autoload_register(array(new self, 'autoload'));
    }

    /**
     * @param string $fqClassName
     */
    public static function autoload($fqClassName)
    {
        $namespacePrefixLength = \strlen(self::NAMESPACE_PREFIX);
        $className = \substr($fqClassName, $namespacePrefixLength);

        if (0 === \strncmp(self::NAMESPACE_PREFIX, $fqClassName, $namespacePrefixLength)) {
            $fqFilename = \PHPWORD_BASE_DIR
                        . \str_replace('\\', \DIRECTORY_SEPARATOR, $className)
                        . '.php';

            if (\file_exists($fqFilename)) {
                require_once $fqFilename;
            } else {
                throw new \Exception("Could not instantiate class.");
            }
        }
    }
}
