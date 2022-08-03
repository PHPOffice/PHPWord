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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\HTML;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Writer\HTML;

/**
 * Test class for PhpOffice\PhpWord\Writer\HTML\Style\Font
 */
class ParagraphTest extends \PHPUnit\Framework\TestCase
{
    private function getAsHTML(PhpWord $phpWord)
    {
        $htmlWriter = new HTML($phpWord);
        $dom = new \DOMDocument();
        $dom->loadHTML($htmlWriter->getContent());

        return $dom;
    }

    /**
     * Tests indentation, line-height, spaceBefore, spaceAfter, both inline and named
     */
    public function testParagraphStyles()
    {
        $phpWord = new PhpWord();
        $pstyle1 = array('spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.08);
        $phpWord->addParagraphStyle('indented', array(
            'indentation' => array('left' => 0.50 * Converter::INCH_TO_TWIP, 'right' => 0.60 * Converter::INCH_TO_TWIP),
            ));
        $text = 'This is a paragraph. It should be long enough to show the effects of indentation on both the right and left sides.';
        $section1 = $phpWord->addSection();
        $section1->addText($text, null, $pstyle1);
        $section1->addText($text, null, 'indented');

        $dom = $this->getAsHTML($phpWord);
        $xpath = new \DOMXPath($dom);

        self::assertEquals(0, $xpath->query('/html/body/div/p[1]/span')->length);
        self::assertEmpty($xpath->query('/html/body/div/p[1]')->item(0)->attributes->getNamedItem('class'));
        self::assertEquals('margin-top: 0pt; margin-bottom: 0pt; line-height: 1.08;', $xpath->query('/html/body/div/p[1]')->item(0)->attributes->getNamedItem('style')->textContent);
        self::assertEquals(0, $xpath->query('/html/body/div/p[2]/span')->length);
        self::assertEmpty($xpath->query('/html/body/div/p[2]')->item(0)->attributes->getNamedItem('style'));
        self::assertEquals('indented', $xpath->query('/html/body/div/p[2]')->item(0)->attributes->getNamedItem('class')->textContent);

        $style = $xpath->query('/html/head/style')->item(0)->textContent;
        self::assertNotFalse(preg_match('/^[.]indented[^\\r\\n]*/m', $style, $matches));
        self::assertEquals('.indented {margin-left: 0.5in; margin-right: 0.6in;}', $matches[0]);
    }

    /**
     * Tests paragraph and font styles specified togeter, both inline and named
     */
    public function testParagraphAndFontStyles()
    {
        $phpWord = new PhpWord();
        $pstyle1 = array('spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.08);
        $phpWord->addParagraphStyle('indented', array(
            'indentation' => array('left' => 0.50 * Converter::INCH_TO_TWIP, 'right' => 0.60 * Converter::INCH_TO_TWIP),
            ));
        $phpWord->addFontStyle('style1', array('name' => 'Courier New', 'size' => 10, 'htmlWhiteSpace' => 'pre-wrap', 'htmlGenericFont' => 'monospace'));
        $text = 'This is a paragraph. It should be long enough to show the effects of indentation on both the right and left sides.';
        $section1 = $phpWord->addSection();
        $section1->addText($text, 'style1', $pstyle1);
        $section1->addText($text, array('name' => 'Verdana', 'size' => '12'), 'indented');

        $dom = $this->getAsHTML($phpWord);
        $xpath = new \DOMXPath($dom);

        self::assertEquals(1, $xpath->query('/html/body/div/p[1]/span')->length);
        self::assertEmpty($xpath->query('/html/body/div/p[1]')->item(0)->attributes->getNamedItem('class'));
        self::assertEquals('margin-top: 0pt; margin-bottom: 0pt; line-height: 1.08;', $xpath->query('/html/body/div/p[1]')->item(0)->attributes->getNamedItem('style')->textContent);
        self::assertEquals('style1', $xpath->query('/html/body/div/p[1]/span')->item(0)->attributes->getNamedItem('class')->textContent);
        self::assertEquals(1, $xpath->query('/html/body/div/p[2]/span')->length);
        self::assertEmpty($xpath->query('/html/body/div/p[2]')->item(0)->attributes->getNamedItem('style'));
        self::assertEquals('indented', $xpath->query('/html/body/div/p[2]')->item(0)->attributes->getNamedItem('class')->textContent);
        self::assertEquals('font-family: \'Verdana\'; font-size: 12pt;', $xpath->query('/html/body/div/p[2]/span')->item(0)->attributes->getNamedItem('style')->textContent);

        $style = $xpath->query('/html/head/style')->item(0)->textContent;
        self::assertNotFalse(preg_match('/^[.]indented[^\\r\\n]*/m', $style, $matches));
        self::assertEquals('.indented {margin-left: 0.5in; margin-right: 0.6in;}', $matches[0]);
        self::assertNotFalse(preg_match('/^[.]style1[^\\r\\n]*/m', $style, $matches));
        self::assertEquals('.style1 {font-family: \'Courier New\', monospace; font-size: 10pt; white-space: pre-wrap;}', $matches[0]);
    }

    /**
     * Tests page break before
     */
    public function testPageBreakBefore()
    {
        $phpWord = new PhpWord();
        $pstyle1 = array('lineHeight' => 1.08);
        $pstyle2 = array('lineHeight' => 1.08, 'pageBreakBefore' => true);

        $section1 = $phpWord->addSection();
        $section1->addText('1st paragraph 1st page', null, $pstyle1);
        $section1->addText('2nd paragraph 1st page', null, $pstyle1);
        $section1->addText('1st paragraph 2nd page', null, $pstyle2);
        $section1->addText('2nd paragraph 2nd page', null, $pstyle1);

        $dom = $this->getAsHTML($phpWord);
        $xpath = new \DOMXPath($dom);
        self::assertEquals('line-height: 1.08;', $xpath->query('/html/body/div/p[1]')->item(0)->attributes->getNamedItem('style')->textContent);
        self::assertEquals('line-height: 1.08;', $xpath->query('/html/body/div/p[2]')->item(0)->attributes->getNamedItem('style')->textContent);
        self::assertEquals('line-height: 1.08; page-break-before: always;', $xpath->query('/html/body/div/p[3]')->item(0)->attributes->getNamedItem('style')->textContent);
        self::assertEquals('line-height: 1.08;', $xpath->query('/html/body/div/p[4]')->item(0)->attributes->getNamedItem('style')->textContent);
    }

    /**
     * Tests blank paragraph
     */
    public function testBlankParagraph()
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
