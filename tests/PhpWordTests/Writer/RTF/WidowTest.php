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
class WidowTest extends \PHPUnit\Framework\TestCase
{
    public function testDefaultWidow(): void
    {
        $phpWord = new PhpWord();
        $section1 = $phpWord->addSection();
        $textRun1 = $section1->addTextRun();
        $textRun1->addText('Section 1 Paragraph 1');
        $writer = new RTF($phpWord);
        $content = $writer->getContent();
        $expected = '\widowctrl';
        self::assertStringNotContainsString($expected, $content, 'should not contain widowctrl');
    }

    public function testTrueWidow(): void
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setRtfWidowControl(true);
        $section1 = $phpWord->addSection();
        $textRun1 = $section1->addTextRun();
        $textRun1->addText('Section 1 Paragraph 1');
        $writer = new RTF($phpWord);
        $content = $writer->getContent();
        $expected = '\deftab720\viewkind1\uc1\widowctrl\lang1036\kerning1\fs20';
        self::assertStringContainsString($expected, $content, 'should contain widowctrl');
    }
}
