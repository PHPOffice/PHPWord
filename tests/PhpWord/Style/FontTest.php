<?php
declare(strict_types=1);
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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Style;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Colors\BasicColor;
use PhpOffice\PhpWord\Style\Colors\Hex;
use PhpOffice\PhpWord\Style\Colors\HighlightColor;
use PhpOffice\PhpWord\Style\Lengths\Absolute;
use PhpOffice\PhpWord\Style\Lengths\Percent;
use PhpOffice\PhpWord\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Style\Font
 *
 * @runTestsInSeparateProcesses
 */
class FontTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tear down after each test
     */
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test initiation for style type and paragraph style
     */
    public function testInitiation()
    {
        $object = new Font('text', array('alignment' => Jc::BOTH));

        $this->assertEquals('text', $object->getStyleType());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $object->getParagraphStyle());
        $this->assertInternalType('array', $object->getStyleValues());
    }

    /**
     * Test setting style values with null value
     */
    public function testSetStyleValueWithNull()
    {
        $object = new Font();

        $attributes = array(
            'name'                => null,
            'size'                => new Absolute(null),
            'hint'                => null,
            'color'               => new Hex(null),
            'bold'                => false,
            'italic'              => false,
            'underline'           => Font::UNDERLINE_NONE,
            'superScript'         => false,
            'subScript'           => false,
            'strikethrough'       => false,
            'doubleStrikethrough' => false,
            'smallCaps'           => false,
            'allCaps'             => false,
            'rtl'                 => false,
            'fgColor'             => new HighlightColor(null),
            'bgColor'             => new Hex(null),
            'scale'               => new Percent(null),
            'spacing'             => new Absolute(null),
            'kerning'             => new Absolute(null),
            'lang'                => null,
            'hidden'              => false,
        );
        foreach ($attributes as $key => $default) {
            $get = is_bool($default) ? "is{$key}" : "get{$key}";
            $new = $default;
            if ($key === 'underline') {
                $new = null;
            }
            $this->assertEquals($default, $object->$get(), "Attribute `$key` should start at default");
            $object->setStyleValue($key, $new);
            $this->assertEquals($default, $object->$get(), "Attribute `$key` should remain at default if set to `null`");
        }
    }

    /**
     * Test setting style values with normal value
     */
    public function testSetStyleValueNormal()
    {
        $object = new Font();

        $attributes = array(
            'name'                => 'Times New Roman',
            'size'                => Absolute::from('pt', 9),
            'color'               => new Hex('999999'),
            'hint'                => 'eastAsia',
            'bold'                => true,
            'italic'              => true,
            'underline'           => Font::UNDERLINE_HEAVY,
            'superScript'         => true,
            'subScript'           => false,
            'strikethrough'       => true,
            'doubleStrikethrough' => false,
            'smallCaps'           => true,
            'allCaps'             => false,
            'fgColor'             => new HighlightColor('yellow'),
            'bgColor'             => new Hex('FFFF00'),
            'lineHeight'          => new Percent(200),
            'scale'               => new Percent(150),
            'spacing'             => Absolute::from('twip', 240),
            'kerning'             => Absolute::from('hpt', 10),
            'rtl'                 => true,
            'noProof'             => true,
            'lang'                => new Language(Language::EN_US),
            'hidden'              => true,
        );
        $object->setStyleByArray($attributes);
        foreach ($attributes as $key => $value) {
            $get = is_bool($value) ? "is{$key}" : "get{$key}";
            $result = $object->$get();
            if ($result instanceof BasicColor) {
                $result = $result->toHexOrName();
                $value = $value->toHexOrName();
            } elseif ($result instanceof Absolute) {
                $result = $result->toInt('hpt');
                $value = $value->toInt('hpt');
            }
            $this->assertEquals($value, $result);
        }
    }

    /**
     * Test set line height
     */
    public function testLineHeight()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Test style array
        $text = $section->addText('This is a test', array('line-height' => new Percent(200)));

        $doc = TestHelperDOCX::getDocument($phpWord);
        $element = $doc->getElement('/w:document/w:body/w:p/w:pPr/w:spacing');

        $lineHeight = $element->getAttribute('w:line');
        $lineRule = $element->getAttribute('w:lineRule');

        $this->assertEquals(480, $lineHeight);
        $this->assertEquals('auto', $lineRule);

        // Test setter
        $text->getFontStyle()->setLineHeight(new Percent(300));
        $doc = TestHelperDOCX::getDocument($phpWord);
        $element = $doc->getElement('/w:document/w:body/w:p/w:pPr/w:spacing');

        $lineHeight = $element->getAttribute('w:line');
        $lineRule = $element->getAttribute('w:lineRule');

        $this->assertEquals(720, $lineHeight);
        $this->assertEquals('auto', $lineRule);
    }

    /**
     * Test line height floatval
     */
    public function testLineHeightFloatval()
    {
        $object = new Font(null, array('alignment' => Jc::CENTER));
        $object->setLineHeight(new Percent(1.5));
        $this->assertEquals(1.5, $object->getLineHeight()->toFloat());
    }

    /**
     * Test line height exception by using nonnumeric value
     *
     * @expectedException \TypeError
     */
    public function testLineHeightException()
    {
        $object = new Font();
        $object->setLineHeight('a');
    }

    /**
     * Test setting the language as a string
     */
    public function testSetLangAsString()
    {
        $object = new Font();
        $object->setLang(Language::FR_BE);
        $this->assertInstanceOf('PhpOffice\PhpWord\Style\Language', $object->getLang());
        $this->assertEquals(Language::FR_BE, $object->getLang()->getLatin());
    }
}
