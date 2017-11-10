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
 * @copyright   2010-2017 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord;

/**
 * Test class for PhpOffice\PhpWord\Settings
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Settings
 * @runTestsInSeparateProcesses
 */
class SettingsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test set/get compatibity option
     */
    public function testSetGetCompatibility()
    {
        $this->assertTrue(Settings::hasCompatibility());
        $this->assertTrue(Settings::setCompatibility(false));
        $this->assertFalse(Settings::hasCompatibility());
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

    /**
     * @covers ::getTempDir
     * @test
     */
    public function testPhpTempDirIsUsedByDefault()
    {
        $this->assertEquals(sys_get_temp_dir(), Settings::getTempDir());
    }

    /**
     * @covers ::setTempDir
     * @covers ::getTempDir
     * @depends testPhpTempDirIsUsedByDefault
     * @test
     */
    public function testTempDirCanBeSet()
    {
        $userDefinedTempDir = 'C:\PhpWordTemp';
        Settings::setTempDir($userDefinedTempDir);
        $currentTempDir = Settings::getTempDir();
        $this->assertEquals($userDefinedTempDir, $currentTempDir);
        $this->assertNotEquals(sys_get_temp_dir(), $currentTempDir);
    }

    /**
     * Test set/get default font name
     */
    public function testSetGetDefaultFontName()
    {
        $this->assertEquals(Settings::DEFAULT_FONT_NAME, Settings::getDefaultFontName());
        $this->assertTrue(Settings::setDefaultFontName('Times New Roman'));
        $this->assertFalse(Settings::setDefaultFontName(' '));
    }

    /**
     * Test set/get default font size
     */
    public function testSetGetDefaultFontSize()
    {
        $this->assertEquals(Settings::DEFAULT_FONT_SIZE, Settings::getDefaultFontSize());
        $this->assertTrue(Settings::setDefaultFontSize(12));
        $this->assertFalse(Settings::setDefaultFontSize(null));
    }

    /**
     * Test load config
     */
    public function testLoadConfig()
    {
        $expected = array(
            'compatibility'         => true,
            'zipClass'              => 'ZipArchive',
            'pdfRendererName'       => 'DomPDF',
            'pdfRendererPath'       => '',
            'defaultFontName'       => 'Arial',
            'defaultFontSize'       => 10,
            'outputEscapingEnabled' => false,
        );

        // Test default value
        $this->assertEquals($expected, Settings::loadConfig());

        // Test with valid file
        $this->assertEquals($expected, Settings::loadConfig(__DIR__ . '/../../phpword.ini.dist'));

        // Test with invalid file
        $this->assertEmpty(Settings::loadConfig(__DIR__ . '/../../phpunit.xml.dist'));
    }
}
