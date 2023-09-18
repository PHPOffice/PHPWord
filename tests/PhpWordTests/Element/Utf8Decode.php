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
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWordTests\Element;

/**
 * Php 8.2 deprecates utf8_decode, but mb_convert_encoding should work.
 */
class Utf8Decode
{
    public static function decode(string $value, string $toEncoding = 'ISO-8859-1'): string
    {
        return function_exists('mb_convert_encoding') ? mb_convert_encoding($value, $toEncoding, 'UTF-8') : utf8_decode($value);
    }
}
