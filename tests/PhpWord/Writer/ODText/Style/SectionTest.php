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

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TestHelperDOCX;

/**
 * Test class for Headers, Footers, Tabs in ODT
 */
class SectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed after each method of the class
     */
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test various section styles, including header, footer, and tabs
     */
    public function testHeaderFooterTabs()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $margins = \PhpOffice\PhpWord\Shared\Converter::INCH_TO_TWIP;
        $phpWord->addFontStyle('hdrstyle1', array('name' => 'Courier New', 'size' => 8));
        $section = $phpWord->addSection(array('paperSize' => 'Letter', 'marginTop' => $margins, 'marginBottom' => $margins));
        $header = $section->createHeader();
        $phpWord->addParagraphStyle('centerheader', array('align' => 'center'));
        $header->addText('Centered Header', 'hdrstyle1', 'centerheader');
        $footer = $section->createFooter();
        $sizew = $section->getStyle()->getPageSizeW();
        $sizel = $section->getStyle()->getMarginLeft();
        $sizer = $section->getStyle()->getMarginRight();
        $footerwidth = $sizew - $sizel - $sizer;
        $phpWord->addParagraphStyle(
            'footerTab',
            array(
                'tabs' => array(
                    new \PhpOffice\PhpWord\Style\Tab('center', (int) ($footerwidth / 2)),
                    new \PhpOffice\PhpWord\Style\Tab('right', (int) $footerwidth),
                ),
            )
        );
        $textrun = $footer->addTextRun('footerTab');
        $textrun->addText('Left footer', 'hdrstyle1');
        $textrun->addText("\t", 'hdrstyle1');
        $fld = $textrun->addField('DATE');
        $fld->setFontStyle('hdrstyle1');
        $textrun->addText("\t", 'hdrstyle1');
        $textrun->addText('Page ', 'hdrstyle1');
        $fld = $textrun->addField('PAGE');
        $fld->setFontStyle('hdrstyle1');
        $textrun->addText(' of ', 'hdrstyle1');
        $fld = $textrun->addField('NUMPAGES');
        $fld->setFontStyle('hdrstyle1');
        $section->addText('First page');
        $section->addPageBreak();
        $section->addText('Second page');
        $section->addPageBreak();
        $section->addText('Third page');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $doc->setDefaultFile('styles.xml');
        $s2a = '/office:document-styles/office:automatic-styles';
        $element = "$s2a/style:page-layout/style:page-layout-properties";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('8.5in', $doc->getElementAttribute($element, 'fo:page-width'));
        $this->assertEquals('11in', $doc->getElementAttribute($element, 'fo:page-height'));
        $this->assertEquals('0.5in', $doc->getElementAttribute($element, 'fo:margin-top'));
        $this->assertEquals('0.5in', $doc->getElementAttribute($element, 'fo:margin-bottom'));

        $s2s = '/office:document-styles/office:styles';
        $element = "$s2s/style:style[1]";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('hdrstyle1', $doc->getElementAttribute($element, 'style:name'));
        $tprop = "$element/style:text-properties";
        $this->assertTrue($doc->elementExists($tprop));
        $this->assertEquals('Courier New', $doc->getElementAttribute($tprop, 'style:font-name'));

        $element = "$s2s/style:style[2]";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('centerheader', $doc->getElementAttribute($element, 'style:name'));
        $tprop = "$element/style:paragraph-properties";
        $this->assertTrue($doc->elementExists($tprop));
        $this->assertEquals('center', $doc->getElementAttribute($tprop, 'fo:text-align'));

        $element = "$s2s/style:style[3]";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('footerTab', $doc->getElementAttribute($element, 'style:name'));
        $tprop = "$element/style:paragraph-properties/style:tab-stops";
        $this->assertTrue($doc->elementExists($tprop));
        $tstop = "$tprop/style:tab-stop[1]";
        $this->assertTrue($doc->elementExists($tstop));
        $this->assertEquals('center', $doc->getElementAttribute($tstop, 'style:type'));
        $this->assertEquals('3.25in', $doc->getElementAttribute($tstop, 'style:position'));
        $tstop = "$tprop/style:tab-stop[2]";
        $this->assertTrue($doc->elementExists($tstop));
        $this->assertEquals('right', $doc->getElementAttribute($tstop, 'style:type'));
        $this->assertEquals('6.5in', $doc->getElementAttribute($tstop, 'style:position'));

        $s2s = '/office:document-styles/office:master-styles/style:master-page/style:footer/text:p';
        $this->assertTrue($doc->elementExists($s2s));
        $element = "$s2s/text:span[1]";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('hdrstyle1', $doc->getElementAttribute($element, 'text:style-name'));
        $this->assertEquals('Left footer', $doc->getElement($element)->nodeValue);
        $element = "$s2s/text:span[2]/text:tab";
        $this->assertTrue($doc->elementExists($element));
        $element = "$s2s/text:span[3]/text:date";
        $this->assertTrue($doc->elementExists($element));
        $element = "$s2s/text:span[4]/text:tab";
        $this->assertTrue($doc->elementExists($element));
        $element = "$s2s/text:span[5]";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('Page', $doc->getElement($element)->nodeValue);
        $this->assertTrue($doc->elementExists("$element/text:s"));
        $element = "$s2s/text:span[6]/text:page-number";
        $this->assertTrue($doc->elementExists($element));
        $element = "$s2s/text:span[7]";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('of', $doc->getElement($element)->nodeValue);
        $this->assertTrue($doc->elementExists("$element/text:s"));
        $this->assertTrue($doc->elementExists("$element/text:s[2]"));
        $element = "$s2s/text:span[8]/text:page-count";
        $this->assertTrue($doc->elementExists($element));
    }

    /**
     * Test HideErrors
     */
    public function testHideErrors()
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()->setHideGrammaticalErrors(true);
        $phpWord->getSettings()->setHideSpellingErrors(true);
        $phpWord->getSettings()->setThemeFontLang(new \PhpOffice\PhpWord\Style\Language('en-US'));
        $phpWord->getSettings()->getThemeFontLang()->setLangId(\PhpOffice\PhpWord\Style\Language::EN_US_ID);
        $section = $phpWord->addSection();
        $section->addText('Here is a paragraph with some speling errorz');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $doc->setDefaultFile('styles.xml');
        $element = '/office:document-styles/office:styles/style:default-style/style:text-properties';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('zxx', $doc->getElementAttribute($element, 'fo:language'));
        $this->assertEquals('zxx', $doc->getElementAttribute($element, 'style:language-asian'));
        $this->assertEquals('zxx', $doc->getElementAttribute($element, 'style:language-complex'));
        $this->assertEquals('none', $doc->getElementAttribute($element, 'fo:country'));
        $this->assertEquals('none', $doc->getElementAttribute($element, 'style:country-asian'));
        $this->assertEquals('none', $doc->getElementAttribute($element, 'style:country-complex'));
    }

    /**
     * Test SpaceBeforeAfter
     */
    public function testMultipleSections()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection(array('paperSize' => 'Letter', 'Orientation' => 'portrait'));
        $section->addText('This section uses Letter paper in portrait orientation.');
        $section = $phpWord->addSection(array('paperSize' => 'A4', 'Orientation' => 'landscape', 'pageNumberingStart' => '9'));
        $header = $section->createHeader();
        $header->addField('PAGE');
        $section->addText('This section uses A4 paper in landscape orientation. It should have a page break beforehand. It artificially starts on page 9.');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:automatic-styles';
        $s2t = '/office:document-content/office:body/office:text';
        $this->assertTrue($doc->elementExists($s2a));
        $this->assertTrue($doc->elementExists($s2t));

        $element = "$s2a/style:style[2]";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('SB1', $doc->getElementAttribute($element, 'style:name'));
        $this->assertEquals('Standard1', $doc->getElementAttribute($element, 'style:master-page-name'));
        $element .= '/style:text-properties';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('none', $doc->getElementAttribute($element, 'text:display'));
        $element = "$s2a/style:style[3]";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('SB2', $doc->getElementAttribute($element, 'style:name'));
        $this->assertEquals('Standard2', $doc->getElementAttribute($element, 'style:master-page-name'));
        $elemen2 = "$element/style:paragraph-properties";
        $this->assertEquals('9', $doc->getElementAttribute($elemen2, 'style:page-number'));
        $element .= '/style:text-properties';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('none', $doc->getElementAttribute($element, 'text:display'));

        $element = "$s2t/text:section[1]";
        $this->assertTrue($doc->elementExists($element));
        $element .= '/text:p[1]';
        $this->assertEquals('SB1', $doc->getElementAttribute($element, 'text:style-name'));
        $element = "$s2t/text:section[2]";
        $this->assertTrue($doc->elementExists($element));
        $element .= '/text:p[1]';
        $this->assertEquals('SB2', $doc->getElementAttribute($element, 'text:style-name'));

        $doc->setDefaultFile('styles.xml');
        $s2a = '/office:document-styles/office:automatic-styles';
        $this->assertTrue($doc->elementExists($s2a));

        $element = "$s2a/style:page-layout[1]";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('Mpm1', $doc->getElementAttribute($element, 'style:name'));
        $element .= '/style:page-layout-properties';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('8.5in', $doc->getElementAttribute($element, 'fo:page-width'));
        $this->assertEquals('11in', $doc->getElementAttribute($element, 'fo:page-height'));
        $this->assertEquals('portrait', $doc->getElementAttribute($element, 'style:print-orientation'));

        $element = "$s2a/style:page-layout[2]";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('Mpm2', $doc->getElementAttribute($element, 'style:name'));
        $element .= '/style:page-layout-properties';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('29.7cm', $doc->getElementAttribute($element, 'fo:page-width'));
        $this->assertEquals('21cm', $doc->getElementAttribute($element, 'fo:page-height'));
        $this->assertEquals('landscape', $doc->getElementAttribute($element, 'style:print-orientation'));

        $s2a = '/office:document-styles/office:master-styles';
        $this->assertTrue($doc->elementExists($s2a));
        $element = "$s2a/style:master-page[1]";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('Standard1', $doc->getElementAttribute($element, 'style:name'));
        $this->assertEquals('Mpm1', $doc->getElementAttribute($element, 'style:page-layout-name'));
        $element = "$s2a/style:master-page[2]";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('Standard2', $doc->getElementAttribute($element, 'style:name'));
        $this->assertEquals('Mpm2', $doc->getElementAttribute($element, 'style:page-layout-name'));
    }
}
