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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Shared;

/**
 * Test class for PhpOffice\PhpWord\Shared\Drawing
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Shared\Drawing
 */
class DrawingTest extends \PHPUnit\Framework\TestCase
{
    public function testDegreesAngle()
    {
        $value = rand(1, 100);

        $this->assertEquals(0, Drawing::degreesToAngle());
        $this->assertEquals((int) round($value * 60000), Drawing::degreesToAngle($value));
        $this->assertEquals(0, Drawing::angleToDegrees());
        $this->assertEquals(round($value / 60000), Drawing::angleToDegrees($value));
    }

    public function testPixelsCentimeters()
    {
        $value = rand(1, 100);

        $this->assertEquals(0, Drawing::pixelsToCentimeters());
        $this->assertEquals($value / Drawing::DPI_96 * 2.54, Drawing::pixelsToCentimeters($value));
        $this->assertEquals(0, Drawing::centimetersToPixels());
        $this->assertEquals($value / 2.54 * Drawing::DPI_96, Drawing::centimetersToPixels($value));
    }

    public function testPixelsEMU()
    {
        $value = rand(1, 100);

        $this->assertEquals(0, Drawing::pixelsToEmu());
        $this->assertEquals(round($value * 9525), Drawing::pixelsToEmu($value));
        $this->assertEquals(0, Drawing::emuToPixels());
        $this->assertEquals(round($value / 9525), Drawing::emuToPixels($value));
    }

    public function testPixelsPoints()
    {
        $value = rand(1, 100);

        $this->assertEquals(0, Drawing::pixelsToPoints());
        $this->assertEquals($value * 0.67777777, Drawing::pixelsToPoints($value));
        $this->assertEquals(0, Drawing::pointsToPixels());
        $this->assertEquals($value * 1.333333333, Drawing::pointsToPixels($value));
    }

    public function testPointsCentimeters()
    {
        $value = rand(1, 100);

        $this->assertEquals(0, Drawing::pointsToCentimeters());
        $this->assertEquals($value * 1.333333333 / Drawing::DPI_96 * 2.54, Drawing::pointsToCentimeters($value));
    }

    public function testTwips()
    {
        $value = rand(1, 100);

        // Centimeters
        $this->assertEquals(0, Drawing::centimetersToTwips());
        $this->assertEquals($value * 566.928, Drawing::centimetersToTwips($value));

        $this->assertEquals(0, Drawing::twipsToCentimeters());
        $this->assertEquals($value / 566.928, Drawing::twipsToCentimeters($value));

        // Inches
        $this->assertEquals(0, Drawing::inchesToTwips());
        $this->assertEquals($value * 1440, Drawing::inchesToTwips($value));

        $this->assertEquals(0, Drawing::twipsToInches());
        $this->assertEquals($value / 1440, Drawing::twipsToInches($value));

        // Pixels
        $this->assertEquals(0, Drawing::twipsToPixels());
        $this->assertEquals(round($value / 15.873984), Drawing::twipsToPixels($value));
    }

    public function testHTML()
    {
        $this->assertFalse(Drawing::htmlToRGB('0'));
        $this->assertFalse(Drawing::htmlToRGB('00'));
        $this->assertFalse(Drawing::htmlToRGB('0000'));
        $this->assertFalse(Drawing::htmlToRGB('00000'));

        $this->assertInternalType('array', Drawing::htmlToRGB('ABCDEF'));
        $this->assertCount(3, Drawing::htmlToRGB('ABCDEF'));
        $this->assertEquals(array(0xAB, 0xCD, 0xEF), Drawing::htmlToRGB('ABCDEF'));
        $this->assertEquals(array(0xAB, 0xCD, 0xEF), Drawing::htmlToRGB('#ABCDEF'));
        $this->assertEquals(array(0xAA, 0xBB, 0xCC), Drawing::htmlToRGB('ABC'));
        $this->assertEquals(array(0xAA, 0xBB, 0xCC), Drawing::htmlToRGB('#ABC'));
    }
}
