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

namespace PhpOffice\PhpWordTests\Writer\RTF\Element;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\RTF as RtfWriter;
use PHPUnit\Framework\TestCase;

class ListItemTest extends TestCase
{
    public function testListItemBasic(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Basic simple bulleted list.');
        $section->addListItem('List Item 1');
        $section->addListItem('List Item 2');
        $section->addListItem('List Item 3');
        $writer = new RtfWriter($phpWord);
        $content = $writer->getContent();
        $content = str_replace("\r\n", "\n", $content);
        $expectedArray = [
            '\pard\widctlpar {Basic simple bulleted list.}\par',
            '\ilvl0\ls1\tx720\fi-360\li720\lin720',
            '{List Item 1}',
            '\par',
            '\ilvl0\ls1\tx720\fi-360\li720\lin720',
            '{List Item 2}',
            '\par',
            '\ilvl0\ls1\tx720\fi-360\li720\lin720',
            '{List Item 3}',
            '\par',
        ];
        $expected = implode("\n", $expectedArray);
        self::assertStringContainsString($expected, $content);
    }

    public function testListItemSingleLevel(): void
    {
        $phpWord = new PhpWord();
        $singlelevelNumberingStyleName = 'singleLevel';
        $phpWord->addNumberingStyle(
            $singlelevelNumberingStyleName,
            [
                'type' => 'singleLevel',
                'levels' => [
                    ['format' => 'decimal', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360, 'restart' => 1],
                ],
            ]
        );
        $section = $phpWord->addSection();
        $section->addText('SingleLevel formatted list.');
        $section->addListItem('List Item 1', 0, null, $singlelevelNumberingStyleName);
        $section->addListItem('List Item 2', 0, null, $singlelevelNumberingStyleName);
        $section->addListItem('List Item 3', 0, null, $singlelevelNumberingStyleName);
        $writer = new RtfWriter($phpWord);
        $content = $writer->getContent();
        $content = str_replace("\r\n", "\n", $content);
        $expectedArray = [
            '\pard\widctlpar {SingleLevel formatted list.}\par',
            '\ilvl0\ls1\tx360\fi-360\li360\lin360',
            '{List Item 1}',
            '\par',
            '\ilvl0\ls1\tx360\fi-360\li360\lin360',
            '{List Item 2}',
            '\par',
            '\ilvl0\ls1\tx360\fi-360\li360\lin360',
            '{List Item 3}',
            '\par',
        ];
        $expected = implode("\n", $expectedArray);
        self::assertStringContainsString($expected, $content);
        self::assertStringContainsString('\levelnorestart1', $content);
        $table = $writer->getListTable();
        self::assertCount(1, $table);
        self::assertSame('singleLevel', $table[0]['type']);
    }
}
