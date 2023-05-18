<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWordTests;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PHPUnit\Framework\TestCase;

/**
 * Test class for PhpOffice\PhpWord\Settings.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Settings
 * @runTestsInSeparateProcesses
 */
class SettingsTest extends TestCase
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

    protected function setUp(): void
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

    protected function tearDown(): void
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
     * Test set/get compatibity option.
     */
    public function testSetGetCompatibility(): void
    {
        self::assertTrue(Settings::hasCompatibility());
        self::assertTrue(Settings::setCompatibility(false));
        self::assertFalse(Settings::hasCompatibility());
    }

    /**
     * Test set/get outputEscapingEnabled option.
     */
    public function testSetGetOutputEscapingEnabled(): void
    {
        self::assertFalse(Settings::isOutputEscapingEnabled());
        Settings::setOutputEscapingEnabled(true);
        self::assertTrue(Settings::isOutputEscapingEnabled());
    }

    /**
     * Test set/get zip class.
     */
    public function testSetGetZipClass(): void
    {
        self::assertEquals(Settings::ZIPARCHIVE, Settings::getZipClass());
        self::assertFalse(Settings::setZipClass('foo'));
        self::assertEquals(Settings::ZIPARCHIVE, Settings::getZipClass());
        self::assertTrue(Settings::setZipClass(Settings::PCLZIP));
        self::assertEquals(Settings::getZipClass(), Settings::PCLZIP);
        self::assertFalse(Settings::setZipClass('foo'));
        self::assertEquals(Settings::getZipClass(), Settings::PCLZIP);
    }

    /**
     * Test set/get PDF renderer.
     */
    public function testSetGetPdfRenderer(): void
    {
        $domPdfPath = realpath(PHPWORD_TESTS_BASE_DIR . '/../vendor/dompdf/dompdf');

        self::assertFalse(Settings::setPdfRenderer('FOO', 'dummy/path'));
        self::assertTrue(Settings::setPdfRenderer(Settings::PDF_RENDERER_DOMPDF, $domPdfPath));
        self::assertEquals(Settings::PDF_RENDERER_DOMPDF, Settings::getPdfRendererName());
        self::assertEquals($domPdfPath, Settings::getPdfRendererPath());
        self::assertFalse(Settings::setPdfRendererPath('dummy/path'));
        self::assertEquals($domPdfPath, Settings::getPdfRendererPath());
    }

    /**
     * Test set/get measurement unit.
     */
    public function testSetGetMeasurementUnit(): void
    {
        self::assertEquals(Settings::UNIT_TWIP, Settings::getMeasurementUnit());
        self::assertFalse(Settings::setMeasurementUnit('foo'));
        self::assertEquals(Settings::UNIT_TWIP, Settings::getMeasurementUnit());
        self::assertTrue(Settings::setMeasurementUnit(Settings::UNIT_INCH));
        self::assertEquals(Settings::UNIT_INCH, Settings::getMeasurementUnit());
        self::assertFalse(Settings::setMeasurementUnit('foo'));
        self::assertEquals(Settings::UNIT_INCH, Settings::getMeasurementUnit());
    }

    /**
     * @covers ::getTempDir
     */
    public function testPhpTempDirIsUsedByDefault(): void
    {
        self::assertEquals(sys_get_temp_dir(), Settings::getTempDir());
    }

    /**
     * @covers ::getTempDir
     * @covers ::setTempDir
     * @depends testPhpTempDirIsUsedByDefault
     */
    public function testTempDirCanBeSet(): void
    {
        $userDefinedTempDir = 'C:\PhpWordTemp';
        Settings::setTempDir($userDefinedTempDir);
        $currentTempDir = Settings::getTempDir();
        self::assertEquals($userDefinedTempDir, $currentTempDir);
        self::assertNotEquals(sys_get_temp_dir(), $currentTempDir);
    }

    /**
     * Test set/get default font name.
     */
    public function testSetGetDefaultFontName(): void
    {
        self::assertEquals(Settings::DEFAULT_FONT_NAME, Settings::getDefaultFontName());
        self::assertFalse(Settings::setDefaultFontName(' '));
        self::assertEquals(Settings::DEFAULT_FONT_NAME, Settings::getDefaultFontName());
        self::assertTrue(Settings::setDefaultFontName('Times New Roman'));
        self::assertEquals('Times New Roman', Settings::getDefaultFontName());
        self::assertFalse(Settings::setDefaultFontName(' '));
        self::assertEquals('Times New Roman', Settings::getDefaultFontName());
    }

    /**
     * Test set/get default font size.
     */
    public function testSetGetDefaultFontSize(): void
    {
        self::assertEquals(Settings::DEFAULT_FONT_SIZE, Settings::getDefaultFontSize());
        self::assertFalse(Settings::setDefaultFontSize(null));
        self::assertEquals(Settings::DEFAULT_FONT_SIZE, Settings::getDefaultFontSize());
        self::assertTrue(Settings::setDefaultFontSize(12));
        self::assertEquals(12, Settings::getDefaultFontSize());
        self::assertFalse(Settings::setDefaultFontSize(null));
        self::assertEquals(12, Settings::getDefaultFontSize());
        self::assertTrue(Settings::setDefaultFontSize(12.5));
        self::assertEquals(12.5, Settings::getDefaultFontSize());
        self::assertFalse(Settings::setDefaultFontSize(0.5));
        self::assertEquals(12.5, Settings::getDefaultFontSize());
        self::assertFalse(Settings::setDefaultFontSize(0));
        self::assertEquals(12.5, Settings::getDefaultFontSize());
    }

    /**
     * Test set/get default paper.
     */
    public function testSetGetDefaultPaper(): void
    {
        $dflt = Settings::DEFAULT_PAPER;
        $chng = ($dflt === 'A4') ? 'Letter' : 'A4';
        $doc = new PhpWord();
        self::assertEquals($dflt, Settings::getDefaultPaper());
        $sec1 = $doc->addSection();
        self::assertEquals($dflt, $sec1->getStyle()->getPaperSize());
        self::assertFalse(Settings::setDefaultPaper(''));
        self::assertEquals($dflt, Settings::getDefaultPaper());
        self::assertTrue(Settings::setDefaultPaper($chng));
        self::assertEquals($chng, Settings::getDefaultPaper());
        $sec2 = $doc->addSection();
        self::assertEquals($chng, $sec2->getStyle()->getPaperSize());
        $sec3 = $doc->addSection(['paperSize' => 'Legal']);
        self::assertEquals('Legal', $sec3->getStyle()->getPaperSize());
        self::assertFalse(Settings::setDefaultPaper(''));
        self::assertEquals($chng, Settings::getDefaultPaper());
    }

    /**
     * Test load config.
     */
    public function testLoadConfig(): void
    {
        $expected = [
            'compatibility' => true,
            'zipClass' => 'ZipArchive',
            'pdfRendererName' => 'DomPDF',
            'pdfRendererPath' => '',
            'defaultFontName' => 'Arial',
            'defaultFontSize' => 10,
            'outputEscapingEnabled' => false,
            'defaultPaper' => 'A4',
        ];

        // Test default value
        self::assertEquals($expected, Settings::loadConfig());

        // Test with valid file
        self::assertEquals($expected, Settings::loadConfig(__DIR__ . '/../../phpword.ini.dist'));
        foreach ($expected as $key => $value) {
            if ($key === 'compatibility') {
                $meth = 'hasCompatibility';
            } elseif ($key === 'outputEscapingEnabled') {
                $meth = 'isOutputEscapingEnabled';
            } else {
                $meth = 'get' . ucfirst($key);
            }
            self::assertEquals(Settings::$meth(), $value);
        }

        // Test with invalid file
        self::assertEmpty(Settings::loadConfig(__DIR__ . '/../../phpunit.xml.dist'));
    }
}
