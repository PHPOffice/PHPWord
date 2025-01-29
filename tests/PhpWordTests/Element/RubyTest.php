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

namespace PhpOffice\PhpWordTests\Element;

use PhpOffice\PhpWord\ComplexType\RubyProperties;
use PhpOffice\PhpWord\Element\Ruby;
use PhpOffice\PhpWord\Element\TextRun;

/**
 * Test class for PhpOffice\PhpWord\Element\Text.
 *
 * @runTestsInSeparateProcesses
 */
class RubyTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test new instance.
     */
    public function testConstruct(): void
    {
        $ruby = new Ruby(new TextRun(), new TextRun(), new RubyProperties());

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Ruby', $ruby);
        self::assertEquals('', $ruby->getBaseTextRun()->getText());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\TextRun', $ruby->getBaseTextRun());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $ruby->getBaseTextRun()->getParagraphStyle());
        self::assertEquals('', $ruby->getRubyTextRun()->getText());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\TextRun', $ruby->getRubyTextRun());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $ruby->getRubyTextRun()->getParagraphStyle());
        self::assertInstanceOf('PhpOffice\\PhpWord\\ComplexType\\RubyProperties', $ruby->getProperties());
        self::assertEquals(RubyProperties::ALIGNMENT_DISTRIBUTE_SPACE, $ruby->getProperties()->getAlignment());
    }

    /**
     * Get/set base text.
     */
    public function testBaseText(): void
    {
        $ruby = new Ruby(new TextRun(), new TextRun(), new RubyProperties());

        self::assertEquals('', $ruby->getBaseTextRun()->getText());
        $tr = new TextRun();
        $tr->addText('Hello, world');
        $ruby->setBaseTextRun($tr);
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\TextRun', $ruby->getBaseTextRun());
        self::assertEquals('Hello, world', $ruby->getBaseTextRun()->getText());
    }

    /**
     * Get/set ruby text.
     */
    public function testRubyText(): void
    {
        $ruby = new Ruby(new TextRun(), new TextRun(), new RubyProperties());

        self::assertEquals('', $ruby->getRubyTextRun()->getText());
        $tr = new TextRun();
        $tr->addText('Hello, ruby');
        $ruby->setRubyTextRun($tr);
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\TextRun', $ruby->getRubyTextRun());
        self::assertEquals('Hello, ruby', $ruby->getRubyTextRun()->getText());
    }

    /**
     * Get/set ruby properties.
     */
    public function testRubyProperties(): void
    {
        $ruby = new Ruby(new TextRun(), new TextRun(), new RubyProperties());

        self::assertEquals(RubyProperties::ALIGNMENT_DISTRIBUTE_SPACE, $ruby->getProperties()->getAlignment());

        $properties = new RubyProperties();
        $properties->setAlignment(RubyProperties::ALIGNMENT_RIGHT_VERTICAL);
        $properties->setFontFaceSize(1);
        $properties->setFontPointsAboveBaseText(2);
        $properties->setFontSizeForBaseText(3);
        $properties->setLanguageId('en-US');
        $ruby->setProperties($properties);

        self::assertInstanceOf('PhpOffice\\PhpWord\\ComplexType\\RubyProperties', $ruby->getProperties());
        self::assertEquals(RubyProperties::ALIGNMENT_RIGHT_VERTICAL, $ruby->getProperties()->getAlignment());
        self::assertEquals(1, $ruby->getProperties()->getFontFaceSize());
        self::assertEquals(2, $ruby->getProperties()->getFontPointsAboveBaseText());
        self::assertEquals(3, $ruby->getProperties()->getFontSizeForBaseText());
        self::assertEquals('en-US', $ruby->getProperties()->getLanguageId());
    }
}
