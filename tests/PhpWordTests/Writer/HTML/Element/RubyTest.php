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

namespace PhpOffice\PhpWordTests\Writer\HTML\Element;

use DOMXPath;
use PhpOffice\PhpWord\ComplexType\RubyProperties;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWordTests\Writer\HTML\Helper;
use PHPUnit\Framework\TestCase;

class RubyTest extends TestCase
{
    /**
     * Tests writing ruby HTML.
     */
    public function testWriteRubyHtml(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $properties = new RubyProperties();
        $properties->setAlignment(RubyProperties::ALIGNMENT_CENTER);
        $properties->setFontFaceSize(10);
        $properties->setFontPointsAboveBaseText(4);
        $properties->setFontSizeForBaseText(20);
        $properties->setLanguageId('ja-JP');

        $baseTextRun = new TextRun(null);
        $baseTextRun->addText('私');
        $rubyTextRun = new TextRun(null);
        $rubyTextRun->addText('わたし');
        $section->addRuby($baseTextRun, $rubyTextRun, $properties);

        $dom = Helper::getAsHTML($phpWord, '', '', ['ruby', 'rt', 'rp']);
        $xpath = new DOMXPath($dom);
        self::assertEquals(1, $xpath->query('/html/body/div/ruby')->length);
        // ensure text is right
        $rubyElement = $dom->getElementsByTagName('ruby')->item(0);
        $rtElement = $dom->getElementsByTagName('rt')->item(0);
        self::assertNotNull($rubyElement);
        self::assertNotNull($rtElement);
        self::assertEquals($baseTextRun->getText() . ' (' . $rubyTextRun->getText() . ')', $rubyElement->textContent);
        self::assertEquals($rubyTextRun->getText(), $rtElement->textContent);
        // check style
        self::assertEquals('font-size:20pt;ruby-align:center;', $rubyElement->attributes->getNamedItem('style')->textContent);
        self::assertEquals('font-size:10pt;', $rtElement->attributes->getNamedItem('style')->textContent);
    }

    /**
     * Tests writing ruby HTML.
     */
    public function testWriteRubyHtmlParagraphStyle(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $properties = new RubyProperties();
        $properties->setAlignment(RubyProperties::ALIGNMENT_CENTER);
        $properties->setFontFaceSize(10);
        $properties->setFontPointsAboveBaseText(4);
        $properties->setFontSizeForBaseText(20);
        $properties->setLanguageId('ja-JP');

        $baseTextRun = new TextRun(['lineHeight' => '8']);
        $baseTextRun->addText('私');
        $rubyTextRun = new TextRun(['lineHeight' => '4']);
        $rubyTextRun->addText('わたし');
        $section->addRuby($baseTextRun, $rubyTextRun, $properties);

        $dom = Helper::getAsHTML($phpWord, '', '', ['ruby', 'rt', 'rp']);
        $xpath = new DOMXPath($dom);
        self::assertEquals(1, $xpath->query('/html/body/div/ruby')->length);
        // ensure text is right
        $rubyElement = $dom->getElementsByTagName('ruby')->item(0);
        $rtElement = $dom->getElementsByTagName('rt')->item(0);
        self::assertNotNull($rubyElement);
        self::assertNotNull($rtElement);
        self::assertEquals($baseTextRun->getText() . ' (' . $rubyTextRun->getText() . ')', $rubyElement->textContent);
        self::assertEquals($rubyTextRun->getText(), $rtElement->textContent);
        // check style
        self::assertEquals('line-height: 8;font-size:20pt;ruby-align:center;', $rubyElement->attributes->getNamedItem('style')->textContent);
        self::assertEquals('line-height: 4;font-size:10pt;', $rtElement->attributes->getNamedItem('style')->textContent);
    }
}
