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
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Tests\Shared;

use PhpOffice\PhpWord\Shared\Drawing;

/**
 * Test class for PhpOffice\PhpWord\Shared\Drawing
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Shared\Drawing
 * @runTestsInSeparateProcesses
 */
class DrawingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test unit conversion functions with various numbers
     */
    public function testUnitConversions()
    {
        $values[] = 0; // zero value
        $values[] = rand(1, 100) / 100; // fraction number
        $values[] = rand(1, 100); // integer

        foreach ($values as $value) {
            $result = Drawing::pixelsToEMU($value);
            $this->assertEquals(round($value * 9525), $result);

            $result = Drawing::emuToPixels($value);
            $this->assertEquals(round($value / 9525), $result);

            $result = Drawing::pixelsToPoints($value);
            $this->assertEquals($value * 0.75, $result);

            $result = Drawing::pointsToPixels($value);
            $this->assertEquals($value * 1.333333333, $result);

            $result = Drawing::degreesToAngle($value);
            $this->assertEquals((int)round($value * 60000), $result);

            $result = Drawing::angleToDegrees($value);
            $this->assertEquals(round($value / 60000), $result);

            $result = Drawing::pixelsToCentimeters($value);
            $this->assertEquals($value * 0.026458333, $result);

            $result = Drawing::centimetersToPixels($value);
            $this->assertEquals($value / 0.026458333, $result);
        }
    }

    /**
     * Test htmlToRGB()
     */
    public function testHtmlToRGB()
    {
        // Prepare test values [ original, expected ]
        $values[] = array('#FF99DD', array(255, 153, 221)); // With #
        $values[] = array('FF99DD', array(255, 153, 221)); // 6 characters
        $values[] = array('F9D', array(255, 153, 221)); // 3 characters
        $values[] = array('0F9D', false); // 4 characters
        // Conduct test
        foreach ($values as $value) {
            $result = Drawing::htmlToRGB($value[0]);
            $this->assertEquals($value[1], $result);
        }
    }
}
