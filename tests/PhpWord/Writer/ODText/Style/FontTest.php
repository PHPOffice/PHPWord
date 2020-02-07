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

namespace PhpOffice\PhpWord\Writer\ODText\Style;

use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\TestHelperDOCX;

/**
 * Test class for Headers, Footers, Tabs in ODT
 */
class FontTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed after each method of the class
     */
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test colors
     */
    public function testColors()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $section->addText('This is red (800) in rtf/html, default in docx/odt', array('color' => '800'));
        $section->addText('This should be cyanish (008787)', array('color' => '008787'));
        $section->addText('This should be dark green (FGCOLOR_DARKGREEN)', array('color' => \PhpOffice\PhpWord\Style\Font::FGCOLOR_DARKGREEN));
        $section->addText('This color is default (unknow)', array('color' => 'unknow'));

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:automatic-styles';
        $this->assertTrue($doc->elementExists($s2a));
        $s2t = '/office:document-content/office:body/office:text/text:section';
        $this->assertTrue($doc->elementExists($s2t));

        $element = "$s2a/style:style[5]";
        $this->assertTrue($doc->elementExists($element));
        $style = $doc->getElementAttribute($element, 'style:name');
        $element .= '/style:text-properties';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('#008787', $doc->getElementAttribute($element, 'fo:color'));
        $span = "$s2t/text:p[3]/text:span";
        $this->assertTrue($doc->elementExists($span));
        $this->assertEquals($style, $doc->getElementAttribute($span, 'text:style-name'));
        $this->assertEquals('This should be cyanish (008787)', $doc->getElement($span)->nodeValue);

        $element = "$s2a/style:style[7]";
        $this->assertTrue($doc->elementExists($element));
        $style = $doc->getElementAttribute($element, 'style:name');
        $element .= '/style:text-properties';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('#006400', $doc->getElementAttribute($element, 'fo:color'));
        $span = "$s2t/text:p[4]/text:span";
        $this->assertTrue($doc->elementExists($span));
        $this->assertEquals($style, $doc->getElementAttribute($span, 'text:style-name'));
        $this->assertEquals('This should be dark green (FGCOLOR_DARKGREEN)', $doc->getElement($span)->nodeValue);
    }

    public function providerAllNamedColors()
    {
        return array(
            array(Font::FGCOLOR_YELLOW, 'FFFF00'),
            array(Font::FGCOLOR_LIGHTGREEN, '90EE90'),
            array(Font::FGCOLOR_CYAN, '00FFFF'),
            array(Font::FGCOLOR_MAGENTA, 'FF00FF'),
            array(Font::FGCOLOR_BLUE, '0000FF'),
            array(Font::FGCOLOR_RED, 'FF0000'),
            array(Font::FGCOLOR_DARKBLUE, '00008B'),
            array(Font::FGCOLOR_DARKCYAN, '008B8B'),
            array(Font::FGCOLOR_DARKGREEN, '006400'),
            array(Font::FGCOLOR_DARKMAGENTA, '8B008B'),
            array(Font::FGCOLOR_DARKRED, '8B0000'),
            array(Font::FGCOLOR_DARKYELLOW, '8B8B00'),
            array(Font::FGCOLOR_DARKGRAY, 'A9A9A9'),
            array(Font::FGCOLOR_LIGHTGRAY, 'D3D3D3'),
            array(Font::FGCOLOR_BLACK, '000000'),
            array('unknow', 'unknow'),
            array('unknown', 'unknown'),
        );
    }

    /**
     * @dataProvider providerAllNamedColors
     *
     * @param string $namedColor
     * @param string $rgbColor
     */
    public function testAllNamedColors($namedColor, $rgbColor)
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $section->addText('This is red (800) in rtf/html, default in docx/odt', array('color' => '800'));
        $section->addText('This should be cyanish (008787)', array('color' => '008787'));
        $section->addText($namedColor, array('color' => $namedColor));

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:automatic-styles';
        $this->assertTrue($doc->elementExists($s2a));
        $s2t = '/office:document-content/office:body/office:text/text:section';
        $this->assertTrue($doc->elementExists($s2t));

        $element = "$s2a/style:style[7]";
        $this->assertTrue($doc->elementExists($element));
        $style = $doc->getElementAttribute($element, 'style:name');
        $element .= '/style:text-properties';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals("#$rgbColor", $doc->getElementAttribute($element, 'fo:color'));
        $span = "$s2t/text:p[4]/text:span";
        $this->assertTrue($doc->elementExists($span));
        $this->assertEquals($style, $doc->getElementAttribute($span, 'text:style-name'));
        $this->assertEquals($namedColor, $doc->getElement($span)->nodeValue);
    }

    /**
     * Test noproof
     */
    public function testNoProof()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Noproof not specified', array('color' => 'black'));
        $section->addText('Noproof is true', array('color' => 'black', 'noproof' => true));
        $section->addText('Noproof is false', array('color' => 'black', 'noproof' => false));

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:automatic-styles';
        $this->assertTrue($doc->elementExists($s2a));
        $s2t = '/office:document-content/office:body/office:text/text:section';
        $this->assertTrue($doc->elementExists($s2t));

        $element = "$s2a/style:style[3]";
        $this->assertTrue($doc->elementExists($element));
        $style = $doc->getElementAttribute($element, 'style:name');
        $element .= '/style:text-properties';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('', $doc->getElementAttribute($element, 'fo:language'));
        $span = "$s2t/text:p[2]/text:span";
        $this->assertTrue($doc->elementExists($span));
        $this->assertEquals($style, $doc->getElementAttribute($span, 'text:style-name'));
        $this->assertEquals('Noproof not specified', $doc->getElement($span)->nodeValue);

        $element = "$s2a/style:style[5]";
        $this->assertTrue($doc->elementExists($element));
        $style = $doc->getElementAttribute($element, 'style:name');
        $element .= '/style:text-properties';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('zxx', $doc->getElementAttribute($element, 'fo:language'));
        $this->assertEquals('zxx', $doc->getElementAttribute($element, 'style:language-asian'));
        $this->assertEquals('zxx', $doc->getElementAttribute($element, 'style:language-complex'));
        $this->assertEquals('none', $doc->getElementAttribute($element, 'fo:country'));
        $this->assertEquals('none', $doc->getElementAttribute($element, 'style:country-asian'));
        $this->assertEquals('none', $doc->getElementAttribute($element, 'style:country-complex'));
        $span = "$s2t/text:p[3]/text:span";
        $this->assertTrue($doc->elementExists($span));
        $this->assertEquals($style, $doc->getElementAttribute($span, 'text:style-name'));
        $this->assertEquals('Noproof is true', $doc->getElement($span)->nodeValue);

        $element = "$s2a/style:style[7]";
        $this->assertTrue($doc->elementExists($element));
        $style = $doc->getElementAttribute($element, 'style:name');
        $element .= '/style:text-properties';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('', $doc->getElementAttribute($element, 'fo:language'));
        $span = "$s2t/text:p[4]/text:span";
        $this->assertTrue($doc->elementExists($span));
        $this->assertEquals($style, $doc->getElementAttribute($span, 'text:style-name'));
        $this->assertEquals('Noproof is false', $doc->getElement($span)->nodeValue);
    }

    /**
     * Test using object with a name as font style for addText
     */
    public function testNamedStyleAsObject()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $named = $phpWord->addFontStyle('namedobject', array('color' => '008787'));
        $section = $phpWord->addSection();
        $section->addText('Let us see what color we wind up with', $named);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2t = '/office:document-content/office:body/office:text/text:section';
        $this->assertTrue($doc->elementExists($s2t));
        $element = "$s2t/text:p[2]/text:span";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('namedobject', $doc->getElementAttribute($element, 'text:style-name'));
    }

    /**
     * Test supplying field font style as array or object or string
     */
    public function testFieldStyles()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $namedstyle = $phpWord->addFontStyle('namedstyle', array('color' => '800000'));
        $section = $phpWord->addSection();
        $textrun = $section->addTextRun();
        $fld = $textrun->addField('DATE');
        $fld->setFontStyle('namedstyle');
        $textrun = $section->addTextRun();
        $fld = $textrun->addField('DATE');
        $fld->setFontStyle(array('color' => '008000'));
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
        $this->assertEquals('T1', $doc->getElementAttribute($element, 'style:name'));
        $this->assertEquals('#008000', $doc->getElementAttribute("$element/style:text-properties", 'fo:color'));
        $element = "$s2a/style:style[7]";
        $this->assertEquals('T2', $doc->getElementAttribute($element, 'style:name'));
        $this->assertEquals('#000080', $doc->getElementAttribute("$element/style:text-properties", 'fo:color'));

        $element = "$s2t/text:p[2]/text:span";
        $this->assertEquals('namedstyle', $doc->getElementAttribute($element, 'text:style-name'));
        $this->assertTrue($doc->elementExists("$element/text:date"));
        $element = "$s2t/text:p[3]/text:span";
        $this->assertEquals('T1', $doc->getElementAttribute($element, 'text:style-name'));
        $this->assertTrue($doc->elementExists("$element/text:date"));
        $element = "$s2t/text:p[4]/text:span";
        $this->assertEquals('T2', $doc->getElementAttribute($element, 'text:style-name'));
        $this->assertTrue($doc->elementExists("$element/text:date"));
        $element = "$s2t/text:p[5]/text:span";
        $this->assertEquals('namedstyle', $doc->getElementAttribute($element, 'text:style-name'));
    }
}
