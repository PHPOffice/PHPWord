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

use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\SimpleType\Color;
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
        Settings::restoreDefaults();
        TestHelperDOCX::clear();
    }

    public function testDefaultDefaults(): void
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        $file = 'styles.xml';

        $path = '/office:document-styles/office:styles/style:default-style/style:text-properties';
        self::assertTrue($doc->elementExists($path, $file));
        $element = $doc->getElement($path, $file);

        self::assertEquals('#000000', $element->getAttribute('fo:color'));
        self::assertEquals('false', $element->getAttribute('style:use-window-font-color')); //has to be set to false so that fo:color can take effect
    }

    public function testSettingDefaults(): void
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        $defaultFontColor = '00FF00';
        $phpWord->setDefaultFontColor($defaultFontColor);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        $file = 'styles.xml';

        $path = '/office:document-styles/office:styles/style:default-style/style:text-properties';
        self::assertTrue($doc->elementExists($path, $file));
        $element = $doc->getElement($path, $file);

        self::assertEquals('#' . $defaultFontColor, $element->getAttribute('fo:color'));
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
        $section->addText('This should be dark green (Color::DARKGREEN)', ['color' => Color::DARKGREEN]);
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
        self::assertEquals('This should be dark green (Color::DARKGREEN)', $doc->getElement($span)->nodeValue);
    }

    public static function providerAllNamedColors()
    {
        return [
            [Color::YELLOW, 'FFFF00'],
            [Color::LIGHTGREEN, '90EE90'],
            [Color::CYAN, '00FFFF'],
            [Color::MAGENTA, 'FF00FF'],
            [Color::BLUE, '0000FF'],
            [Color::RED, 'FF0000'],
            [Color::DARKBLUE, '00008B'],
            [Color::DARKCYAN, '008B8B'],
            [Color::DARKGREEN, '006400'],
            [Color::DARKMAGENTA, '8B008B'],
            [Color::DARKRED, '8B0000'],
            [Color::DARKYELLOW, '808000'],
            [Color::DARKGRAY, 'A9A9A9'],
            [Color::LIGHTGRAY, 'D3D3D3'],
            [Color::BLACK, '000000'],
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
        $font = new Font();
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

    public function testUnderline(): void
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->addFontStyle('underlineStyle', [
            'underline' => Font::UNDERLINE_DOTDOTDASH,
        ]);
        $section = $phpWord->addSection();
        $section->addText('Sample text.', 'underlineStyle');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $doc->setDefaultFile('styles.xml');
        $s2a = '/office:document-styles/office:styles';
        self::assertTrue($doc->elementExists($s2a));
        $style = "$s2a/style:style";
        self::assertTrue($doc->elementExists($style));
        self::assertSame('underlineStyle', $doc->getElementAttribute($style, 'style:name'));
        $properties = "$style/style:text-properties";
        self::assertTrue($doc->elementExists($properties));
        self::assertSame('dot-dot-dash', $doc->getElementAttribute($properties, 'style:text-underline-style'));
    }

    /**
     * Test underline color.
     */
    public function testUnderlineColor(): void
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();

        // Test 1: Set underline color to red
        $section->addText('Underline red', ['underline' => true, 'underlineColor' => 'FF0000']);

        // Test 2: Set underline color to green
        $section->addText('Underline green', ['underline' => true, 'underlineColor' => '00FF00']);

        // Test 3: No underline color (should not output attribute)
        $section->addText('No underline color', ['underline' => true, 'underlineColor' => '']);

        // Test 4: Use named color constant
        $section->addText('Underline darkblue', ['underline' => true, 'underlineColor' => Font::FGCOLOR_DARKBLUE]);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        $s2a = '/office:document-content/office:automatic-styles';
        self::assertTrue($doc->elementExists($s2a));

        // Check first text with red underline
        $element = "$s2a/style:style[3]";
        self::assertTrue($doc->elementExists($element));
        $styleName = $doc->getElementAttribute($element, 'style:name');
        $element .= '/style:text-properties';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('#FF0000', $doc->getElementAttribute($element, 'style:text-underline-color'));
        $span = '/office:document-content/office:body/office:text/text:section/text:p[2]/text:span';
        self::assertTrue($doc->elementExists($span));
        self::assertEquals($styleName, $doc->getElementAttribute($span, 'text:style-name'));
        self::assertEquals('Underline red', $doc->getElement($span)->nodeValue);

        // Check second text with green underline
        $element = "$s2a/style:style[5]";
        self::assertTrue($doc->elementExists($element));
        $styleName = $doc->getElementAttribute($element, 'style:name');
        $element .= '/style:text-properties';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('#00FF00', $doc->getElementAttribute($element, 'style:text-underline-color'));
        $span = '/office:document-content/office:body/office:text/text:section/text:p[3]/text:span';
        self::assertTrue($doc->elementExists($span));
        self::assertEquals($styleName, $doc->getElementAttribute($span, 'text:style-name'));
        self::assertEquals('Underline green', $doc->getElement($span)->nodeValue);

        // Check third text: no underline color → attribute should not be present
        $element = "$s2a/style:style[7]";
        self::assertTrue($doc->elementExists($element));
        $styleName = $doc->getElementAttribute($element, 'style:name');
        $element .= '/style:text-properties';
        self::assertTrue($doc->elementExists($element));
        self::assertSame('', $doc->getElementAttribute($element, 'style:text-underline-color'), 'style:text-underline-color should not be present when empty');
        $span = '/office:document-content/office:body/office:text/text:section/text:p[4]/text:span';
        self::assertTrue($doc->elementExists($span));
        self::assertEquals('No underline color', $doc->getElement($span)->nodeValue);
        self::assertEquals($styleName, $doc->getElementAttribute($span, 'text:style-name'));

        // Check fourth text: darkblue via constant
        $element = "$s2a/style:style[9]";
        self::assertTrue($doc->elementExists($element));
        $styleName = $doc->getElementAttribute($element, 'style:name');
        $element .= '/style:text-properties';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('#00008B', $doc->getElementAttribute($element, 'style:text-underline-color'));
        $span = '/office:document-content/office:body/office:text/text:section/text:p[5]/text:span';
        self::assertTrue($doc->elementExists($span));
        self::assertEquals($styleName, $doc->getElementAttribute($span, 'text:style-name'));
        self::assertEquals('Underline darkblue', $doc->getElement($span)->nodeValue);
    }
}
