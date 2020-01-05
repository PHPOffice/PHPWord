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
}
