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

/**
 * Test class for PhpOffice\PhpWord\Writer\PDF\MPDF.
 *
 * @runTestsInSeparateProcesses
 */
class MPDFTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test construct.
     */
    public function testConstruct(): void
    {
        $file = __DIR__ . '/../../_files/mpdf.pdf';

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Test 1');
        $section->addPageBreak();
        $section->addText('Test 2');
        $oSettings = new \PhpOffice\PhpWord\Style\Section();
        $oSettings->setSettingValue('orientation', 'landscape');
        $section = $phpWord->addSection($oSettings); // @phpstan-ignore-line
        $section->addText('Section 2 - landscape');

        $writer = new MPDF($phpWord);
        $writer->save($file);

        self::assertFileExists($file);

        unlink($file);
    }

    public function testEditCallback(): void
    {
        $file = __DIR__ . '/../../_files/mpdf.pdf';

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Test 1');
        $section->addPageBreak();
        $section->addText('Test 2');
        $oSettings = new \PhpOffice\PhpWord\Style\Section();
        $oSettings->setSettingValue('orientation', 'landscape');
        $section = $phpWord->addSection($oSettings); // @phpstan-ignore-line
        $section->addText('Section 2 - landscape');

        $writer = new MPDF($phpWord);
        /** @var callable */
        $callback = [self::class, 'cbEditContent'];
        $writer->setEditCallback($callback);
        $writer->save($file);

        self::assertFileExists($file);

        unlink($file);
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
        Settings::setPdfRenderer($rendererName, $rendererLibraryPath);
        Settings::setPdfRendererOptions([
            'font' => 'Arial',
        ]);
        $writer = new PDF(new PhpWord());
        self::assertEquals('Arial', $writer->getFont());
    }
}
