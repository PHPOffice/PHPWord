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
use PhpOffice\PhpWord\SimpleType\Color;
use PhpOffice\PhpWord\Style\Font;

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
        self::assertEquals([139, 0, 139], Converter::htmlToRgb(Color::DARKMAGENTA)); // Constant
    }

    /**
     * Test SimpleType::Color. Ensure all colors come out to correct values.
     * Verified against https://c-rex.net/samples/ooxml/e1/Part4/OOXML_P4_DOCX_ST_PresetColorVal_topic_ID0ELA5NB.html.
     */
    public function testBuiltInColors(): void
    {
        self::assertSame([0, 255, 255], Converter::htmlToRgb(Color::AQUA));
        self::assertSame([0, 0, 0], Converter::htmlToRgb(Color::BLACK));
        self::assertSame([0, 0, 255], Converter::htmlToRgb(Color::BLUE));
        self::assertSame([165, 42, 42], Converter::htmlToRgb(Color::BROWN));
        self::assertSame([0, 255, 255], Converter::htmlToRgb(Color::CYAN));
        self::assertSame([0, 0, 139], Converter::htmlToRgb(Color::DARKBLUE));
        self::assertSame([0, 139, 139], Converter::htmlToRgb(Color::DARKCYAN));
        self::assertSame([169, 169, 169], Converter::htmlToRgb(Color::DARKGRAY));
        self::assertSame([0, 100, 0], Converter::htmlToRgb(Color::DARKGREEN));
        self::assertSame([139, 0, 139], Converter::htmlToRgb(Color::DARKMAGENTA));
        self::assertSame([255, 140, 0], Converter::htmlToRgb(Color::DARKORANGE));
        self::assertSame([139, 0, 0], Converter::htmlToRgb(Color::DARKRED));
        self::assertSame([148, 0, 211], Converter::htmlToRgb(Color::DARKVIOLET));
        self::assertSame([128, 128, 0], Converter::htmlToRgb(Color::DARKYELLOW));
        self::assertSame([255, 0, 255], Converter::htmlToRgb(Color::FUCHSIA));
        self::assertSame([255, 215, 0], Converter::htmlToRgb(Color::GOLD));
        self::assertSame([128, 128, 128], Converter::htmlToRgb(Color::GRAY));
        self::assertSame([0, 128, 0], Converter::htmlToRgb(Color::GREEN));
        self::assertSame([173, 216, 230], Converter::htmlToRgb(Color::LIGHTBLUE));
        self::assertSame([224, 255, 255], Converter::htmlToRgb(Color::LIGHTCYAN));
        self::assertSame([211, 211, 211], Converter::htmlToRgb(Color::LIGHTGRAY));
        self::assertSame([144, 238, 144], Converter::htmlToRgb(Color::LIGHTGREEN));
        self::assertSame([255, 182, 193], Converter::htmlToRgb(Color::LIGHTPINK));
        self::assertSame([255, 255, 224], Converter::htmlToRgb(Color::LIGHTYELLOW));
        self::assertSame([0, 255, 0], Converter::htmlToRgb(Color::LIME));
        self::assertSame([255, 0, 255], Converter::htmlToRgb(Color::MAGENTA));
        self::assertSame([128, 0, 0], Converter::htmlToRgb(Color::MAROON));
        self::assertSame([0, 0, 128], Converter::htmlToRgb(Color::NAVY));
        self::assertSame([128, 128, 0], Converter::htmlToRgb(Color::OLIVE));
        self::assertSame([255, 165, 0], Converter::htmlToRgb(Color::ORANGE));
        self::assertSame([255, 192, 203], Converter::htmlToRgb(Color::PINK));
        self::assertSame([128, 0, 128], Converter::htmlToRgb(Color::PURPLE));
        self::assertSame([255, 0, 0], Converter::htmlToRgb(Color::RED));
        self::assertSame([192, 192, 192], Converter::htmlToRgb(Color::SILVER));
        self::assertSame([210, 180, 140], Converter::htmlToRgb(Color::TAN));
        self::assertSame([0, 128, 128], Converter::htmlToRgb(Color::TEAL));
        self::assertSame([64, 224, 208], Converter::htmlToRgb(Color::TURQUOISE));
        self::assertSame([238, 130, 238], Converter::htmlToRgb(Color::VIOLET));
        self::assertSame([255, 255, 255], Converter::htmlToRgb(Color::WHITE));
        self::assertSame([255, 255, 0], Converter::htmlToRgb(Color::YELLOW));
    }

    /**
     * Make sure deprecated colors are properly represented in non-deprecated.
     *
     * @dataProvider providerDeprecatedColorNames
     */
    public function testDeprecatedColorNames(string $deprecatedName, string $newName): void
    {
        self::assertSame($deprecatedName, $newName);
    }

    public function testDeprecatedColorNamesCount(): void
    {
        self::assertCount(15, self::providerDeprecatedColorNames());
    }

    public static function providerDeprecatedColorNames(): array
    {
        return [
            [Font::FGCOLOR_BLACK, Color::BLACK],
            [Font::FGCOLOR_BLUE, Color::BLUE],
            [Font::FGCOLOR_CYAN, Color::CYAN],
            [Font::FGCOLOR_DARKBLUE, Color::DARKBLUE],
            [Font::FGCOLOR_DARKCYAN, Color::DARKCYAN],
            [Font::FGCOLOR_DARKGRAY, Color::DARKGRAY],
            [Font::FGCOLOR_DARKGREEN, Color::DARKGREEN],
            [Font::FGCOLOR_DARKMAGENTA, Color::DARKMAGENTA],
            [Font::FGCOLOR_DARKRED, Color::DARKRED],
            [Font::FGCOLOR_DARKYELLOW, Color::DARKYELLOW],
            [Font::FGCOLOR_LIGHTGRAY, Color::LIGHTGRAY],
            [Font::FGCOLOR_LIGHTGREEN, Color::LIGHTGREEN],
            [Font::FGCOLOR_MAGENTA, Color::MAGENTA],
            [Font::FGCOLOR_RED, Color::RED],
            [Font::FGCOLOR_YELLOW, Color::YELLOW],
        ];
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
