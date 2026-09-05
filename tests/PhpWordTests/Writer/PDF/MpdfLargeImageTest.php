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
use PhpOffice\PhpWord\Writer\PDF\MPDF;

/**
 * Test class for PhpOffice\PhpWord\Writer\PDF\MPDF.
 *
 * @runTestsInSeparateProcesses
 */
class MpdfLargeImageTest extends \PHPUnit\Framework\TestCase
{
    /** @var string */
    private $jpegName = '';

    /** @var string */
    private $pdfName = '';

    protected function tearDown(): void
    {
        // Nothing sensible to do if unlink fails.
        if ($this->jpegName !== '') {
            @unlink($this->jpegName);
        }
        if ($this->pdfName !== '') {
            @unlink($this->pdfName);
        }
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
        // Generate a random-noise JPEG (~700-900 KB) that resists JPEG compression.
        // The resulting base64 data URI will be a single HTML line > 900 KB,
        // which exceeds the default pcre.backtrack_limit of 1 000 000.
        $size1 = 1600;
        $size2 = 1200;
        $img = imagecreatetruecolor($size1, $size2);
        if ($img === false) {
            self::fail('imagecreatetruecolor() failed.');
        }
        for ($y = 0; $y < $size2; ++$y) {
            for ($x = 0; $x < $size1; ++$x) {
                imagesetpixel($img, $x, $y, (int) imagecolorallocate($img, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255)));
            }
        }
        ob_start();
        self::assertTrue(imagejpeg($img, null, 95));
        $imageData = ob_get_clean();

        $tmpImage = $this->jpegName = tempnam(sys_get_temp_dir(), 'tmp_');
        file_put_contents($tmpImage, $imageData);

        $pcreBacktrackLimitOld = ini_get('pcre.backtrack_limit');
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Large-image PDF — issue #2876 regression test');
        $section->addImage($tmpImage, ['width' => 400, 'height' => 267]);

        $writer = new MPDF($phpWord);
        $file = $this->pdfName = tempnam(sys_get_temp_dir(), 'tmp_');
        $writer->save($file);

        $pcreBacktrackLimitNew = ini_get('pcre.backtrack_limit');
        self::assertSame($pcreBacktrackLimitOld, $pcreBacktrackLimitNew);
        self::assertFileExists($file);
        self::assertGreaterThan(0, filesize($file));

        $longLine = false;
        $maxLen = (int) $pcreBacktrackLimitOld;
        $html = $writer->getContent();
        foreach (explode("\n", $html) as $line) {
            if (strlen($line) > $maxLen) {
                $longLine = true;

                break;
            }
        }
        self::assertTrue($longLine);
    }
}
