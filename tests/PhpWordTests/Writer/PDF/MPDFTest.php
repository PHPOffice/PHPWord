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
 * @see        https://github.com/PHPOffice/PHPWord
 *
 * @license    http://www.gnu.org/licenses/lgpl.txt LGPL version 3
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
     * Test that a large embedded image does not trigger the pcre.backtrack_limit error (issue #2876).
     *
     * PHPWord embeds images as base64 data URIs on a single HTML line. When the image is large
     * the line exceeds pcre.backtrack_limit and Mpdf refuses the WriteHTML call. The fix must
     * handle this gracefully without requiring the caller to raise pcre.backtrack_limit.
     */
    public function testLargeImageDoesNotExceedBacktrackLimit(): void
    {
        if (!extension_loaded('gd')) {
            self::markTestSkipped('GD extension required to generate a large test image.');
        }

        $file = __DIR__ . '/../../_files/mpdf_large_image.pdf';

        // Generate a random-noise JPEG (~700-900 KB) that resists JPEG compression.
        // The resulting base64 data URI will be a single HTML line > 900 KB,
        // which exceeds the default pcre.backtrack_limit of 1 000 000.
        $img = imagecreatetruecolor(1600, 1200);
        if ($img === false) {
            self::markTestSkipped('imagecreatetruecolor() failed.');
        }
        for ($y = 0; $y < 1200; ++$y) {
            for ($x = 0; $x < 1600; ++$x) {
                imagesetpixel($img, $x, $y, (int) imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255)));
            }
        }
        ob_start();
        imagejpeg($img, null, 95);
        $imageData = (string) ob_get_clean();

        $tmpImage = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'phpword_test_large_2876.jpg';
        file_put_contents($tmpImage, $imageData);

        $testLimit = 1000000;
        $origLimitStr = (string) ini_get('pcre.backtrack_limit');
        ini_set('pcre.backtrack_limit', (string) $testLimit);

        try {
            self::assertGreaterThan($testLimit, strlen(base64_encode($imageData)), 'Test image base64 must exceed pcre.backtrack_limit to exercise the fix');

            $phpWord = new PhpWord();
            $section = $phpWord->addSection();
            $section->addText('Large-image PDF — issue #2876 regression test');
            $section->addImage($tmpImage, ['width' => 400, 'height' => 267]);

            $writer = new MPDF($phpWord);
            $writer->save($file);

            self::assertFileExists($file);
            self::assertGreaterThan(0, filesize($file));
            self::assertSame($testLimit, (int) ini_get('pcre.backtrack_limit'));
        } finally {
            ini_set('pcre.backtrack_limit', $origLimitStr);
            @unlink($tmpImage);
            if (file_exists($file)) {
                unlink($file);
            }
        }
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

    /**
     * Regression test for #2876: large inline Base64 images used to exhaust
     * pcre.backtrack_limit when the Mpdf writer split the HTML line-by-line.
     */
    public function testWriteLargeEmbeddedImageDoesNotExhaustBacktrackLimit(): void
    {
        if (!extension_loaded('gd')) {
            self::markTestSkipped('GD extension required to generate a test image.');
        }

        $rendererName = Settings::PDF_RENDERER_MPDF;
        $rendererLibraryPath = realpath(PHPWORD_TESTS_BASE_DIR . '/../vendor/mpdf/mpdf');
        Settings::setPdfRenderer($rendererName, $rendererLibraryPath); // @phpstan-ignore-line

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $imagePath = tempnam(sys_get_temp_dir(), 'phpword_') . '.png';
        $gd = imagecreatetruecolor(100, 100);
        if ($gd === false) {
            self::markTestSkipped('imagecreatetruecolor() failed.');
        }
        imagepng($gd, $imagePath);
        imagedestroy($gd);

        $section->addImage($imagePath);

        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'PDF');
        $outputPath = tempnam(sys_get_temp_dir(), 'phpword_') . '.pdf';

        $writer->save($outputPath);

        self::assertFileExists($outputPath);
        self::assertGreaterThan(0, filesize($outputPath));

        unlink($imagePath);
        unlink($outputPath);
    }
}
