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
        $this->assertTrue(Settings::hasCompatibility());
        $this->assertTrue(Settings::setCompatibility(false));
        $this->assertFalse(Settings::hasCompatibility());
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
