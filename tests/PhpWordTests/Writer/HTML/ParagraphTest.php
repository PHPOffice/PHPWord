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

namespace PhpOffice\PhpWordTests\Writer\HTML;

use DOMXPath;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Writer\HTML;

/**
 * Test class for PhpOffice\PhpWord\Writer\HTML\Style\Font.
 */
class ParagraphTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests indentation, line-height, spaceBefore, spaceAfter, both inline and named.
     */
    public function testParagraphStyles(): void
    {
        $phpWord = new PhpWord();
        $pstyle1 = ['spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.08];
        $phpWord->addParagraphStyle('indented', [
            'indentation' => ['left' => 0.50 * Converter::INCH_TO_TWIP, 'right' => 0.60 * Converter::INCH_TO_TWIP],
        ]);
        $text = 'This is a paragraph. It should be long enough to show the effects of indentation on both the right and left sides.';
        $section1 = $phpWord->addSection();
        $section1->addText($text, null, $pstyle1);
        $section1->addText($text, null, 'indented');

        $dom = Helper::getAsHTML($phpWord);
        $xpath = new DOMXPath($dom);

        self::assertEquals(0, Helper::getLength($xpath, '/html/body/div/p[1]/span'));
        self::assertEmpty(Helper::getNamedItem($xpath, '/html/body/div/p[1]', 'class'));
        self::assertEquals('margin-top: 0pt; margin-bottom: 0pt; line-height: 1.08;', Helper::getTextContent($xpath, '/html/body/div/p[1]', 'style'));
        self::assertEquals(0, Helper::getLength($xpath, '/html/body/div/p[2]/span'));
        self::assertEmpty(Helper::getNamedItem($xpath, '/html/body/div/p[2]', 'style'));
        self::assertEquals('indented', Helper::getTextContent($xpath, '/html/body/div/p[2]', 'class'));

        $style = Helper::getTextContent($xpath, '/html/head/style');
        self::assertNotFalse(preg_match('/^[.]indented[^\\r\\n]*/m', $style, $matches));
        self::assertEquals('.indented {margin-left: 0.5in; margin-right: 0.6in;}', $matches[0]);
    }

    /**
     * Tests paragraph and font styles specified togeter, both inline and named.
     */
    public function testParagraphAndFontStyles(): void
    {
        $phpWord = new PhpWord();
        $pstyle1 = ['spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.08];
        $phpWord->addParagraphStyle('indented', [
            'indentation' => ['left' => 0.50 * Converter::INCH_TO_TWIP, 'right' => 0.60 * Converter::INCH_TO_TWIP],
        ]);
        $phpWord->addFontStyle('style1', ['name' => 'Courier New', 'size' => 10, 'whiteSpace' => 'pre-wrap', 'fallbackFont' => 'monospace']);
        $text = 'This is a paragraph. It should be long enough to show the effects of indentation on both the right and left sides.';
        $section1 = $phpWord->addSection();
        $section1->addText($text, 'style1', $pstyle1);
        $section1->addText($text, ['name' => 'Verdana', 'size' => '12'], 'indented');

        $dom = Helper::getAsHTML($phpWord);
        $xpath = new DOMXPath($dom);

        self::assertEquals(1, Helper::getLength($xpath, '/html/body/div/p[1]/span'));
        self::assertEmpty(Helper::getNamedItem($xpath, '/html/body/div/p[1]', 'class'));
        self::assertEquals('margin-top: 0pt; margin-bottom: 0pt; line-height: 1.08;', Helper::getTextContent($xpath, '/html/body/div/p[1]', 'style'));
        self::assertEquals('style1', Helper::getTextContent($xpath, '/html/body/div/p[1]/span', 'class'));
        self::assertEquals(1, Helper::getLength($xpath, '/html/body/div/p[2]/span'));
        self::assertEmpty(Helper::getNamedItem($xpath, '/html/body/div/p[2]', 'style'));
        self::assertEquals('indented', Helper::getTextContent($xpath, '/html/body/div/p[2]', 'class'));
        self::assertEquals('font-family: \'Verdana\'; font-size: 12pt;', Helper::getTextContent($xpath, '/html/body/div/p[2]/span', 'style'));

        $style = Helper::getTextContent($xpath, '/html/head/style');
        self::assertNotFalse(preg_match('/^[.]indented[^\\r\\n]*/m', $style, $matches));
        self::assertEquals('.indented {margin-left: 0.5in; margin-right: 0.6in;}', $matches[0]);
        self::assertNotFalse(preg_match('/^[.]style1[^\\r\\n]*/m', $style, $matches));
        self::assertEquals('.style1 {font-family: \'Courier New\', monospace; font-size: 10pt; white-space: pre-wrap;}', $matches[0]);
    }

    /**
     * Tests page break before.
     */
    public function testPageBreakBefore(): void
    {
        $phpWord = new PhpWord();
        $pstyle1 = ['lineHeight' => 1.08];
        $pstyle2 = ['lineHeight' => 1.08, 'pageBreakBefore' => true];

        $section1 = $phpWord->addSection();
        $section1->addText('1st paragraph 1st page', null, $pstyle1);
        $section1->addText('2nd paragraph 1st page', null, $pstyle1);
        $section1->addText('1st paragraph 2nd page', null, $pstyle2);
        $section1->addText('2nd paragraph 2nd page', null, $pstyle1);

        $dom = Helper::getAsHTML($phpWord);
        $xpath = new DOMXPath($dom);
        self::assertEquals('line-height: 1.08;', Helper::getTextContent($xpath, '/html/body/div/p[1]', 'style'));
        self::assertEquals('line-height: 1.08;', Helper::getTextContent($xpath, '/html/body/div/p[2]', 'style'));
        self::assertEquals('line-height: 1.08; page-break-before: always;', Helper::getTextContent($xpath, '/html/body/div/p[3]', 'style'));
        self::assertEquals('line-height: 1.08;', Helper::getTextContent($xpath, '/html/body/div/p[4]', 'style'));
    }

    /**
     * Tests blank paragraph.
     */
    public function testBlankParagraph(): void
    {
        $phpWord = new PhpWord();

        $section1 = $phpWord->addSection();
        $section1->addText('Text before blank text');
        $section1->addText('');
        $section1->addText('Text after blank text');

        $htmlWriter = new HTML($phpWord);
        $body = $htmlWriter->getWriterPart('Body')->write();
        $bodylines = explode(PHP_EOL, $body);
        self::assertEquals('<p>Text before blank text</p>', $bodylines[2]);
        self::assertEquals('<p>&nbsp;</p>', $bodylines[3]);
        self::assertEquals('<p>Text after blank text</p>', $bodylines[4]);
    }
}
