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

namespace PhpOffice\PhpWordTests\Writer\PDF;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Writer\PDF;
use PhpOffice\PhpWord\Writer\PDF\MPDF;
use ReflectionMethod;

/**
 * Test class for PhpOffice\PhpWord\Writer\PDF\MPDF.
 */
class MPDFTest extends \PHPUnit\Framework\TestCase
{
    protected function tearDown(): void
    {
        Settings::restoreDefaults();
    }

    /**
     * Test construct.
     */
    public function testConstruct(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Test 1');
        $section->addPageBreak();
        $section->addText('Test 2');
        $oSettings = new \PhpOffice\PhpWord\Style\Section();
        $oSettings->setSettingValue('orientation', 'landscape');
        $section = $phpWord->addSection($oSettings);
        $section->addText('Section 2 - landscape');

        $writer = new MPDF($phpWord);
        $writer->setFont('xyz');
        ob_start();
        $writer->save('php://output');
        $contents = (string) ob_get_clean();
        self::assertSame('%PDF', substr($contents, 0, 4));
    }

    public function testEditCallback(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Test 1');
        $section->addPageBreak();
        $section->addText('Test 2');
        $oSettings = new \PhpOffice\PhpWord\Style\Section();
        $oSettings->setSettingValue('orientation', 'landscape');
        $section = $phpWord->addSection($oSettings);
        $section->addText('Section 2 - landscape');

        $writer = new MPDF($phpWord);
        /** @var callable */
        $callback = [self::class, 'cbEditContent'];
        $writer->setEditCallback($callback);
        ob_start();
        $writer->save('php://output');
        $contents = (string) ob_get_clean();
        self::assertSame('%PDF', substr($contents, 0, 4));
    }

    // add a footer
    public static function cbEditContent(string $html): string
    {
        $afterBody = '<htmlpagefooter name="myFooter1"><div style=\'text-align: right;\'>{PAGENO}</div></htmlpagefooter>' . MPDF::SIMULATED_BODY_START;
        $beforeBody = '<style>@page page1 {odd-footer-name: html_myFooter1;}</style>';
        $needle = '</head>';
        $pos = strpos($html, $needle);
        if ($pos !== false) {
            $html = (string) substr_replace($html, "$beforeBody\n$needle", $pos, strlen($needle));
        }
        $needle = '<body>';
        $pos = strpos($html, $needle);
        if ($pos !== false) {
            $html = (string) substr_replace($html, "$needle\n$afterBody", $pos, strlen($needle));
        }

        return $html;
    }

    /**
     * Test set/get abstract renderer options.
     */
    public function testSetGetAbstractRendererOptions(): void
    {
        $rendererName = Settings::PDF_RENDERER_MPDF;
        $rendererLibraryPath = realpath(PHPWORD_TESTS_BASE_DIR . '/../vendor/mpdf/mpdf');
        self::assertNotFalse($rendererLibraryPath);
        Settings::setPdfRenderer($rendererName, $rendererLibraryPath);
        Settings::setPdfRendererOptions([
            'font' => 'Arial',
        ]);
        $writer = new PDF(new PhpWord());
        self::assertEquals('Arial', $writer->getFont());
    }

    /**
     * Test that the mPDF temporary directory can be set via the renderer options.
     */
    public function testSetTempDir(): void
    {
        $rendererName = Settings::PDF_RENDERER_MPDF;
        $rendererLibraryPath = (string) realpath(PHPWORD_TESTS_BASE_DIR . '/../vendor/mpdf/mpdf');
        Settings::setPdfRenderer($rendererName, $rendererLibraryPath);

        $writer = new MPDF(new PhpWord());
        $method = new ReflectionMethod(MPDF::class, 'createExternalWriterInstance');
        if (PHP_VERSION_ID < 80100) {
            $method->setAccessible(true);
        }

        // Defaults to the system temporary directory.
        self::assertSame(sys_get_temp_dir() . '/mpdf', $method->invoke($writer)->tempDir);

        // Can be overridden via the PDF renderer options.
        Settings::setPdfRendererOptions([
            'tempDir' => sys_get_temp_dir() . '/phpword-mpdf',
        ]);
        self::assertSame(sys_get_temp_dir() . '/phpword-mpdf', $method->invoke($writer)->tempDir);
    }
}
