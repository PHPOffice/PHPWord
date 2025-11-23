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

namespace PhpOffice\PhpWordTests\Writer\RTF\Style;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\VerticalJc;
use PhpOffice\PhpWord\Writer\RTF;
use PHPUnit\Framework\TestCase;

class SectionTest extends TestCase
{
    /** @dataProvider verticalAlignProvider */
    public function testVerticalAlign(string $vAlign, string $expect): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $style = $section->getStyle();
        $style->setVAlign($vAlign);
        $writer = new RTF($phpWord);
        self::assertStringContainsString($expect, $writer->getContent());
    }

    public static function verticalAlignProvider(): array
    {
        return [
            [VerticalJc::TOP, '\vertalt'],
            [VerticalJc::CENTER, '\vertalc'],
            [VerticalJc::BOTH, '\vertalj'],
            [VerticalJc::BOTTOM, '\vertalb'],
        ];
    }

    public function testNoVerticalAlignNoBreakType(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $style = $section->getStyle();
        $writer = new RTF($phpWord);
        $content = $writer->getContent();
        self::assertStringNotContainsString('vert', $content);
        self::assertStringNotContainsString('sbk', $content);
    }

    /** @dataProvider breakTypeProvider */
    public function testBreakType(string $breakType, string $expect): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $style = $section->getStyle();
        $style->setBreakType($breakType);
        $writer = new RTF($phpWord);
        $content = $writer->getContent();
        self::assertStringContainsString($expect, $content);
    }

    public static function breakTypeProvider(): array
    {
        return [
            ['nextPage', '\sbkpage'],
            ['nextColumn', '\sbkcol'],
            ['continuous', '\sbknone'],
            ['evenPage', '\sbkeven'],
            ['oddPage', '\sbkodd'],
        ];
    }

    public function testColsNum(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $style = $section->getStyle();
        $style->setColsNum(5);
        $style->setColsSpace(7);
        $writer = new RTF($phpWord);
        $content = $writer->getContent();
        self::assertStringContainsString('\cols5', $content);
        self::assertStringContainsString('\colsx7', $content);
    }

    public function testPageSize(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $temp1 = $section->getStyle()->getOrientation();
        self::assertSame('portrait', $temp1);
        $writer = new RTF($phpWord);
        $content = $writer->getContent();

        self::assertStringContainsString('\sectd \pgwsxn11906\pghsxn16838', $content);
        $temp2 = $section->getStyle()->getOrientation();
        self::assertSame('portrait', $temp2);
    }

    public function testPageSizeBookFold(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $phpWord->getSettings()->setBookFoldPrinting(true);
        $temp1 = $section->getStyle()->getOrientation();
        self::assertSame('portrait', $temp1);
        $writer = new RTF($phpWord);
        $content = $writer->getContent();

        self::assertStringContainsString('\sectd \pgwsxn8419\pghsxn11906', $content);
        $temp2 = $section->getStyle()->getOrientation();
        self::assertSame('landscape', $temp2);
    }
}
