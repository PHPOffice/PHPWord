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

use PhpOffice\PhpWord\Element\Footer;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Writer\RTF;

/**
 * Test class for PhpOffice\PhpWord\Writer\RTF\Style subnamespace.
 */
class Sample11Test extends \PHPUnit\Framework\TestCase
{
    public function testSample11(): void
    {
        $source = 'samples/resources/Sample_11_ReadWord2007.docx';
        $phpWord = IOFactory::load($source);
        $writer = new RTF($phpWord);
        $content = $writer->getContent();
        $expected = '{\colortbl;\red255\green0\blue0;\red0\green0\blue0;\red0\green0\blue255;\red0\green176\blue80;\red255\green255\blue0;}';
        self::assertStringContainsString($expected, $content);
        $expected = '\highlight5 highlighted';
        self::assertStringContainsString($expected, $content);
        $expected = '\strike even ';
        self::assertStringContainsString($expected, $content);
    }

    public function testBorderColor(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->getStyle()
            ->setBorderSize(5)
            ->setBorderColor('FF00FF');
        $writer = new RTF($phpWord);
        $content = $writer->getContent();
        $expected = '{\colortbl;\red255\green0\blue255;}';
        self::assertStringContainsString($expected, $content);
    }

    public function testFooters(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $phpWord->getSettings()->setEvenAndOddHeaders(true);
        $section = $phpWord->addSection();
        $footerFirst = $section->addFooter(Footer::FIRST);
        $textRunFirst = $footerFirst->addTextRun();
        $textRunFirst->addText('First Footer');
        $footerEven = $section->addFooter(Footer::EVEN);
        $textRunEven = $footerEven->addTextRun();
        $textRunEven->addText('Even Footer');
        $footerAuto = $section->addFooter(Footer::AUTO);
        $textRunAuto = $footerAuto->addTextRun();
        $textRunAuto->addText('Odd Footer');
        $section->addText('This should be page 1');
        $section->addPageBreak();
        $section->addText('This should be page 2');
        $section->addPageBreak();
        $section->addText('This should be page 3');
        $section->addPageBreak();
        $section->addText('This should be page 4');
        $section->addPageBreak();
        $section->addText('This should be page 5');
        $writer = new RTF($phpWord);
        $content = $writer->getContent();
        $expected = '{\footerf\pard\nowidctlpar {{\cf0\f0 First Footer}}\par';
        self::assertStringContainsString($expected, $content);
        $expected = '{\footerl\pard\nowidctlpar {{\cf0\f0 Even Footer}}\par';
        self::assertStringContainsString($expected, $content);
        $expected = '{\footerr\pard\nowidctlpar {{\cf0\f0 Odd Footer}}\par';
        self::assertStringContainsString($expected, $content);
    }

    public function testPageBreakBeforeTextRun(): void
    {
        $phpWord = new PhpWord();
        $phpWord->addParagraphStyle('pbb', [
            'pageBreakBefore' => true,
        ]);
        $section1 = $phpWord->addSection();
        $textRun1 = $section1->addTextRun();
        $textRun1->addText('Section 1 Paragraph 1');
        $section2 = $phpWord->addSection();
        $textRun2 = $section2->addTextRun('pbb');
        $textRun2->addText('Section 2 Paragraph 1');
        $textRun3 = $section2->addTextRun('pbb');
        $textRun3->addText('Section 2 Paragraph 2');
        $writer = new RTF($phpWord);
        $content = $writer->getContent();
        $expected = '\pard\nowidctlpar {{\cf0\f0 Section 2 Paragraph 1}}\par';
        self::assertStringContainsString($expected, $content, 'no page break');
        $expected = '\pard\nowidctlpar \page{{\cf0\f0 Section 2 Paragraph 2}}\par';
        self::assertStringContainsString($expected, $content, 'page break');
    }

    public function testPageBreakBeforeTitle(): void
    {
        $phpWord = new PhpWord();
        $pbb = ['pageBreakBefore' => true];
        $phpWord->addTitleStyle(
            1,
            ['bold' => true],
            $pbb
        );
        $section1 = $phpWord->addSection();
        $textRun1 = $section1->addTextRun();
        $textRun1->addText('Section 1 Paragraph 1');
        $section2 = $phpWord->addSection();
        $section2->addTitle('Heading1 with pbb first element in section', 1);
        $section2->addTitle('Heading1 with pbb not first element in section', 1);
        $writer = new RTF($phpWord);
        $content = $writer->getContent();
        $expected = '\pard\nowidctlpar {\outlinelevel0{\cf0\f0\b Heading1 with pbb first element in section}';
        self::assertStringContainsString($expected, $content, 'no page break');
        $expected = '\pard\nowidctlpar \page{\outlinelevel0{\cf0\f0\b Heading1 with pbb not first element in section}';
        self::assertStringContainsString($expected, $content, 'page break');
    }
}
