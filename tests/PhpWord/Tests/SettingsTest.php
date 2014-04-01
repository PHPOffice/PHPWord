<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests;

use PhpOffice\PhpWord\Settings;

/**
 * Test class for PhpOffice\PhpWord\Settings
 *
 * @runTestsInSeparateProcesses
 */
class SettingsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Get/set compatibity option
     */
    public function testGetSetCompatibility()
    {
        $this->assertTrue(Settings::getCompatibility());
        $this->assertTrue(Settings::setCompatibility(false));
        $this->assertFalse(Settings::getCompatibility());
        $this->assertFalse(Settings::setCompatibility('Non boolean'));
    }

    /**
     * Get/set zip class
     */
    public function testGetSetZipClass()
    {
        $this->assertEquals(Settings::ZIPARCHIVE, Settings::getZipClass());
        $this->assertTrue(Settings::setZipClass(Settings::PCLZIP));
        $this->assertFalse(Settings::setZipClass('foo'));
    }
}
