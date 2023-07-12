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
        /** @var callable */
        $callback = [self::class, 'editContent'];
        $writer->setEditHtmlCallback($callback);
        $writer->save($file);

        self::assertFileExists($file);

        unlink($file);
    }

    // add a footer
    public static function editContent(string $html): string
    {
        $afterBody = '<htmlpagefooter name="myFooter1"><div style=\'text-align: right; font-family: "Poor Richard", serif; font-size: 18pt;\'>{PAGENO}</div></htmlpagefooter>';
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
}
