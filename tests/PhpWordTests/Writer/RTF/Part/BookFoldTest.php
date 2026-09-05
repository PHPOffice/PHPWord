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

namespace PhpOffice\PhpWordTests\Writer\RTF;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\RTF;

/**
 * Test class for PhpOffice\PhpWord\Writer\RTF\Style subnamespace.
 */
class BookFoldTest extends \PHPUnit\Framework\TestCase
{
    public function testBookFold(): void
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setBookFoldPrinting(true);
        $section1 = $phpWord->addSection();
        $textRun1 = $section1->addTextRun();
        $textRun1->addText('Section 1 Paragraph 1');
        $writer = new RTF($phpWord);
        $content = $writer->getContent();
        $expected = '\bookfold\landscape';
        self::assertStringContainsString($expected, $content);
    }

    public function testMirrorMargins(): void
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setMirrorMargins(true);
        $section1 = $phpWord->addSection();
        $textRun1 = $section1->addTextRun();
        $textRun1->addText('Section 1 Paragraph 1');
        $writer = new RTF($phpWord);
        $content = $writer->getContent();
        $expected = '\margmirror';
        self::assertStringContainsString($expected, $content);
        $expected = '\facingp';
        self::assertStringContainsString($expected, $content);
    }
}
