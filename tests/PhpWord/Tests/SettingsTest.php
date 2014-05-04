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
     * Test set/get compatibity option
     */
    public function testSetGetCompatibility()
    {
        $this->assertTrue(Settings::getCompatibility());
        $this->assertTrue(Settings::setCompatibility(false));
        $this->assertFalse(Settings::getCompatibility());
        $this->assertFalse(Settings::setCompatibility('Non boolean'));
    }

    /**
     * Test set/get zip class
     */
    public function testSetGetZipClass()
    {
        $this->assertEquals(Settings::ZIPARCHIVE, Settings::getZipClass());
        $this->assertTrue(Settings::setZipClass(Settings::PCLZIP));
        $this->assertFalse(Settings::setZipClass('foo'));
    }

    /**
     * Test set/get PDF renderer
     */
    public function testSetGetPdfRenderer()
    {
        $domPdfPath = realpath(PHPWORD_TESTS_BASE_DIR . '/../vendor/dompdf/dompdf');

        $this->assertFalse(Settings::setPdfRenderer('FOO', 'dummy/path'));
        $this->assertTrue(Settings::setPdfRenderer(Settings::PDF_RENDERER_DOMPDF, $domPdfPath));
        $this->assertEquals(Settings::PDF_RENDERER_DOMPDF, Settings::getPdfRendererName());
        $this->assertEquals($domPdfPath, Settings::getPdfRendererPath());
        $this->assertFalse(Settings::setPdfRendererPath('dummy/path'));
    }

    /**
     * Test set/get measurement unit
     */
    public function testSetGetMeasurementUnit()
    {
        $this->assertEquals(Settings::UNIT_TWIP, Settings::getMeasurementUnit());
        $this->assertTrue(Settings::setMeasurementUnit(Settings::UNIT_INCH));
        $this->assertFalse(Settings::setMeasurementUnit('foo'));
    }
}
