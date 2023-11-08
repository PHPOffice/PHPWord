<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors. test bootstrap
 *
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */
require_once __DIR__ . '/../bootstrap.php';

date_default_timezone_set('UTC');

// defining base dir for tests
if (!defined('PHPWORD_TESTS_BASE_DIR')) {
    define('PHPWORD_TESTS_BASE_DIR', realpath(__DIR__));
}

function phpunit10ErrorHandler(int $errno, string $errstr, string $filename, int $lineno): bool
{
    $x = error_reporting() & $errno;
    if (
        in_array(
            $errno,
            [
                E_DEPRECATED,
                E_WARNING,
                E_NOTICE,
                E_USER_DEPRECATED,
                E_USER_NOTICE,
                E_USER_WARNING,
            ],
            true
        )
    ) {
        if (0 === $x) {
            return true; // message suppressed - stop error handling
        }

        throw new \Exception("$errstr $filename $lineno");
    }

    return false; // continue error handling
}

function utf8decode(string $value, string $toEncoding = 'ISO-8859-1'): string
{
    return function_exists('mb_convert_encoding') ? mb_convert_encoding($value, $toEncoding, 'UTF-8') : utf8_decode($value);
}

if (!method_exists(\PHPUnit\Framework\TestCase::class, 'setOutputCallback')) {
    ini_set('error_reporting', (string) E_ALL);
    set_error_handler('phpunit10ErrorHandler');
}
