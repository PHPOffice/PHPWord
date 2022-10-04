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

use PhpOffice\PhpWord\Shared\Drawing;

/**
 * Test class for PhpOffice\PhpWord\Shared\Drawing.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Shared\Drawing
 */
class DrawingTest extends \PHPUnit\Framework\TestCase
{
    public function testDegreesAngle(): void
    {
        $value = mt_rand(1, 100);

        self::assertEquals(0, Drawing::degreesToAngle());
        self::assertEquals((int) round($value * 60000), Drawing::degreesToAngle($value));
        self::assertEquals(0, Drawing::angleToDegrees());
        self::assertEquals(round($value / 60000), Drawing::angleToDegrees($value));
    }

    public function testPixelsCentimeters(): void
    {
        $value = mt_rand(1, 100);

        self::assertEquals(0, Drawing::pixelsToCentimeters());
        self::assertEquals($value / Drawing::DPI_96 * 2.54, Drawing::pixelsToCentimeters($value));
        self::assertEquals(0, Drawing::centimetersToPixels());
        self::assertEquals($value / 2.54 * Drawing::DPI_96, Drawing::centimetersToPixels($value));
    }

    public function testPixelsEMU(): void
    {
        $value = mt_rand(1, 100);

        self::assertEquals(0, Drawing::pixelsToEmu());
        self::assertEquals(round($value * 9525), Drawing::pixelsToEmu($value));
        self::assertEquals(0, Drawing::emuToPixels());
        self::assertEquals(round($value / 9525), Drawing::emuToPixels($value));
    }

    public function testPixelsPoints(): void
    {
        $value = mt_rand(1, 100);

        self::assertEquals(0, Drawing::pixelsToPoints());
        self::assertEquals($value * 0.67777777, Drawing::pixelsToPoints($value));
        self::assertEquals(0, Drawing::pointsToPixels());
        self::assertEquals($value * 1.333333333, Drawing::pointsToPixels($value));
    }

    public function testPointsCentimeters(): void
    {
        $value = mt_rand(1, 100);

        self::assertEquals(0, Drawing::pointsToCentimeters());
        self::assertEquals($value * 1.333333333 / Drawing::DPI_96 * 2.54, Drawing::pointsToCentimeters($value));
    }

    public function testTwips(): void
    {
        $value = mt_rand(1, 100);

        // Centimeters
        self::assertEquals(0, Drawing::centimetersToTwips());
        self::assertEquals($value * 566.928, Drawing::centimetersToTwips($value));

        self::assertEquals(0, Drawing::twipsToCentimeters());
        self::assertEquals($value / 566.928, Drawing::twipsToCentimeters($value));

        // Inches
        self::assertEquals(0, Drawing::inchesToTwips());
        self::assertEquals($value * 1440, Drawing::inchesToTwips($value));

        self::assertEquals(0, Drawing::twipsToInches());
        self::assertEquals($value / 1440, Drawing::twipsToInches($value));

        // Pixels
        self::assertEquals(0, Drawing::twipsToPixels());
        self::assertEquals(round($value / 15.873984), Drawing::twipsToPixels($value));
    }

    public function testHTML(): void
    {
        self::assertFalse(Drawing::htmlToRGB('0'));
        self::assertFalse(Drawing::htmlToRGB('00'));
        self::assertFalse(Drawing::htmlToRGB('0000'));
        self::assertFalse(Drawing::htmlToRGB('00000'));

        self::assertIsArray(Drawing::htmlToRGB('ABCDEF'));
        self::assertCount(3, Drawing::htmlToRGB('ABCDEF'));
        self::assertEquals([0xAB, 0xCD, 0xEF], Drawing::htmlToRGB('ABCDEF'));
        self::assertEquals([0xAB, 0xCD, 0xEF], Drawing::htmlToRGB('#ABCDEF'));
        self::assertEquals([0xAA, 0xBB, 0xCC], Drawing::htmlToRGB('ABC'));
        self::assertEquals([0xAA, 0xBB, 0xCC], Drawing::htmlToRGB('#ABC'));
    }
}
