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

use PhpOffice\PhpWord\Shared\Converter;

/**
 * Test class for PhpOffice\PhpWord\Shared\Converter.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Shared\Converter
 */
class ConverterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test unit conversion functions with various numbers.
     */
    public function testUnitConversions(): void
    {
        $values = [];
        $values[] = 0; // zero value
        $values[] = mt_rand(1, 100) / 100; // fraction number
        $values[] = mt_rand(1, 100); // integer

        foreach ($values as $value) {
            $result = Converter::cmToTwip($value);
            self::assertEqualsWithDelta($value / 2.54 * 1440, $result, 0.00001);

            $result = Converter::cmToInch($value);
            self::assertEqualsWithDelta($value / 2.54, $result, 0.00001);

            $result = Converter::cmToPixel($value);
            self::assertEqualsWithDelta($value / 2.54 * 96, $result, 0.00001);

            $result = Converter::cmToPoint($value);
            self::assertEqualsWithDelta($value / 2.54 * 72, $result, 0.00001);

            $result = Converter::cmToEmu($value);
            self::assertEqualsWithDelta(round($value / 2.54 * 96 * 9525), $result, 0.00001);

            $result = Converter::inchToTwip($value);
            self::assertEqualsWithDelta($value * 1440, $result, 0.00001);

            $result = Converter::inchToCm($value);
            self::assertEqualsWithDelta($value * 2.54, $result, 0.00001);

            $result = Converter::inchToPixel($value);
            self::assertEqualsWithDelta($value * 96, $result, 0.00001);

            $result = Converter::inchToPoint($value);
            self::assertEqualsWithDelta($value * 72, $result, 0.00001);

            $result = Converter::inchToEmu($value);
            self::assertEqualsWithDelta(round($value * 96 * 9525), $result, 0.00001);

            $result = Converter::pixelToTwip($value);
            self::assertEqualsWithDelta($value / 96 * 1440, $result, 0.00001);

            $result = Converter::pixelToCm($value);
            self::assertEqualsWithDelta($value / 96 * 2.54, $result, 0.00001);

            $result = Converter::pixelToPoint($value);
            self::assertEqualsWithDelta($value / 96 * 72, $result, 0.00001);

            $result = Converter::pixelToEmu($value);
            self::assertEqualsWithDelta(round($value * 9525), $result, 0.00001);

            $result = Converter::pointToTwip($value);
            self::assertEqualsWithDelta($value * 20, $result, 0.00001);

            $result = Converter::pointToCm($value);
            self::assertEqualsWithDelta($value * 0.035277778, $result, 0.00001);

            $result = Converter::pointToPixel($value);
            self::assertEqualsWithDelta($value / 72 * 96, $result, 0.00001);

            $result = Converter::pointToEmu($value);
            self::assertEqualsWithDelta(round($value / 72 * 96 * 9525), $result, 0.00001);

            $result = Converter::emuToPixel($value);
            self::assertEqualsWithDelta(round($value / 9525), $result, 0.00001);

            $result = Converter::picaToPoint($value);
            self::assertEqualsWithDelta($value / 6 * 72, $result, 0.00001);

            $result = Converter::degreeToAngle($value);
            self::assertEqualsWithDelta((int) round($value * 60000), $result, 0.00001);

            $result = Converter::angleToDegree($value);
            self::assertEqualsWithDelta(round($value / 60000), $result, 0.00001);
        }
    }

    /**
     * Test htmlToRGB().
     */
    public function testHtmlToRGB(): void
    {
        $flse = false;
        self::assertEquals([255, 153, 221], Converter::htmlToRgb('#FF99DD')); // With #
        self::assertEquals([224, 170, 29], Converter::htmlToRgb('E0AA1D')); // 6 characters
        self::assertEquals([102, 119, 136], Converter::htmlToRgb('678')); // 3 characters
        self::assertEquals($flse, Converter::htmlToRgb('0F9D')); // 4 characters
        self::assertEquals([0, 0, 0], Converter::htmlToRgb('unknow')); // 6 characters, invalid
        self::assertEquals([139, 0, 139], Converter::htmlToRgb(\PhpOffice\PhpWord\Style\Font::FGCOLOR_DARKMAGENTA)); // Constant
    }

    /**
     * Test css size to point.
     */
    public function testCssSizeParser(): void
    {
        self::assertNull(Converter::cssToPoint('10em'));
        self::assertEquals(0, Converter::cssToPoint('0'));
        self::assertEquals(10, Converter::cssToPoint('10pt'));
        self::assertEquals(7.5, Converter::cssToPoint('10px'));
        self::assertEquals(720, Converter::cssToPoint('10in'));
        self::assertEquals(7.2, Converter::cssToPoint('0.1in'));
        self::assertEquals(120, Converter::cssToPoint('10pc'));
        self::assertEqualsWithDelta(28.346457, Converter::cssToPoint('10mm'), 0.000001);
        self::assertEqualsWithDelta(283.464567, Converter::cssToPoint('10cm'), 0.000001);
        self::assertEquals(40, Converter::cssToPixel('30pt'));
        self::assertEquals(1.27, Converter::cssToCm('36pt'));
        self::assertEquals(127000, Converter::cssToEmu('10pt'));
    }
}
