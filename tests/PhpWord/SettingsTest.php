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

namespace PhpOffice\PhpWord;

/**
 * Test class for PhpOffice\PhpWord\Settings
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Settings
 * @runTestsInSeparateProcesses
 */
class SettingsTest extends \PHPUnit\Framework\TestCase
{
    private $compatibility;
    private $defaultFontSize;
    private $defaultFontName;
    private $defaultPaper;
    private $measurementUnit;
    private $outputEscapingEnabled;
    private $pdfRendererName;
    private $pdfRendererPath;
    private $tempDir;
    private $zipClass;

    public function setUp()
    {
        $this->compatibility = Settings::hasCompatibility();
        $this->defaultFontSize = Settings::getDefaultFontSize();
        $this->defaultFontName = Settings::getDefaultFontName();
        $this->defaultPaper = Settings::getDefaultPaper();
        $this->measurementUnit = Settings::getMeasurementUnit();
        $this->outputEscapingEnabled = Settings::isOutputEscapingEnabled();
        $this->pdfRendererName = Settings::getPdfRendererName();
        $this->pdfRendererPath = Settings::getPdfRendererPath();
        $this->tempDir = Settings::getTempDir();
        $this->zipClass = Settings::getZipClass();
    }

    public function tearDown()
    {
        Settings::setCompatibility($this->compatibility);
        Settings::setDefaultFontSize($this->defaultFontSize);
        Settings::setDefaultFontName($this->defaultFontName);
        Settings::setDefaultPaper($this->defaultPaper);
        Settings::setMeasurementUnit($this->measurementUnit);
        Settings::setOutputEscapingEnabled($this->outputEscapingEnabled);
        Settings::setPdfRendererName($this->pdfRendererName);
        Settings::setPdfRendererPath($this->pdfRendererPath);
        Settings::setTempDir($this->tempDir);
        Settings::setZipClass($this->zipClass);
    }

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
     * Test set/get outputEscapingEnabled option
     */
    public function testSetGetOutputEscapingEnabled()
    {
        $this->assertFalse(Settings::isOutputEscapingEnabled());
        Settings::setOutputEscapingEnabled(true);
        $this->assertTrue(Settings::isOutputEscapingEnabled());
    }

    /**
     * Test set/get zip class
     */
    public function testSetGetZipClass()
    {
        $this->assertEquals(Settings::ZIPARCHIVE, Settings::getZipClass());
        $this->assertFalse(Settings::setZipClass('foo'));
        $this->assertEquals(Settings::ZIPARCHIVE, Settings::getZipClass());
        $this->assertTrue(Settings::setZipClass(Settings::PCLZIP));
        $this->assertEquals(Settings::getZipClass(), Settings::PCLZIP);
        $this->assertFalse(Settings::setZipClass('foo'));
        $this->assertEquals(Settings::getZipClass(), Settings::PCLZIP);
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
        $this->assertEquals($domPdfPath, Settings::getPdfRendererPath());
    }

    /**
     * Test set/get measurement unit
     */
    public function testSetGetMeasurementUnit()
    {
        $this->assertEquals(Settings::UNIT_TWIP, Settings::getMeasurementUnit());
        $this->assertFalse(Settings::setMeasurementUnit('foo'));
        $this->assertEquals(Settings::UNIT_TWIP, Settings::getMeasurementUnit());
        $this->assertTrue(Settings::setMeasurementUnit(Settings::UNIT_INCH));
        $this->assertEquals(Settings::UNIT_INCH, Settings::getMeasurementUnit());
        $this->assertFalse(Settings::setMeasurementUnit('foo'));
        $this->assertEquals(Settings::UNIT_INCH, Settings::getMeasurementUnit());
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
        $this->assertFalse(Settings::setDefaultFontName(' '));
        $this->assertEquals(Settings::DEFAULT_FONT_NAME, Settings::getDefaultFontName());
        $this->assertTrue(Settings::setDefaultFontName('Times New Roman'));
        $this->assertEquals('Times New Roman', Settings::getDefaultFontName());
        $this->assertFalse(Settings::setDefaultFontName(' '));
        $this->assertEquals('Times New Roman', Settings::getDefaultFontName());
    }

    /**
     * Test set/get default font size
     */
    public function testSetGetDefaultFontSize()
    {
        $this->assertEquals(Settings::DEFAULT_FONT_SIZE, Settings::getDefaultFontSize());
        $this->assertFalse(Settings::setDefaultFontSize(null));
        $this->assertEquals(Settings::DEFAULT_FONT_SIZE, Settings::getDefaultFontSize());
        $this->assertTrue(Settings::setDefaultFontSize(12));
        $this->assertEquals(12, Settings::getDefaultFontSize());
        $this->assertFalse(Settings::setDefaultFontSize(null));
        $this->assertEquals(12, Settings::getDefaultFontSize());
    }

    /**
     * Test set/get default paper
     */
    public function testSetGetDefaultPaper()
    {
        $dflt = Settings::DEFAULT_PAPER;
        $chng = ($dflt === 'A4') ? 'Letter' : 'A4';
        $doc = new PhpWord();
        $this->assertEquals($dflt, Settings::getDefaultPaper());
        $sec1 = $doc->addSection();
        $this->assertEquals($dflt, $sec1->getStyle()->getPaperSize());
        $this->assertFalse(Settings::setDefaultPaper(''));
        $this->assertEquals($dflt, Settings::getDefaultPaper());
        $this->assertTrue(Settings::setDefaultPaper($chng));
        $this->assertEquals($chng, Settings::getDefaultPaper());
        $sec2 = $doc->addSection();
        $this->assertEquals($chng, $sec2->getStyle()->getPaperSize());
        $sec3 = $doc->addSection(array('paperSize' => 'Legal'));
        $this->assertEquals('Legal', $sec3->getStyle()->getPaperSize());
        $this->assertFalse(Settings::setDefaultPaper(''));
        $this->assertEquals($chng, Settings::getDefaultPaper());
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
            'defaultPaper'          => 'A4',
        );

        // Test default value
        $this->assertEquals($expected, Settings::loadConfig());

        // Test with valid file
        $this->assertEquals($expected, Settings::loadConfig(__DIR__ . '/../../phpword.ini.dist'));
        foreach ($expected as $key => $value) {
            if ($key === 'compatibility') {
                $meth = 'hasCompatibility';
            } elseif ($key === 'outputEscapingEnabled') {
                $meth = 'isOutputEscapingEnabled';
            } else {
                $meth = 'get' . ucfirst($key);
            }
            $this->assertEquals(Settings::$meth(), $value);
        }

        // Test with invalid file
        $this->assertEmpty(Settings::loadConfig(__DIR__ . '/../../phpunit.xml.dist'));
    }
}
