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

namespace PhpOffice\PhpWordTests\Shared;

use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWordTests\AbstractWebServerEmbeddedTest;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Shared\Html.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Shared\Html
 */
class HtmlRtlTest extends AbstractWebServerEmbeddedTest
{
    /**
     * Tear down after each test.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    public function testParseCssDirection(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $html = '<p style="direction: rtl">test1.</p>';
        $html .= '<p style="direction: ltr">test2.</p>';
        $html .= '<p>test3.</p>';
        Html::addHtml($section, $html);
        $elements = $section->getElements();
        self::assertCount(3, $elements);

        $index = 0;
        $element = $elements[$index];
        self::assertInstanceOf(TextRun::class, $element);
        $paragraphStyle = $element->getParagraphStyle();
        self::assertInstanceOf(Paragraph::class, $paragraphStyle);
        self::assertTrue($paragraphStyle->isBidi());
        self::assertSame('tbRl', $paragraphStyle->getTextDirection());
        $textElements = $element->getElements();
        self::assertCount(1, $textElements);
        $textElement = $textElements[0];
        self::assertInstanceOf(Text::class, $textElement);
        self::assertInstanceOf(Font::class, $textElement->getFontStyle());
        self::assertTrue($textElement->getFontStyle()->isRtl());

        $index = 1;
        $element = $elements[$index];
        self::assertInstanceOf(TextRun::class, $element);
        $paragraphStyle = $element->getParagraphStyle();
        self::assertInstanceOf(Paragraph::class, $paragraphStyle);
        self::assertFalse($paragraphStyle->isBidi());
        self::assertSame('lrTb', $paragraphStyle->getTextDirection());
        $textElements = $element->getElements();
        self::assertCount(1, $textElements);
        $textElement = $textElements[0];
        self::assertInstanceOf(Text::class, $textElement);
        self::assertInstanceOf(Font::class, $textElement->getFontStyle());
        self::assertFalse($textElement->getFontStyle()->isRtl());

        $index = 2;
        $element = $elements[$index];
        self::assertInstanceOf(TextRun::class, $element);
        $paragraphStyle = $element->getParagraphStyle();
        self::assertInstanceOf(Paragraph::class, $paragraphStyle);
        self::assertNull($paragraphStyle->isBidi());
        self::assertSame('', $paragraphStyle->getTextDirection());
        $textElements = $element->getElements();
        self::assertCount(1, $textElements);
        $textElement = $textElements[0];
        self::assertInstanceOf(Text::class, $textElement);
        self::assertInstanceOf(Font::class, $textElement->getFontStyle());
        self::assertNull($textElement->getFontStyle()->isRtl());
    }

    public function testParseHtmlDir(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $html = '<p dir="rtl">test1.</p>';
        $html .= '<p dir="ltr">test2.</p>';
        $html .= '<p>test3.</p>';
        Html::addHtml($section, $html);
        $elements = $section->getElements();
        self::assertCount(3, $elements);

        $index = 0;
        $element = $elements[$index];
        self::assertInstanceOf(TextRun::class, $element);
        $paragraphStyle = $element->getParagraphStyle();
        self::assertInstanceOf(Paragraph::class, $paragraphStyle);
        self::assertTrue($paragraphStyle->isBidi());
        self::assertSame('tbRl', $paragraphStyle->getTextDirection());
        $textElements = $element->getElements();
        self::assertCount(1, $textElements);
        $textElement = $textElements[0];
        self::assertInstanceOf(Text::class, $textElement);
        self::assertInstanceOf(Font::class, $textElement->getFontStyle());
        self::assertTrue($textElement->getFontStyle()->isRtl());

        $index = 1;
        $element = $elements[$index];
        self::assertInstanceOf(TextRun::class, $element);
        $paragraphStyle = $element->getParagraphStyle();
        self::assertInstanceOf(Paragraph::class, $paragraphStyle);
        self::assertFalse($paragraphStyle->isBidi());
        self::assertSame('lrTb', $paragraphStyle->getTextDirection());
        $textElements = $element->getElements();
        self::assertCount(1, $textElements);
        $textElement = $textElements[0];
        self::assertInstanceOf(Text::class, $textElement);
        self::assertInstanceOf(Font::class, $textElement->getFontStyle());
        self::assertFalse($textElement->getFontStyle()->isRtl());

        $index = 2;
        $element = $elements[$index];
        self::assertInstanceOf(TextRun::class, $element);
        $paragraphStyle = $element->getParagraphStyle();
        self::assertInstanceOf(Paragraph::class, $paragraphStyle);
        self::assertNull($paragraphStyle->isBidi());
        self::assertSame('', $paragraphStyle->getTextDirection());
        $textElements = $element->getElements();
        self::assertCount(1, $textElements);
        $textElement = $textElements[0];
        self::assertInstanceOf(Text::class, $textElement);
        self::assertInstanceOf(Font::class, $textElement->getFontStyle());
        self::assertNull($textElement->getFontStyle()->isRtl());
    }

    public function testCssClassNameOnPElement(): void
    {
        $phpWord = new PhpWord();
        $phpWord->addFontStyle('customClass', ['bold' => true], ['borderBottomSize' => 3, 'borderBottomColor' => '#00ff00', 'textDirection' => 'tbRl']);
        $section = $phpWord->addSection();
        $html = '<p class="customClass">test1.</p>';
        Html::addHtml($section, $html);
        $doc = TestHelperDOCX::getDocument($phpWord);
        $path = '/w:document/w:body/w:p';
        $paragraphPath = $path . '/w:pPr';
        $element = $doc->getElement($paragraphPath . '/w:pStyle');
        self::assertSame('customClass', $element->getAttribute('w:val'));
        $textPath = $path . '/w:r/w:t';
        self::assertSame('test1.', $doc->getElement($textPath)->nodeValue);
        self::assertSame('customClass', $doc->getElement($path . '/w:r/w:rPr/w:rStyle')->getAttribute('w:val'));

        // Styles
        $file = 'word/styles.xml';
        $path = '/w:styles/w:style[@w:styleId="customClass"]';
        $paragraphPath = $path . '/w:pPr';
        $element = $doc->getElement($paragraphPath . '/w:pBdr/w:bottom', $file);
        self::assertSame('#00ff00', $element->getAttribute('w:color'));
        $element = $doc->getElement($paragraphPath . '/w:textDirection', $file);
        self::assertSame('tbRl', $element->getAttribute('w:val'));
        $fontPath = $path . '/w:rPr';
        $element = $doc->getElement($fontPath . '/w:b', $file);
        self::assertSame('1', $element->getAttribute('w:val'));
    }
}
