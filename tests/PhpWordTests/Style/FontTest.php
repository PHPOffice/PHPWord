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

namespace PhpOffice\PhpWordTests\Style;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Language;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Style\Font.
 *
 * @runTestsInSeparateProcesses
 */
class FontTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tear down after each test.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test initiation for style type and paragraph style.
     */
    public function testInitiation(): void
    {
        $object = new Font('text', ['alignment' => Jc::BOTH]);

        self::assertEquals('text', $object->getStyleType());
        self::assertInstanceOf(\PhpOffice\PhpWord\Style\Paragraph::class, $object->getParagraph());
        self::assertIsArray($object->getStyleValues());
    }

    /**
     * Test setting style values with null or empty value.
     */
    public function testSetStyleValueWithNullOrEmpty(): void
    {
        $object = new Font();

        $attributes = [
            'name' => null,
            'size' => null,
            'hint' => null,
            'color' => null,
            'bold' => false,
            'italic' => false,
            'underline' => Font::UNDERLINE_NONE,
            'superScript' => false,
            'subScript' => false,
            'strikethrough' => false,
            'doubleStrikethrough' => false,
            'smallCaps' => false,
            'allCaps' => false,
            'rtl' => false,
            'fgColor' => null,
            'bgColor' => null,
            'scale' => null,
            'spacing' => null,
            'kerning' => null,
            'lang' => null,
            'hidden' => false,
        ];
        foreach ($attributes as $key => $default) {
            $get = is_bool($default) ? "is{$key}" : "get{$key}";
            self::assertEquals($default, $object->$get());
            $object->setStyleValue($key, null);
            self::assertEquals($default, $object->$get());
            $object->setStyleValue($key, '');
            self::assertEquals($default, $object->$get());
        }
    }

    /**
     * Test setting style values with normal value.
     */
    public function testSetStyleValueNormal(): void
    {
        $object = new Font();

        $attributes = [
            'name' => 'Times New Roman',
            'size' => 9,
            'color' => '999999',
            'hint' => 'eastAsia',
            'bold' => true,
            'italic' => true,
            'underline' => Font::UNDERLINE_HEAVY,
            'superScript' => true,
            'subScript' => false,
            'strikethrough' => true,
            'doubleStrikethrough' => false,
            'smallCaps' => true,
            'allCaps' => false,
            'fgColor' => Font::FGCOLOR_YELLOW,
            'bgColor' => 'FFFF00',
            'lineHeight' => 2,
            'scale' => 150,
            'spacing' => 240,
            'kerning' => 10,
            'rtl' => true,
            'noProof' => true,
            'lang' => new Language(Language::EN_US),
            'hidden' => true,
        ];
        $object->setStyleByArray($attributes);
        foreach ($attributes as $key => $value) {
            $get = is_bool($value) ? "is{$key}" : "get{$key}";
            self::assertEquals($value, $object->$get());
        }
    }

    /**
     * Test set line height.
     */
    public function testLineHeight(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Test style array
        $text = $section->addText('This is a test', ['line-height' => 2.0]);

        $doc = TestHelperDOCX::getDocument($phpWord);
        $element = $doc->getElement('/w:document/w:body/w:p/w:pPr/w:spacing');

        $lineHeight = $element->getAttribute('w:line');
        $lineRule = $element->getAttribute('w:lineRule');

        self::assertEquals(480, $lineHeight);
        self::assertEquals('auto', $lineRule);

        // Test setter
        $text->getFontStyle()->setLineHeight(3.0);
        $doc = TestHelperDOCX::getDocument($phpWord);
        $element = $doc->getElement('/w:document/w:body/w:p/w:pPr/w:spacing');

        $lineHeight = $element->getAttribute('w:line');
        $lineRule = $element->getAttribute('w:lineRule');

        self::assertEquals(720, $lineHeight);
        self::assertEquals('auto', $lineRule);
    }

    /**
     * Test line height floatval.
     */
    public function testLineHeightFloatval(): void
    {
        $object = new Font(null, ['alignment' => Jc::CENTER]);
        $object->setLineHeight('1.5pt');
        self::assertEquals(1.5, $object->getLineHeight());
    }

    /**
     * Test line height exception by using nonnumeric value.
     */
    public function testLineHeightException(): void
    {
        $this->expectException(\PhpOffice\PhpWord\Exception\InvalidStyleException::class);
        $object = new Font();
        $object->setLineHeight('a');
    }

    /**
     * Test setting the language as a string.
     */
    public function testSetLangAsString(): void
    {
        $object = new Font();
        $object->setLang(Language::FR_BE);
        self::assertInstanceOf('PhpOffice\PhpWord\Style\Language', $object->getLang());
        self::assertEquals(Language::FR_BE, $object->getLang()->getLatin());
    }
}
