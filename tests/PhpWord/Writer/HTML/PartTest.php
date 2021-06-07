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
use PhpOffice\PhpWord\Writer\HTML\Part\Body;

/**
 * Test class for PhpOffice\PhpWord\Writer\HTML\Part subnamespace
 */
class PartTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test get parent writer exception
     *
     * @expectedException \PhpOffice\PhpWord\Exception\Exception
     */
    public function testGetParentWriterException()
    {
        $object = new Body();
        $object->getParentWriter();
    }

    private function getAsHTML(PhpWord $phpWord)
    {
        $htmlWriter = new HTML($phpWord);
        $dom = new \DOMDocument();
        $dom->loadHTML($htmlWriter->getContent());

        return $dom;
    }

    /**
     * Tests writing multiple sections
     */
    public function testWriteSections()
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
            ->setMarginRight($mrig)
            ->setPortrait();
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
            ->setMarginRight($mrig)
            ->setLandscape();
        $section2->addText('In theory, this will be printed landscape on A4 paper');

        $dom = $this->getAsHTML($phpWord);
        $xpath = new \DOMXPath($dom);

        self::assertEquals('en-US', $xpath->query('/html')->item(0)->attributes->getNamedItem('lang')->textContent);
        self::assertEquals(2, $xpath->query('/html/body/div')->length);
        self::assertEquals('page: page1', $xpath->query('/html/body/div[1]')->item(0)->attributes->getNamedItem('style')->textContent);
        self::assertEquals('page: page2', $xpath->query('/html/body/div[2]')->item(0)->attributes->getNamedItem('style')->textContent);

        $style = $xpath->query('/html/head/style')->item(0)->textContent;
        self::assertNotFalse(strpos($style, 'body > div + div {page-break-before: always;}'));
        self::assertNotFalse(strpos($style, 'div > *:first-child {page-break-before: auto;}'));
        self::assertNotFalse(strpos($style, '@page page1 {size: Letter portrait; margin-right: 0.75in; margin-left: 0.75in; margin-top: 0.5in; margin-bottom: 0.5in; }'));
        self::assertNotFalse(strpos($style, '@page page2 {size: A4 landscape; margin-right: 0.65in; margin-left: 0.65in; margin-top: 0.6in; margin-bottom: 0.6in; }'));
    }

    /**
     * Tests theme font East Asian
     */
    public function testThemeFontEastAsian()
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new \PhpOffice\PhpWord\Style\Language('', 'hi-IN'));
        $section1 = $phpWord->addSection();
        $section1->addText('पाठ हिंदी में');

        $dom = $this->getAsHTML($phpWord);
        $xpath = new \DOMXPath($dom);

        self::assertEquals('hi-IN', $xpath->query('/html')->item(0)->attributes->getNamedItem('lang')->textContent);
    }

    /**
     * Tests theme font bidirectional
     */
    public function testThemeBidirecional()
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setThemeFontLang(new \PhpOffice\PhpWord\Style\Language('', '', 'he-IL'));
        $section1 = $phpWord->addSection();
        $section1->addText('שלום');

        $dom = $this->getAsHTML($phpWord);
        $xpath = new \DOMXPath($dom);

        self::assertEquals('he-IL', $xpath->query('/html')->item(0)->attributes->getNamedItem('lang')->textContent);
    }

    /**
     * Tests writing when default paragraph style is specified
     */
    public function testDefaultParagraphStyle()
    {
        $phpWord = new PhpWord();
        $nospacebeforeafter = array('spaceBefore' => 0, 'spaceAfter' => 0);
        $phpWord->setDefaultParagraphStyle($nospacebeforeafter);
        $section1 = $phpWord->addSection();
        $section1->addText('First paragraph with no space before or after');
        $section1->addText('Second paragraph with no space before or after');

        $dom = $this->getAsHTML($phpWord);
        $xpath = new \DOMXPath($dom);

        self::assertNull($xpath->query('/html')->item(0)->attributes->getNamedItem('lang'));
        $style = $xpath->query('/html/head/style')->item(0)->textContent;
        self::assertNotFalse(strpos($style, 'p, .Normal {margin-top: 0pt; margin-bottom: 0pt;}'));
    }

    /**
     * Tests writing when default paragraph style is omitted
     */
    public function testNoDefaultParagraphStyle()
    {
        $phpWord = new PhpWord();
        $section1 = $phpWord->addSection();
        $section1->addText('First paragraph with no space before or after');
        $section1->addText('Second paragraph with no space before or after');

        $dom = $this->getAsHTML($phpWord);
        $xpath = new \DOMXPath($dom);

        $style = $xpath->query('/html/head/style')->item(0)->textContent;
        self::assertFalse(strpos($style, 'Normal'));
    }

    /**
     * Tests title styles
     */
    public function testTitleStyles()
    {
        $phpWord = new PhpWord();
        $phpWord->setDefaultParagraphStyle(array('spaceBefore' => 0, 'spaceAfter' => 0));
        $phpWord->addTitleStyle(1, array('bold' => true, 'name' => 'Calibri'), array('spaceBefore' => 10, 'spaceAfter' => 10));
        $phpWord->addTitleStyle(2, array('italic' => true, 'name' => 'Times New Roman'), array('spaceBefore' => 5, 'spaceAfter' => 5));
        $section1 = $phpWord->addSection();
        $section1->addTitle('Header 1 #1', 1);
        $section1->addTitle('Header 2 #1', 2);
        $section1->addText('Paragraph under header 2 #1');
        $section1->addTitle('Header 2 #2', 2);
        $section1->addText('Paragraph under header 2 #2');

        $dom = $this->getAsHTML($phpWord);
        $xpath = new \DOMXPath($dom);

        $style = $xpath->query('/html/head/style')->item(0)->textContent;
        self::assertNotFalse(strpos($style, 'h1 {font-family: \'Calibri\'; font-weight: bold;}'));
        self::assertNotFalse(strpos($style, 'h1 {margin-top: 0.5pt; margin-bottom: 0.5pt;}'));
        self::assertNotFalse(strpos($style, 'h2 {font-family: \'Times New Roman\'; font-style: italic;}'));
        self::assertNotFalse(strpos($style, 'h2 {margin-top: 0.25pt; margin-bottom: 0.25pt;}'));
        self::assertEquals(1, $xpath->query('/html/body/div/h1')->length);
        self::assertEquals(2, $xpath->query('/html/body/div/h2')->length);
    }
}
