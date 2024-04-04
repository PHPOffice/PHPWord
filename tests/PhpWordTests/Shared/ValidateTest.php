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

namespace PhpOffice\PhpWordTests\Shared;

use PhpOffice\PhpWord\Shared\Validate;

class ValidateTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider providerCSSGenericFont
     */
    public function testValidateCSSGenericFont(?string $value, string $expected): void
    {
        self::assertEquals($expected, Validate::validateCSSGenericFont($value));
    }

    public static function providerCSSGenericFont(): iterable
    {
        $data = [];
        // Valid data
        foreach (Validate::CSS_GENERICFONT as $value) {
            $data[] = [
                $value,
                $value,
            ];
        }
        // Invalid data
        $data[] = ['invalidData', ''];
        $data[] = ['', ''];
        $data[] = [null, ''];

        return $data;
    }

    /**
     * @dataProvider providerCSSWhiteSpace
     */
    public function testValidateCSSWhiteSpace(?string $value, string $expected): void
    {
        self::assertEquals($expected, Validate::validateCSSWhiteSpace($value));
    }

    public static function providerCSSWhiteSpace(): iterable
    {
        $data = [];
        // Valid data
        foreach (Validate::CSS_WHITESPACE as $value) {
            $data[] = [
                $value,
                $value,
            ];
        }
        // Invalid data
        $data[] = ['invalidData', ''];
        $data[] = ['', ''];
        $data[] = [null, ''];

        return $data;
    }
}
