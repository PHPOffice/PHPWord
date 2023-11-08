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
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Style\Language;

/**
 * Test class for PhpOffice\PhpWord\Writer\HTML\Style\Font.
 */
class FontTest extends \PHPUnit\Framework\TestCase
{
    /** @var string */
    private $defaultFontName;

    /** @var float|int */
    private $defaultFontSize;

    /**
     * Executed before each method of the class.
     */
    protected function setUp(): void
    {
        $this->defaultFontName = Settings::getDefaultFontName();
        $this->defaultFontSize = Settings::getDefaultFontSize();
    }

    /**
     * Executed after each method of the class.
     */
    protected function tearDown(): void
    {
        Settings::setDefaultFontName($this->defaultFontName);
        Settings::setDefaultFontSize($this->defaultFontSize);
    }

    /**
     * Tests font names - without generics.
     */
    public function testFontNames1(): void
    {
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Courier New');
        $phpWord->setDefaultFontSize(12);
        $phpWord->addFontStyle('style1', ['name' => 'Tahoma', 'size' => 10, 'color' => '1B2232', 'bold' => true]);
        $phpWord->addFontStyle('style2', ['name' => 'Arial', 'size' => 10]);
        $phpWord->addFontStyle('style3', ['name' => 'hack attempt\'}; display:none', 'size' => 10]);
        $phpWord->addFontStyle('style4', ['name' => 'padmaa 1.1', 'size' => 10, 'bold' => true]);
        $phpWord->addFontStyle('style5', ['name' => 'MingLiU-ExtB', 'size' => 10, 'bold' => true]);
        $section1 = $phpWord->addSection();
        $section1->addText('Default font');
        $section1->addText('Tahoma', 'style1');
        $section1->addText('Arial', 'style2');
        $section1->addText('hack attempt', 'style3');
        $section1->addText('padmaa 1.1 bold', 'style4');
        $section1->addText('MingLiu-ExtB bold', 'style5');

        $dom = Helper::getAsHTML($phpWord);
        $xpath = new DOMXPath($dom);

        self::assertEmpty(Helper::getNamedItem($xpath, '/html/body/div/p[1]', 'class'));
        self::assertEquals(0, Helper::getLength($xpath, '/html/body/div/p[1]/span'));
        self::assertEquals('style1', Helper::getTextContent($xpath, '/html/body/div/p[2]/span', 'class'));
        self::assertEquals('style2', Helper::getTextContent($xpath, '/html/body/div/p[3]/span', 'class'));
        self::assertEquals('style3', Helper::getTextContent($xpath, '/html/body/div/p[4]/span', 'class'));
        self::assertEquals('style4', Helper::getTextContent($xpath, '/html/body/div/p[5]/span', 'class'));
        self::assertEquals('style5', Helper::getTextContent($xpath, '/html/body/div/p[6]/span', 'class'));

        $style = Helper::getTextContent($xpath, '/html/head/style');
        $prg = preg_match('/^[*][^\\r\\n]*/m', $style, $matches);
        self::assertNotFalse($prg);
        self::assertEquals('* {font-family: \'Courier New\'; font-size: 12pt;}', $matches[0]);
        $prg = preg_match('/^[.]style1[^\\r\\n]*/m', $style, $matches);
        self::assertNotFalse($prg);
        self::assertEquals('.style1 {font-family: \'Tahoma\'; font-size: 10pt; color: #1B2232; font-weight: bold;}', $matches[0]);
        $prg = preg_match('/^[.]style2[^\\r\\n]*/m', $style, $matches);
        self::assertNotFalse($prg);
        self::assertEquals('.style2 {font-family: \'Arial\'; font-size: 10pt;}', $matches[0]);
        $prg = preg_match('/^[.]style3[^\\r\\n]*/m', $style, $matches);
        self::assertNotFalse($prg);
        self::assertEquals('.style3 {font-family: \'hack attempt&#039;}; display:none\'; font-size: 10pt;}', $matches[0]);
        $prg = preg_match('/^[.]style4[^\\r\\n]*/m', $style, $matches);
        self::assertNotFalse($prg);
        self::assertEquals('.style4 {font-family: \'padmaa 1.1\'; font-size: 10pt; font-weight: bold;}', $matches[0]);
        $prg = preg_match('/^[.]style5[^\\r\\n]*/m', $style, $matches);
        self::assertNotFalse($prg);
        self::assertEquals('.style5 {font-family: \'MingLiU-ExtB\'; font-size: 10pt; font-weight: bold;}', $matches[0]);
    }

