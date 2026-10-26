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

namespace PhpOffice\PhpWordTests\Writer\HTML;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\SimpleType\VerticalJc;
use PhpOffice\PhpWord\Writer\HTML;

/**
 * Test class for PhpOffice\PhpWord\Writer\RTF\Style subnamespace.
 */
class Sample36RtlTest extends \PHPUnit\Framework\TestCase
{
    public function testSample36Rtl(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $textrun = $section->addTextRun();
        $textrun->addText('This is a Left to Right paragraph.');

        $textrun = $section->addTextRun(['alignment' => Jc::END]);
        $textrun->addText('سلام این یک پاراگراف راست به چپ است', ['rtl' => true]);

        $section->addText('Table visually presented as RTL');
        $style = ['rtl' => true, 'size' => 12];
        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => '000000',
            'width' => 5000,
            'unit' => TblWidth::PERCENT,
            'bidiVisual' => true,
        ];

        $table = $section->addTable($tableStyle);
        $cellHCentered = ['alignment' => Jc::CENTER];
        $cellHEnd = ['alignment' => Jc::END];
        $cellVCentered = ['valign' => VerticalJc::CENTER];

        //Vidually bidirectinal table
        $table->addRow();
        $cell = $table->addCell(1500, $cellVCentered);
        $textrun = $cell->addTextRun($cellHCentered);
        $textrun->addText('ردیف', $style);

        $cell = $table->addCell(2000);
        $textrun = $cell->addTextRun($cellHEnd);
        $textrun->addText('سوالات', $style);

        $cell = $table->addCell(1000, $cellVCentered);
        $textrun = $cell->addTextRun($cellHCentered);
        $textrun->addText('بارم', $style);
        $writer = new HTML($phpWord);
        $content = $writer->getContent();
        $expected = '<table style="table-layout: auto; direction: rtl;';
        self::assertStringContainsString($expected, $content);
        self::assertSame(6, substr_count($content, 'direction: rtl'));
        self::assertSame(1, substr_count($content, '<table'));
    }
}
