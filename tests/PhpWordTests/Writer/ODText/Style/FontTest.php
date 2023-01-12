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

namespace PhpOffice\PhpWordTests\Writer\ODText\Style;

use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for Headers, Footers, Tabs in ODT.
 */
class FontTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed after each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test colors.
     */
    public function testColors(): void
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $section->addText('This is red (800) in rtf/html, default in docx/odt', ['color' => '800']);
        $section->addText('This should be cyanish (008787)', ['color' => '008787']);
        $section->addText('This should be dark green (FGCOLOR_DARKGREEN)', ['color' => \PhpOffice\PhpWord\Style\Font::FGCOLOR_DARKGREEN]);
        $section->addText('This color is default (unknow)', ['color' => 'unknow']);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:automatic-styles';
        self::assertTrue($doc->elementExists($s2a));
        $s2t = '/office:document-content/office:body/office:text/text:section';
        self::assertTrue($doc->elementExists($s2t));

        $element = "$s2a/style:style[5]";
        self::assertTrue($doc->elementExists($element));
        $style = $doc->getElementAttribute($element, 'style:name');
        $element .= '/style:text-properties';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('#008787', $doc->getElementAttribute($element, 'fo:color'));
        $span = "$s2t/text:p[3]/text:span";
        self::assertTrue($doc->elementExists($span));
        self::assertEquals($style, $doc->getElementAttribute($span, 'text:style-name'));
        self::assertEquals('This should be cyanish (008787)', $doc->getElement($span)->nodeValue);

        $element = "$s2a/style:style[7]";
        self::assertTrue($doc->elementExists($element));
        $style = $doc->getElementAttribute($element, 'style:name');
        $element .= '/style:text-properties';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('#006400', $doc->getElementAttribute($element, 'fo:color'));
        $span = "$s2t/text:p[4]/text:span";
        self::assertTrue($doc->elementExists($span));
        self::assertEquals($style, $doc->getElementAttribute($span, 'text:style-name'));
        self::assertEquals('This should be dark green (FGCOLOR_DARKGREEN)', $doc->getElement($span)->nodeValue);
    }

    public function providerAllNamedColors()
    {
        return [
            [Font::FGCOLOR_YELLOW, 'FFFF00'],
            [Font::FGCOLOR_LIGHTGREEN, '90EE90'],
            [Font::FGCOLOR_CYAN, '00FFFF'],
            [Font::FGCOLOR_MAGENTA, 'FF00FF'],
            [Font::FGCOLOR_BLUE, '0000FF'],
            [Font::FGCOLOR_RED, 'FF0000'],
            [Font::FGCOLOR_DARKBLUE, '00008B'],
            [Font::FGCOLOR_DARKCYAN, '008B8B'],
            [Font::FGCOLOR_DARKGREEN, '006400'],
            [Font::FGCOLOR_DARKMAGENTA, '8B008B'],
            [Font::FGCOLOR_DARKRED, '8B0000'],
            [Font::FGCOLOR_DARKYELLOW, '8B8B00'],
            [Font::FGCOLOR_DARKGRAY, 'A9A9A9'],
            [Font::FGCOLOR_LIGHTGRAY, 'D3D3D3'],
            [Font::FGCOLOR_BLACK, '000000'],
            ['unknow', 'unknow'],
            ['unknown', 'unknown'],
        ];
    }

    /**
     * @dataProvider providerAllNamedColors
     *
     * @param string $namedColor
     * @param string $rgbColor
     */
    public function testAllNamedColors($namedColor, $rgbColor): void
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $section->addText('This is red (800) in rtf/html, default in docx/odt', ['color' => '800']);
        $section->addText('This should be cyanish (008787)', ['color' => '008787']);
        $section->addText($namedColor, ['color' => $namedColor]);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:automatic-styles';
        self::assertTrue($doc->elementExists($s2a));
        $s2t = '/office:document-content/office:body/office:text/text:section';
        self::assertTrue($doc->elementExists($s2t));

        $element = "$s2a/style:style[7]";
        self::assertTrue($doc->elementExists($element));
        $style = $doc->getElementAttribute($element, 'style:name');
        $element .= '/style:text-properties';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals("#$rgbColor", $doc->getElementAttribute($element, 'fo:color'));
        $span = "$s2t/text:p[4]/text:span";
        self::assertTrue($doc->elementExists($span));
        self::assertEquals($style, $doc->getElementAttribute($span, 'text:style-name'));
        self::assertEquals($namedColor, $doc->getElement($span)->nodeValue);
    }

    /**
     * Test noproof.
     */
    public function testNoProof(): void
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Noproof not specified', ['color' => 'black']);
        $section->addText('Noproof is true', ['color' => 'black', 'noproof' => true]);
        $section->addText('Noproof is false', ['color' => 'black', 'noproof' => false]);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:automatic-styles';
        self::assertTrue($doc->elementExists($s2a));
        $s2t = '/office:document-content/office:body/office:text/text:section';
        self::assertTrue($doc->elementExists($s2t));

        $element = "$s2a/style:style[3]";
        self::assertTrue($doc->elementExists($element));
        $style = $doc->getElementAttribute($element, 'style:name');
        $element .= '/style:text-properties';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('', $doc->getElementAttribute($element, 'fo:language'));
        $span = "$s2t/text:p[2]/text:span";
        self::assertTrue($doc->elementExists($span));
        self::assertEquals($style, $doc->getElementAttribute($span, 'text:style-name'));
        self::assertEquals('Noproof not specified', $doc->getElement($span)->nodeValue);

        $element = "$s2a/style:style[5]";
        self::assertTrue($doc->elementExists($element));
        $style = $doc->getElementAttribute($element, 'style:name');
        $element .= '/style:text-properties';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('zxx', $doc->getElementAttribute($element, 'fo:language'));
        self::assertEquals('zxx', $doc->getElementAttribute($element, 'style:language-asian'));
        self::assertEquals('zxx', $doc->getElementAttribute($element, 'style:language-complex'));
        self::assertEquals('none', $doc->getElementAttribute($element, 'fo:country'));
        self::assertEquals('none', $doc->getElementAttribute($element, 'style:country-asian'));
        self::assertEquals('none', $doc->getElementAttribute($element, 'style:country-complex'));
        $span = "$s2t/text:p[3]/text:span";
        self::assertTrue($doc->elementExists($span));
        self::assertEquals($style, $doc->getElementAttribute($span, 'text:style-name'));
        self::assertEquals('Noproof is true', $doc->getElement($span)->nodeValue);

        $element = "$s2a/style:style[7]";
        self::assertTrue($doc->elementExists($element));
        $style = $doc->getElementAttribute($element, 'style:name');
        $element .= '/style:text-properties';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('', $doc->getElementAttribute($element, 'fo:language'));
        $span = "$s2t/text:p[4]/text:span";
        self::assertTrue($doc->elementExists($span));
        self::assertEquals($style, $doc->getElementAttribute($span, 'text:style-name'));
        self::assertEquals('Noproof is false', $doc->getElement($span)->nodeValue);
    }

    /**
     * Test using object with a name as font style for addText.
     */
    public function testNamedStyleAsObject(): void
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $named = $phpWord->addFontStyle('namedobject', ['color' => '008787']);
        $section = $phpWord->addSection();
        $section->addText('Let us see what color we wind up with', $named);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2t = '/office:document-content/office:body/office:text/text:section';
        self::assertTrue($doc->elementExists($s2t));
        $element = "$s2t/text:p[2]/text:span";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('namedobject', $doc->getElementAttribute($element, 'text:style-name'));
    }

    /**
     * Test supplying field font style as array or object or string.
     */
    public function testFieldStyles(): void
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $namedstyle = $phpWord->addFontStyle('namedstyle', ['color' => '800000']);
        $section = $phpWord->addSection();
        $textrun = $section->addTextRun();
        $fld = $textrun->addField('DATE');
        $fld->setFontStyle('namedstyle');
        $textrun = $section->addTextRun();
        $fld = $textrun->addField('DATE');
        $fld->setFontStyle(['color' => '008000']);
        $textrun = $section->addTextRun();
        $fld = $textrun->addField('DATE');
        $font = new \PhpOffice\PhpWord\Style\Font();
        $font->setColor('000080');
        $fld->setFontStyle($font);
        $textrun = $section->addTextRun();
        $fld = $textrun->addField('DATE');
        $fld->setFontStyle($namedstyle);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:automatic-styles';
        $s2t = '/office:document-content/office:body/office:text/text:section';

        $element = "$s2a/style:style[5]";
        self::assertEquals('T1', $doc->getElementAttribute($element, 'style:name'));
        self::assertEquals('#008000', $doc->getElementAttribute("$element/style:text-properties", 'fo:color'));
        $element = "$s2a/style:style[7]";
        self::assertEquals('T2', $doc->getElementAttribute($element, 'style:name'));
        self::assertEquals('#000080', $doc->getElementAttribute("$element/style:text-properties", 'fo:color'));

        $element = "$s2t/text:p[2]/text:span";
        self::assertEquals('namedstyle', $doc->getElementAttribute($element, 'text:style-name'));
        self::assertTrue($doc->elementExists("$element/text:date"));
        $element = "$s2t/text:p[3]/text:span";
        self::assertEquals('T1', $doc->getElementAttribute($element, 'text:style-name'));
        self::assertTrue($doc->elementExists("$element/text:date"));
        $element = "$s2t/text:p[4]/text:span";
        self::assertEquals('T2', $doc->getElementAttribute($element, 'text:style-name'));
        self::assertTrue($doc->elementExists("$element/text:date"));
        $element = "$s2t/text:p[5]/text:span";
        self::assertEquals('namedstyle', $doc->getElementAttribute($element, 'text:style-name'));
    }
}
