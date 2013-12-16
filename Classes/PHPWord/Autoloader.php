<?php
/**
 * PHPWord
 *
 * Copyright (c) 2013 PHPWord
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
 * @copyright  Copyright (c) 2013 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.7.0
 */

if (!defined('PHPWORD_BASE_PATH')) {
    define('PHPWORD_BASE_PATH', realpath(__DIR__ . '/../') . '/');
}

/**
 * Class PHPWord_Autoloader
 */
class PHPWord_Autoloader
{
    /**
     * Register the autoloader
     *
     * @return void
     */
    public static function register()
    {
        spl_autoload_register(array('PHPWord_Autoloader', 'load'));
    }

    /**
     * Autoloader
     *
     * @param string $strObjectName
     * @return mixed
     */
    public static function load($strObjectName)
    {
        $strObjectFilePath = __DIR__ . '/../' . str_replace('_', '/', $strObjectName) . '.php';
        if (file_exists($strObjectFilePath) && is_readable($strObjectFilePath)) {
            require_once $strObjectFilePath;
            return true;
        }

        return null;
    }
}