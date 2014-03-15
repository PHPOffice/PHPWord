<?php
namespace PHPWord\Tests\Shared;

use PHPWord_Shared_Drawing;

/**
 * Class DrawingTest
 *
 * @package PHPWord\Tests
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
            $result = PHPWord_Shared_Drawing::pixelsToEMU($value);
            $this->assertEquals(round($value * 9525), $result);

            $result = PHPWord_Shared_Drawing::EMUToPixels($value);
            $this->assertEquals(round($value / 9525), $result);

            $result = PHPWord_Shared_Drawing::pixelsToPoints($value);
            $this->assertEquals($value * 0.67777777, $result);

            $result = PHPWord_Shared_Drawing::pointsToPixels($value);
            $this->assertEquals($value * 1.333333333, $result);

            $result = PHPWord_Shared_Drawing::degreesToAngle($value);
            $this->assertEquals((int)round($value * 60000), $result);

            $result = PHPWord_Shared_Drawing::angleToDegrees($value);
            $this->assertEquals(round($value / 60000), $result);

            $result = PHPWord_Shared_Drawing::pixelsToCentimeters($value);
            $this->assertEquals($value * 0.028, $result);

            $result = PHPWord_Shared_Drawing::centimetersToPixels($value);
            $this->assertEquals($value / 0.028, $result);
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
            $result = PHPWord_Shared_Drawing::htmlToRGB($value[0]);
            $this->assertEquals($value[1], $result);
        }
    }
}
