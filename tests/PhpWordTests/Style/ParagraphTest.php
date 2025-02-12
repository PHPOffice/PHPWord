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
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\SimpleType\LineSpacingRule;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\Style\Tab;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Style\Paragraph.
 *
 * @runTestsInSeparateProcesses
 */
class ParagraphTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tear down after each test.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test setting style values with null or empty value.
     */
    public function testSetStyleValueWithNullOrEmpty(): void
    {
        $object = new Paragraph();

        $attributes = [
            'widowControl' => true,
            'keepNext' => false,
            'keepLines' => false,
            'pageBreakBefore' => false,
            'contextualSpacing' => false,
        ];
        foreach ($attributes as $key => $default) {
            $get = $this->findGetter($key, $default, $object);
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
        $object = new Paragraph();

        $attributes = [
            'spaceAfter' => 240,
            'spaceBefore' => 240,
            'indent' => 1,
            'hanging' => 1,
            'spacing' => 120,
            'spacingLineRule' => LineSpacingRule::AT_LEAST,
            'basedOn' => 'Normal',
            'next' => 'Normal',
            'numStyle' => 'numStyle',
            'numLevel' => 1,
            'widowControl' => false,
            'keepNext' => true,
            'keepLines' => true,
            'pageBreakBefore' => true,
            'contextualSpacing' => true,
            'textAlignment' => 'auto',
            'bidi' => true,
            'suppressAutoHyphens' => true,
        ];
        foreach ($attributes as $key => $value) {
            $get = $this->findGetter($key, $value, $object);
            $object->setStyleValue("$key", $value);
            if (('indent' == $key || 'hanging' == $key) && is_numeric($value)) {
                $value = $value * 720;
            }
            self::assertEquals($value, $object->$get());
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param object $object
     *
     * @return string
     */
    private function findGetter($key, $value, $object)
    {
        if (is_bool($value)) {
            if (method_exists($object, "is{$key}")) {
                return "is{$key}";
            } elseif (method_exists($object, "has{$key}")) {
                return "has{$key}";
            }
        }

        return "get{$key}";
    }

    /**
     * Test get null style value.
     */
    public function testGetNullStyleValue(): void
    {
        $object = new Paragraph();

        $attributes = ['spacing', 'indent', 'hanging', 'spaceBefore', 'spaceAfter', 'textAlignment'];
        foreach ($attributes as $key) {
            $get = $this->findGetter($key, null, $object);
            self::assertNull($object->$get());
        }
    }

    /**
     * Test tabs.
     */
    public function testTabs(): void
    {
        $object = new Paragraph();
        $object->setTabs([new Tab('left', 1550), new Tab('right', 5300)]);
        self::assertCount(2, $object->getTabs());
    }

    public function testHanging(): void
    {
        $rand = mt_rand(0, 255);

        $object = new Paragraph();
        self::assertNull($object->getHanging());
        self::assertInstanceOf(Paragraph::class, $object->setHanging($rand));
        self::assertEquals($rand, $object->getHanging());
        self::assertInstanceOf(Paragraph::class, $object->setHanging(null));
        self::assertNull($object->getHanging());
        self::assertInstanceOf(Paragraph::class, $object->setHanging($rand));
        self::assertEquals($rand, $object->getHanging());
        self::assertInstanceOf(Paragraph::class, $object->setHanging());
        self::assertNull($object->getHanging());
    }

    public function testIndent(): void
    {
        $rand = mt_rand(0, 255);

        $object = new Paragraph();
        self::assertNull($object->getIndent());
        self::assertInstanceOf(Paragraph::class, $object->setIndent($rand));
        self::assertEquals($rand, $object->getIndent());
        self::assertInstanceOf(Paragraph::class, $object->setIndent(null));
        self::assertNull($object->getIndent());
        self::assertInstanceOf(Paragraph::class, $object->setIndent($rand));
        self::assertEquals($rand, $object->getIndent());
        self::assertInstanceOf(Paragraph::class, $object->setIndent());
        self::assertNull($object->getIndent());
    }

    public function testIndentation(): void
    {
        $rand = mt_rand(0, 255);
        $rand2 = mt_rand(0, 255);

        $object = new Paragraph();
        self::assertNull($object->getIndentation());
        // Set Basic indentation
        self::assertInstanceOf(Paragraph::class, $object->setIndentation([]));
        self::assertNotNull($object->getIndentation());
        self::assertEquals(0, $object->getIndentation()->getLeft());
        self::assertEquals(0, $object->getIndentation()->getRight());
        self::assertEquals(0, $object->getIndentation()->getHanging());
        self::assertEquals(0, $object->getIndentation()->getFirstLine());
        // Set indentation : left
        self::assertInstanceOf(Paragraph::class, $object->setIndentation([
            'left' => $rand,
        ]));
        self::assertNotNull($object->getIndentation());
        self::assertEquals($rand, $object->getIndentation()->getLeft());
        self::assertEquals(0, $object->getIndentation()->getRight());
        self::assertEquals(0, $object->getIndentation()->getHanging());
        self::assertEquals(0, $object->getIndentation()->getFirstLine());
        // Set indentation : right
        self::assertInstanceOf(Paragraph::class, $object->setIndentation([
            'right' => $rand,
        ]));
        self::assertNotNull($object->getIndentation());
        self::assertEquals($rand, $object->getIndentation()->getLeft());
        self::assertEquals($rand, $object->getIndentation()->getRight());
        self::assertEquals(0, $object->getIndentation()->getHanging());
        self::assertEquals(0, $object->getIndentation()->getFirstLine());
        // Set indentation : hanging
        self::assertInstanceOf(Paragraph::class, $object->setIndentation([
            'hanging' => $rand,
        ]));
        self::assertNotNull($object->getIndentation());
        self::assertEquals($rand, $object->getIndentation()->getLeft());
        self::assertEquals($rand, $object->getIndentation()->getRight());
        self::assertEquals($rand, $object->getIndentation()->getHanging());
        self::assertEquals(0, $object->getIndentation()->getFirstLine());
        // Set indentation : firstline
        self::assertInstanceOf(Paragraph::class, $object->setIndentation([
            'firstline' => $rand,
        ]));
        self::assertNotNull($object->getIndentation());
        self::assertEquals($rand, $object->getIndentation()->getLeft());
        self::assertEquals($rand, $object->getIndentation()->getRight());
        self::assertEquals($rand, $object->getIndentation()->getHanging());
        self::assertEquals($rand, $object->getIndentation()->getFirstLine());
        // Replace indentation : left & firstline
        self::assertInstanceOf(Paragraph::class, $object->setIndentation([
            'left' => $rand2,
            'firstline' => $rand2,
        ]));
        self::assertNotNull($object->getIndentation());
        self::assertEquals($rand2, $object->getIndentation()->getLeft());
        self::assertEquals($rand, $object->getIndentation()->getRight());
        self::assertEquals($rand, $object->getIndentation()->getHanging());
        self::assertEquals($rand2, $object->getIndentation()->getFirstLine());
        // Replace indentation : N/A
        self::assertInstanceOf(Paragraph::class, $object->setIndentation());
        self::assertNotNull($object->getIndentation());
        self::assertEquals($rand2, $object->getIndentation()->getLeft());
        self::assertEquals($rand, $object->getIndentation()->getRight());
        self::assertEquals($rand, $object->getIndentation()->getHanging());
        self::assertEquals($rand2, $object->getIndentation()->getFirstLine());
    }

    public function testIndentFirstLine(): void
    {
        $rand = mt_rand(0, 255);

        $object = new Paragraph();
        self::assertNull($object->getIndentFirstLine());
        self::assertInstanceOf(Paragraph::class, $object->setIndentFirstLine($rand));
        self::assertEquals($rand, $object->getIndentFirstLine());
        self::assertInstanceOf(Paragraph::class, $object->setIndentFirstLine(null));
        self::assertNull($object->getIndentFirstLine());
        self::assertInstanceOf(Paragraph::class, $object->setIndentFirstLine($rand));
        self::assertEquals($rand, $object->getIndentFirstLine());
        self::assertInstanceOf(Paragraph::class, $object->setIndentFirstLine());
        self::assertNull($object->getIndentFirstLine());
    }

    public function testIndentLeft(): void
    {
        $rand = mt_rand(0, 255);

        $object = new Paragraph();
        self::assertNull($object->getIndentLeft());
        self::assertInstanceOf(Paragraph::class, $object->setIndentLeft($rand));
        self::assertEquals($rand, $object->getIndentLeft());
        self::assertInstanceOf(Paragraph::class, $object->setIndentLeft(null));
        self::assertNull($object->getIndentLeft());
        self::assertInstanceOf(Paragraph::class, $object->setIndentLeft($rand));
        self::assertEquals($rand, $object->getIndentLeft());
        self::assertInstanceOf(Paragraph::class, $object->setIndentLeft());
        self::assertNull($object->getIndentLeft());
    }

    public function testIndentRight(): void
    {
        $rand = mt_rand(0, 255);

        $object = new Paragraph();
        self::assertNull($object->getIndentRight());
        self::assertInstanceOf(Paragraph::class, $object->setIndentRight($rand));
        self::assertEquals($rand, $object->getIndentRight());
        self::assertInstanceOf(Paragraph::class, $object->setIndentRight(null));
        self::assertNull($object->getIndentRight());
        self::assertInstanceOf(Paragraph::class, $object->setIndentRight($rand));
        self::assertEquals($rand, $object->getIndentRight());
        self::assertInstanceOf(Paragraph::class, $object->setIndentRight());
        self::assertNull($object->getIndentRight());
    }

    /**
     * Line height.
     */
    public function testLineHeight(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Test style array
        $text = $section->addText('This is a test', [], ['line-height' => 2.0]);

        $doc = TestHelperDOCX::getDocument($phpWord);
        $element = $doc->getElement('/w:document/w:body/w:p/w:pPr/w:spacing');

        $lineHeight = $element->getAttribute('w:line');
        $lineRule = $element->getAttribute('w:lineRule');

        self::assertEquals(480, $lineHeight);
        self::assertEquals('auto', $lineRule);

        // Test setter
        $text->getParagraphStyle()->setLineHeight(3.0);
        TestHelperDOCX::clear();
        $doc = TestHelperDOCX::getDocument($phpWord);
        $element = $doc->getElement('/w:document/w:body/w:p/w:pPr/w:spacing');

        $lineHeight = $element->getAttribute('w:line');
        $lineRule = $element->getAttribute('w:lineRule');

        self::assertEquals(720, $lineHeight);
        self::assertEquals('auto', $lineRule);
    }

    /**
     * Test setLineHeight validation.
     */
    public function testLineHeightValidation(): void
    {
        $object = new Paragraph();
        $object->setLineHeight('12.5pt');
        self::assertEquals(12.5, $object->getLineHeight());
    }

    /**
     * Test line height exception by using nonnumeric value.
     */
    public function testLineHeightException(): void
    {
        $this->expectException(\PhpOffice\PhpWord\Exception\InvalidStyleException::class);
        $object = new Paragraph();
        $object->setLineHeight('a');
    }

    public function testBidiVisual(): void
    {
        $object = new Paragraph();
        self::assertNull($object->isBidi());
        self::assertInstanceOf(Paragraph::class, $object->setBidi(true));
        self::assertTrue($object->isBidi());
        self::assertInstanceOf(Paragraph::class, $object->setBidi(false));
        self::assertFalse($object->isBidi());
        self::assertInstanceOf(Paragraph::class, $object->setBidi(null));
        self::assertNull($object->isBidi());
    }

    public function testBidiVisualSettings(): void
    {
        Settings::setDefaultRtl(null);
        $object = new Paragraph();
        self::assertNull($object->isBidi());

        Settings::setDefaultRtl(true);
        $object = new Paragraph();
        self::assertTrue($object->isBidi());

        Settings::setDefaultRtl(false);
        $object = new Paragraph();
        self::assertFalse($object->isBidi());

        Settings::setDefaultRtl(null);
    }
}
