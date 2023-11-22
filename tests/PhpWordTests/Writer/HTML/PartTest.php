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
use PhpOffice\PhpWord\Writer\HTML\Part\Body;

/**
 * Test class for PhpOffice\PhpWord\Writer\HTML\Part subnamespace.
 */
class PartTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test get parent writer exception.
     */
    public function testGetParentWriterException(): void
    {
        $this->expectException(\PhpOffice\PhpWord\Exception\Exception::class);
        $object = new Body();
        $object->getParentWriter();
    }

    /**
     * Tests writing multiple sections.
     */
    public function testWriteSections(): void
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new \PhpOffice\PhpWord\Style\Language('en-US'));
        $section1 = $phpWord->addSection();
        $mtop = 0.5 * Converter::INCH_TO_TWIP;
        $mbot = 0.5 * Converter::INCH_TO_TWIP;
        $mrig = 0.75 * Converter::INCH_TO_TWIP;
        $mlef = 0.75 * Converter::INCH_TO_TWIP;
        $section1
            ->getStyle()
            ->setPaperSize('Letter')
            ->setMarginTop($mtop)
            ->setMarginBottom($mbot)
            ->setMarginLeft($mlef)
            ->setMarginRight($mrig);
        $section1->getStyle()->setPortrait();
        $section1->addText('In theory, this will be printed portrait on letter paper');

        $section2 = $phpWord->addSection();
        $mtop = 0.6 * Converter::INCH_TO_TWIP;
        $mbot = 0.6 * Converter::INCH_TO_TWIP;
        $mrig = 0.65 * Converter::INCH_TO_TWIP;
        $mlef = 0.65 * Converter::INCH_TO_TWIP;
        $section2
            ->getStyle()
            ->setPaperSize('A4')
            ->setMarginTop($mtop)
            ->setMarginBottom($mbot)
            ->setMarginLeft($mlef)
            ->setMarginRight($mrig);
        $section2->getStyle()->setLandscape();
        $section2->addText('In theory, this will be printed landscape on A4 paper');

        $dom = Helper::getAsHTML($phpWord);
        $xpath = new DOMXPath($dom);

        self::assertEquals('en-US', Helper::getTextContent($xpath, '/html', 'lang'));
        self::assertEquals(2, Helper::getLength($xpath, '/html/body/div'));
        self::assertEquals('page: page1', Helper::getTextContent($xpath, '/html/body/div[1]', 'style'));
        self::assertEquals('page: page2', Helper::getTextContent($xpath, '/html/body/div[2]', 'style'));

        $style = Helper::getTextContent($xpath, '/html/head/style');
        self::assertNotFalse(strpos($style, 'body > div + div {page-break-before: always;}'));
        self::assertNotFalse(strpos($style, 'div > *:first-child {page-break-before: auto;}'));
        self::assertNotFalse(strpos($style, '@page page1 {size: Letter portrait; margin-right: 0.75in; margin-left: 0.75in; margin-top: 0.5in; margin-bottom: 0.5in; }'));
        self::assertNotFalse(strpos($style, '@page page2 {size: A4 landscape; margin-right: 0.65in; margin-left: 0.65in; margin-top: 0.6in; margin-bottom: 0.6in; }'));
    }

    /**
     * Tests theme font East Asian.
     */
    public function testThemeFontEastAsian(): void
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new \PhpOffice\PhpWord\Style\Language('', 'hi-IN'));
        $section1 = $phpWord->addSection();
        $section1->addText('??? ????? ???');

        $dom = Helper::getAsHTML($phpWord);
        $xpath = new DOMXPath($dom);

        self::assertEquals('hi-IN', Helper::getTextContent($xpath, '/html', 'lang'));
    }

    /**
     * Tests theme font bidirectional.
     */
    public function testThemeBidirecional(): void
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new \PhpOffice\PhpWord\Style\Language('', '', 'he-IL'));
        $section1 = $phpWord->addSection();
        $section1->addText('????');

        $dom = Helper::getAsHTML($phpWord);
        $xpath = new DOMXPath($dom);

        self::assertEquals('he-IL', Helper::getTextContent($xpath, '/html', 'lang'));
    }

    /**
     * Tests writing when default paragraph style is specified.
     */
    public function testDefaultParagraphStyle(): void
    {
        $phpWord = new PhpWord();
        $nospacebeforeafter = ['spaceBefore' => 0, 'spaceAfter' => 0];
        $phpWord->setDefaultParagraphStyle($nospacebeforeafter);
        $section1 = $phpWord->addSection();
        $section1->addText('First paragraph with no space before or after');
        $section1->addText('Second paragraph with no space before or after');

        $dom = Helper::getAsHTML($phpWord);
        $xpath = new DOMXPath($dom);

        self::assertEmpty(Helper::getNamedItem($xpath, '/html', 'lang'));
        $style = Helper::getTextContent($xpath, '/html/head/style');
        self::assertNotFalse(strpos($style, 'p, .Normal {margin-top: 0pt; margin-bottom: 0pt;}'));
    }

    /**
     * Tests writing when default paragraph style is omitted.
     */
    public function testNoDefaultParagraphStyle(): void
    {
        $phpWord = new PhpWord();
        $section1 = $phpWord->addSection();
        $section1->addText('First paragraph with no space before or after');
        $section1->addText('Second paragraph with no space before or after');

        $dom = Helper::getAsHTML($phpWord);
        $xpath = new DOMXPath($dom);

        $style = Helper::getTextContent($xpath, '/html/head/style');
        self::assertFalse(strpos($style, 'Normal'));
    }

    /**
     * Tests title styles.
     */
    public function testTitleStyles(): void
    {
        $phpWord = new PhpWord();
        $phpWord->setDefaultParagraphStyle(['spaceBefore' => 0, 'spaceAfter' => 0]);
        $phpWord->addTitleStyle(1, ['bold' => true, 'name' => 'Calibri'], ['spaceBefore' => 10, 'spaceAfter' => 10]);
        $phpWord->addTitleStyle(2, ['italic' => true, 'name' => 'Times New Roman'], ['spaceBefore' => 5, 'spaceAfter' => 5]);
        $section1 = $phpWord->addSection();
        $section1->addTitle('Header 1 #1', 1);
        $section1->addTitle('Header 2 #1', 2);
        $section1->addText('Paragraph under header 2 #1');
        $section1->addTitle('Header 2 #2', 2);
        $section1->addText('Paragraph under header 2 #2');

        $dom = Helper::getAsHTML($phpWord);
        $xpath = new DOMXPath($dom);

        $style = Helper::getTextContent($xpath, '/html/head/style');
        self::assertNotFalse(strpos($style, 'h1 {font-family: \'Calibri\'; font-weight: bold;}'));
        self::assertNotFalse(strpos($style, 'h1 {margin-top: 0.5pt; margin-bottom: 0.5pt;}'));
        self::assertNotFalse(strpos($style, 'h2 {font-family: \'Times New Roman\'; font-style: italic;}'));
        self::assertNotFalse(strpos($style, 'h2 {margin-top: 0.25pt; margin-bottom: 0.25pt;}'));
        self::assertEquals(1, Helper::getLength($xpath, '/html/body/div/h1'));
        self::assertEquals(2, Helper::getLength($xpath, '/html/body/div/h2'));
    }
}
