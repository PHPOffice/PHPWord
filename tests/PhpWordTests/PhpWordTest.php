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

namespace PhpOffice\PhpWordTests;

use BadMethodCallException;
use DateTimeImmutable;
use PhpOffice\PhpWord\Metadata\DocInfo;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Style;

/**
 * Test class for PhpOffice\PhpWord\PhpWord.
 *
 * @runTestsInSeparateProcesses
 */
class PhpWordTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test object creation.
     */
    public function testConstruct(): void
    {
        do {
            $dtStart = new DateTimeImmutable();
            $startSecond = $dtStart->format('s');
            $phpWord = new PhpWord();
            $docInfo = new DocInfo();
            $endSecond = (new DateTimeImmutable('now'))->format('s');
        } while ($startSecond !== $endSecond);
        self::assertEquals($docInfo, $phpWord->getDocInfo());
        self::assertEquals(Settings::DEFAULT_FONT_NAME, $phpWord->getDefaultFontName());
        self::assertEquals(Settings::DEFAULT_FONT_SIZE, $phpWord->getDefaultFontSize());
    }

    /**
     * Test create/get section.
     */
    public function testCreateGetSections(): void
    {
        $phpWord = new PhpWord();
        $phpWord->addSection();
        self::assertCount(1, $phpWord->getSections());
    }

    /**
     * Test set/get default font name.
     */
    public function testSetGetDefaultFontName(): void
    {
        $phpWord = new PhpWord();
        $fontName = 'Times New Roman';
        self::assertEquals(Settings::DEFAULT_FONT_NAME, $phpWord->getDefaultFontName());
        $phpWord->setDefaultFontName($fontName);
        self::assertEquals($fontName, $phpWord->getDefaultFontName());
    }

    /**
     * Test set/get default font size.
     */
    public function testSetGetDefaultFontSize(): void
    {
        $phpWord = new PhpWord();
        $fontSize = 16;
        self::assertEquals(Settings::DEFAULT_FONT_SIZE, $phpWord->getDefaultFontSize());
        $phpWord->setDefaultFontSize($fontSize);
        self::assertEquals($fontSize, $phpWord->getDefaultFontSize());
    }

    /**
     * Test set default paragraph style.
     */
    public function testSetDefaultParagraphStyle(): void
    {
        $phpWord = new PhpWord();
        $phpWord->setDefaultParagraphStyle([]);
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', Style::getStyle('Normal'));
    }

    /**
     * Test add styles.
     */
    public function testAddStyles(): void
    {
        $phpWord = new PhpWord();
        $styles = [
            'Paragraph' => 'Paragraph',
            'Font' => 'Font',
            'Table' => 'Table',
            'Link' => 'Font',
        ];
        foreach ($styles as $key => $value) {
            $method = "add{$key}Style";
            $styleId = "{$key} Style";
            $phpWord->$method($styleId, []);
            self::assertInstanceOf("PhpOffice\\PhpWord\\Style\\{$value}", Style::getStyle($styleId));
        }
    }

    /**
     * Test add title style.
     */
    public function testAddTitleStyle(): void
    {
        $phpWord = new PhpWord();
        $titleLevel = 1;
        $titleName = "Heading_{$titleLevel}";
        $phpWord->addTitleStyle($titleLevel, []);
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', Style::getStyle($titleName));
    }

    /**
     * Test save.
     */
    public function testSave(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Hello world!');
        ob_start();
        self::assertTrue($phpWord->save('test.docx', 'Word2007', true));
        $contents = ob_get_contents();
        self::assertTrue(ob_end_clean());
        self::assertNotEmpty($contents);
    }

    /**
     * Test calling undefined method.
     */
    public function testCallUndefinedMethod(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('is not defined');
        $phpWord = new PhpWord();
        $phpWord->undefinedMethod();
    }

    /**
     * @covers \PhpOffice\PhpWord\PhpWord::getSection
     */
    public function testGetNotExistingSection(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->getSection(0);

        self::assertNull($section);
    }

    /**
     * @covers \PhpOffice\PhpWord\PhpWord::getSection
     */
    public function testGetSection(): void
    {
        $phpWord = new PhpWord();
        $phpWord->addSection();
        $section = $phpWord->getSection(0);

        self::assertNotNull($section);
    }

    /**
     * @covers \PhpOffice\PhpWord\PhpWord::sortSections
     */
    public function testSortSections(): void
    {
        $phpWord = new PhpWord();
        $section1 = $phpWord->addSection();
        $section1->addText('test1');
        $section2 = $phpWord->addSection();
        $section2->addText('test2');
        $section2->addText('test3');

        self::assertEquals(1, $phpWord->getSection(0)->countElements());
        self::assertEquals(2, $phpWord->getSection(1)->countElements());

        $phpWord->sortSections(function ($a, $b) {
            $numElementsInA = $a->countElements();
            $numElementsInB = $b->countElements();
            if ($numElementsInA === $numElementsInB) {
                return 0;
            } elseif ($numElementsInA > $numElementsInB) {
                return -1;
            }

            return 1;
        });

        self::assertEquals(2, $phpWord->getSection(0)->countElements());
        self::assertEquals(1, $phpWord->getSection(1)->countElements());
    }

    /**
     * @covers \PhpOffice\PhpWord\PhpWord::getSettings
     */
    public function testGetSettings(): void
    {
        $phpWord = new PhpWord();
        self::assertInstanceOf('PhpOffice\\PhpWord\\Metadata\\Settings', $phpWord->getSettings());
    }
}