    /**
     * Tests font names - with generics.
     */
    public function testFontNames2(): void
    {
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Courier New');
        $phpWord->setDefaultFontSize(12);
        $phpWord->addFontStyle('style1', ['name' => 'Tahoma', 'size' => 10, 'color' => '1B2232', 'bold' => true]);
        $phpWord->addFontStyle('style2', ['name' => 'Arial', 'size' => 10, 'fallbackFont' => 'sans-serif']);
        $phpWord->addFontStyle('style3', ['name' => 'DejaVu Sans Monospace', 'size' => 10, 'fallbackFont' => 'monospace']);
        $phpWord->addFontStyle('style4', ['name' => 'Arial', 'size' => 10, 'fallbackFont' => 'invalid']);
        $section1 = $phpWord->addSection();
        $section1->addText('Default font');
        $section1->addText('Tahoma', 'style1');
        $section1->addText('Arial', 'style2');
        $section1->addText('DejaVu Sans Monospace', 'style3');
        $section1->addText('Arial with invalid fallback', 'style4');

        $dom = Helper::getAsHTML($phpWord);
        $xpath = new DOMXPath($dom);

        self::assertEmpty(Helper::getNamedItem($xpath, '/html/body/div/p[1]', 'class'));
        self::assertEquals(0, Helper::getLength($xpath, '/html/body/div/p[1]/span'));
        self::assertEquals('style1', Helper::getTextContent($xpath, '/html/body/div/p[2]/span', 'class'));
        self::assertEquals('style2', Helper::getTextContent($xpath, '/html/body/div/p[3]/span', 'class'));
        self::assertEquals('style3', Helper::getTextContent($xpath, '/html/body/div/p[4]/span', 'class'));
        self::assertEquals('style4', Helper::getTextContent($xpath, '/html/body/div/p[5]/span', 'class'));

        $style = Helper::getTextContent($xpath, '/html/head/style');
        $prg = preg_match('/^[*][^\\r\\n]*/m', $style, $matches);
        self::assertNotFalse($prg);
        self::assertEquals('* {font-family: \'Courier New\'; font-size: 12pt;}', $matches[0]);
        $prg = preg_match('/^[.]style1[^\\r\\n]*/m', $style, $matches);
        self::assertNotFalse($prg);
        self::assertEquals('.style1 {font-family: \'Tahoma\'; font-size: 10pt; color: #1B2232; font-weight: bold;}', $matches[0]);
        $prg = preg_match('/^[.]style2[^\\r\\n]*/m', $style, $matches);
        self::assertNotFalse($prg);
        self::assertEquals('.style2 {font-family: \'Arial\', sans-serif; font-size: 10pt;}', $matches[0]);
        $prg = preg_match('/^[.]style3[^\\r\\n]*/m', $style, $matches);
        self::assertNotFalse($prg);
        self::assertEquals('.style3 {font-family: \'DejaVu Sans Monospace\', monospace; font-size: 10pt;}', $matches[0]);
        $prg = preg_match('/^[.]style4[^\\r\\n]*/m', $style, $matches);
        self::assertNotFalse($prg);
        self::assertEquals('.style4 {font-family: \'Arial\'; font-size: 10pt;}', $matches[0]);
    }

    /**
     * Tests font names - with generics including for default font.
     */
    public function testFontNames3(): void
    {
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Courier New');
        $phpWord->setDefaultFontSize(12);
        $phpWord->addFontStyle('style1', ['name' => 'Tahoma', 'size' => 10, 'color' => '1B2232', 'bold' => true]);
        $phpWord->addFontStyle('style2', ['name' => 'Arial', 'size' => 10, 'fallbackFont' => 'sans-serif']);
        $phpWord->addFontStyle('style3', ['name' => 'DejaVu Sans Monospace', 'size' => 10, 'fallbackFont' => 'monospace']);
        $phpWord->addFontStyle('style4', ['name' => 'Arial', 'size' => 10, 'fallbackFont' => 'invalid']);
        $section1 = $phpWord->addSection();
        $section1->addText('Default font');
        $section1->addText('Tahoma', 'style1');
        $section1->addText('Arial', 'style2');
        $section1->addText('DejaVu Sans Monospace', 'style3');
        $section1->addText('Arial with invalid fallback', 'style4');

        $dom = Helper::getAsHTML($phpWord, '', 'monospace');
        $xpath = new DOMXPath($dom);

        self::assertEmpty(Helper::getNamedItem($xpath, '/html/body/div/p[1]', 'class'));
        self::assertEquals(0, Helper::getLength($xpath, '/html/body/div/p[1]/span'));
        self::assertEquals('style1', Helper::getTextContent($xpath, '/html/body/div/p[2]/span', 'class'));
        self::assertEquals('style2', Helper::getTextContent($xpath, '/html/body/div/p[3]/span', 'class'));
        self::assertEquals('style3', Helper::getTextContent($xpath, '/html/body/div/p[4]/span', 'class'));
        self::assertEquals('style4', Helper::getTextContent($xpath, '/html/body/div/p[5]/span', 'class'));

        $style = Helper::getTextContent($xpath, '/html/head/style');
        $prg = preg_match('/^[*][^\\r\\n]*/m', $style, $matches);
        self::assertNotFalse($prg);
        self::assertEquals('* {font-family: \'Courier New\', monospace; font-size: 12pt;}', $matches[0]);
        $prg = preg_match('/^[.]style1[^\\r\\n]*/m', $style, $matches);
        self::assertNotFalse($prg);
        self::assertEquals('.style1 {font-family: \'Tahoma\'; font-size: 10pt; color: #1B2232; font-weight: bold;}', $matches[0]);
        $prg = preg_match('/^[.]style2[^\\r\\n]*/m', $style, $matches);
        self::assertNotFalse($prg);
        self::assertEquals('.style2 {font-family: \'Arial\', sans-serif; font-size: 10pt;}', $matches[0]);
        $prg = preg_match('/^[.]style3[^\\r\\n]*/m', $style, $matches);
        self::assertNotFalse($prg);
        self::assertEquals('.style3 {font-family: \'DejaVu Sans Monospace\', monospace; font-size: 10pt;}', $matches[0]);
        $prg = preg_match('/^[.]style4[^\\r\\n]*/m', $style, $matches);
        self::assertNotFalse($prg);
        self::assertEquals('.style4 {font-family: \'Arial\'; font-size: 10pt;}', $matches[0]);
    }

    /**
     * Tests white space.
     */
    public function testWhiteSpace(): void
    {
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontSize(12);
        $phpWord->addFontStyle('style1', ['name' => 'Courier New', 'size' => 10, 'whiteSpace' => 'pre-wrap']);
        $phpWord->addFontStyle('style2', ['name' => 'Courier New', 'size' => 10, 'whiteSpace' => 'invalid']);
        $phpWord->addFontStyle('style3', ['name' => 'Courier New', 'size' => 10, 'whiteSpace' => 'normal']);
        $phpWord->addFontStyle('style4', ['name' => 'Courier New', 'size' => 10, 'whiteSpace' => 'invalid']);
        $text = 'This                  is                 a               long                      line                                              which                     will                      be              split over 2 lines with pre-wrap';
        $section1 = $phpWord->addSection();
        $section1->addText($text);
        $section1->addText($text, 'style1');
        $section1->addText($text, 'style2');
        $section1->addText($text, 'style3');
        $section1->addText($text, 'style4');

        $dom = Helper::getAsHTML($phpWord, 'pre-wrap');
        $xpath = new DOMXPath($dom);

        $style = Helper::getTextContent($xpath, '/html/head/style');
        self::assertNotFalse(preg_match('/^[*][^\\r\\n]*/m', $style, $matches));
        self::assertEquals('* {font-family: \'Arial\'; font-size: 12pt; white-space: pre-wrap;}', $matches[0]);
        $prg = preg_match('/^[.]style1[^\\r\\n]*/m', $style, $matches);
        self::assertNotFalse($prg);
        self::assertEquals('.style1 {font-family: \'Courier New\'; font-size: 10pt; white-space: pre-wrap;}', $matches[0]);
        $prg = preg_match('/^[.]style2[^\\r\\n]*/m', $style, $matches);
        self::assertNotFalse($prg);
        self::assertEquals('.style2 {font-family: \'Courier New\'; font-size: 10pt;}', $matches[0]);
        $prg = preg_match('/^[.]style3[^\\r\\n]*/m', $style, $matches);
        self::assertNotFalse($prg);
        self::assertEquals('.style3 {font-family: \'Courier New\'; font-size: 10pt; white-space: normal;}', $matches[0]);
        $prg = preg_match('/^[.]style4[^\\r\\n]*/m', $style, $matches);
        self::assertNotFalse($prg);
        self::assertEquals('.style4 {font-family: \'Courier New\'; font-size: 10pt;}', $matches[0]);
    }

    /**
     * Tests inline font style.
     */
    public function testInline(): void
    {
        $phpWord = new PhpWord();
        $style1 = ['name' => 'Courier New', 'size' => 10, 'whiteSpace' => 'pre-wrap'];
        $style2 = ['name' => 'Verdana', 'size' => 8.5];
        $text = 'This is a paragraph.';
        $section1 = $phpWord->addSection();
        $section1->addText($text, $style1);
        $section1->addText($text, $style2);
        $section1->addText($text);

        $dom = Helper::getAsHTML($phpWord);
        $xpath = new DOMXPath($dom);

        self::assertEquals('font-family: \'Courier New\'; font-size: 10pt; white-space: pre-wrap;', Helper::getTextContent($xpath, '/html/body/div/p[1]/span', 'style'));
        self::assertEquals('font-family: \'Verdana\'; font-size: 8.5pt;', Helper::getTextContent($xpath, '/html/body/div/p[2]/span', 'style'));
        self::assertEmpty(Helper::getNamedItem($xpath, '/html/body/div/p[3]', 'class'));
        self::assertEmpty(Helper::getNamedItem($xpath, '/html/body/div/p[3]', 'style'));
        self::assertEquals(0, Helper::getLength($xpath, '/html/body/div/p[3]/span'));
    }

    /**
     * Tests languages.
     */
    public function testLanguages(): void
    {
        $phpWord = new PhpWord();
        $langarabic = new Language('', '', 'ar-DZ');
        $phpWord->addFontStyle('arabic', ['lang' => $langarabic]);
        $langhindi = new Language('', 'hi-IN');
        $phpWord->addFontStyle('hindi', ['lang' => $langhindi, 'name' => 'Arial']);
        $phpWord->addFontStyle('nolang', ['name' => 'Verdana', 'size' => '10']);
        $section = $phpWord->addSection();
        $textrun = $section->addTextRun();
        $textrun->addText('سلام این یک پاراگراف راست به چپ است', ['rtl' => true, 'lang' => $langarabic]);
        $section->addText('Ce texte-ci est en français.', ['lang' => 'fr-BE']);
        $section->addText('Ce texte-ci aussi.', ['lang' => 'fr-BE', 'name' => 'Verdana']);
        $section->addText('Text with no language');
        $section->addText('पाठ हिंदी में', 'hindi');
        $section->addText('Non-existent style', 'nonexistent');
        $section->addText('Style without language', 'nolang');

        $dom = Helper::getAsHTML($phpWord);
        $xpath = new DOMXPath($dom);
        self::assertEquals('ar-DZ', Helper::getTextContent($xpath, '/html/body/div/p[1]/span', 'lang'));
        self::assertEquals('fr-BE', Helper::getTextContent($xpath, '/html/body/div/p[2]/span', 'lang'));
        self::assertEquals('fr-BE', Helper::getTextContent($xpath, '/html/body/div/p[3]/span', 'lang'));
        self::assertEquals('font-family: \'Verdana\';', Helper::getTextContent($xpath, '/html/body/div/p[3]/span', 'style'));
        self::assertEquals(0, Helper::getLength($xpath, '/html/body/div/p[4]/span'));
        self::assertEquals('hi-IN', Helper::getTextContent($xpath, '/html/body/div/p[5]/span', 'lang'));
        self::assertEquals('hindi', Helper::getTextContent($xpath, '/html/body/div/p[5]/span', 'class'));
        self::assertEquals('nonexistent', Helper::getTextContent($xpath, '/html/body/div/p[6]/span', 'class'));
        self::assertEmpty(Helper::getNamedItem($xpath, '/html/body/div/p[6]/span', 'lang'));
        self::assertEquals('nolang', Helper::getTextContent($xpath, '/html/body/div/p[7]/span', 'class'));
        self::assertEmpty(Helper::getNamedItem($xpath, '/html/body/div/p[7]/span', 'lang'));
    }
}
