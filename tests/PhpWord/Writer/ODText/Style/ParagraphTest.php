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
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\TestHelperDOCX;

/**
 * Test class for Headers, Footers, Tabs in ODT
 */
class ParagraphTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed after each method of the class
     */
    public function tearDown()
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test page break
     */
    public function testPageBreak()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Text on first page');
        $section->addPageBreak();
        $section->addText('Text on second page');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        $s2a = '/office:document-content/office:automatic-styles';
        $element = "$s2a/style:style[1]";
        $this->assertEquals('PB', $doc->getElementAttribute($element, 'style:name'));
        $element .= '/style:paragraph-properties';
        $this->assertEquals('page', $doc->getElementAttribute($element, 'fo:break-after'));
        $this->assertEquals('0cm', $doc->getElementAttribute($element, 'fo:margin-top'));
        $this->assertEquals('0cm', $doc->getElementAttribute($element, 'fo:margin-bottom'));

        $s2a = '/office:document-content/office:body/office:text/text:section';
        $element = "$s2a/text:p[3]";
        $this->assertEquals('PB', $doc->getElementAttribute($element, 'text:style-name'));
    }

    /**
     * Test normal/indent
     */
    public function testNormalIndent()
    {
        $phpWord = new PhpWord();
        $cvt = Converter::INCH_TO_TWIP;
        $indent1 = array('indentation' => array('left' => 0.50 * $cvt));
        $indent2 = array('indentation' => array('left' => 1.00 * $cvt, 'right' => 1.05 * $cvt));
        $indent3 = array('indentation' => array('left' => -0.50 * $cvt));
        $indent4 = array('indentation' => array('left' => 0 * $cvt));
        $phpWord->setDefaultParagraphStyle($indent1);
        $section = $phpWord->addSection();
        $section->addText('Should use default indent (0.5)');
        $section->addText('Should use non-default indent (1.0) on both sides, and here\'s an extra long line to prove it', null, $indent2);
        $section->addText('Should use non-default indent (-0.5)', null, $indent3);
        $section->addText('Should use non-default indent (0)', null, $indent4);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:automatic-styles';
        $this->assertTrue($doc->elementExists($s2a));

        $element = "$s2a/style:style[4]";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('Normal', $doc->getElementAttribute($element, 'style:parent-style-name'));
        $element .= '/style:paragraph-properties';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('', $doc->getElementAttribute($element, 'fo:margin-left'));
        $this->assertEquals('', $doc->getElementAttribute($element, 'fo:margin-right'));

        $element = "$s2a/style:style[6]/style:paragraph-properties";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('1in', $doc->getElementAttribute($element, 'fo:margin-left'));
        $this->assertEquals('1.05in', $doc->getElementAttribute($element, 'fo:margin-right'));

        $element = "$s2a/style:style[8]/style:paragraph-properties";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('-0.5in', $doc->getElementAttribute($element, 'fo:margin-left'));
        $this->assertEquals('0in', $doc->getElementAttribute($element, 'fo:margin-right'));

        $element = "$s2a/style:style[10]/style:paragraph-properties";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('0in', $doc->getElementAttribute($element, 'fo:margin-left'));
        $this->assertEquals('0in', $doc->getElementAttribute($element, 'fo:margin-right'));

        $doc->setDefaultFile('styles.xml');
        $element = '/office:document-styles/office:styles/style:style';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('Normal', $doc->getElementAttribute($element, 'style:name'));
        $element .= '/style:paragraph-properties';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('0.5in', $doc->getElementAttribute($element, 'fo:margin-left'));
        $this->assertEquals('0in', $doc->getElementAttribute($element, 'fo:margin-right'));
    }

    /**
     * Test textAlign
     */
    public function testTextAlign()
    {
        $phpWord = new PhpWord();
        $align1 = array('alignment' => 'end');
        $align2 = array('alignment' => 'start');
        $phpWord->setDefaultParagraphStyle($align1);
        $section = $phpWord->addSection();
        $section->addText('Should use default alignment (right for this doc)');
        $section->addText('Explicit left alignment', null, $align2);
        $section->addText('Explicit right alignment', null, $align1);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:automatic-styles';
        $this->assertTrue($doc->elementExists($s2a));

        $element = "$s2a/style:style[4]";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('Normal', $doc->getElementAttribute($element, 'style:parent-style-name'));
        $element .= '/style:paragraph-properties';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('', $doc->getElementAttribute($element, 'fo:text-align'));

        $element = "$s2a/style:style[6]/style:paragraph-properties";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('start', $doc->getElementAttribute($element, 'fo:text-align'));

        $element = "$s2a/style:style[8]/style:paragraph-properties";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('end', $doc->getElementAttribute($element, 'fo:text-align'));

        $doc->setDefaultFile('styles.xml');
        $element = '/office:document-styles/office:styles/style:style';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('Normal', $doc->getElementAttribute($element, 'style:name'));
        $element .= '/style:paragraph-properties';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('end', $doc->getElementAttribute($element, 'fo:text-align'));
    }

    /**
     * Test lineHeight
     */
    public function testLineHeight()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Should use line height 1.08, and here\'s a long line which ought to overflow onto a second line to prove it', null, array('lineHeight' => 1.08));
        $section->addText('Should use line height 1.20, and here\'s a long line which ought to overflow onto a second line to prove it', null, array('lineHeight' => 1.20));
        $section->addText('Should use line height 0.90, and here\'s a long line which ought to overflow onto a second line to prove it', null, array('lineHeight' => 0.90));

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:automatic-styles';
        $this->assertTrue($doc->elementExists($s2a));

        $element = "$s2a/style:style[4]/style:paragraph-properties";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('108%', $doc->getElementAttribute($element, 'fo:line-height'));

        $element = "$s2a/style:style[6]/style:paragraph-properties";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('120%', $doc->getElementAttribute($element, 'fo:line-height'));

        $element = "$s2a/style:style[8]/style:paragraph-properties";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('90%', $doc->getElementAttribute($element, 'fo:line-height'));
    }

    /**
     * Test SpaceBeforeAfter
     */
    public function testSpaceBeforeAfter()
    {
        $phpWord = new PhpWord();
        $phpWord->setDefaultParagraphStyle(array('spaceBefore' => 0, 'spaceAfter' => 0));
        $section = $phpWord->addSection();
        $section->addText('No spacing between this paragraph and next');
        $section->addText('No spacing between this paragraph and previous');
        $section->addText('No spacing before this but 100 after', null, array('spaceAfter' => 100));
        $section->addText('No spacing for this paragraph but previous specified 100 after and next specifies 100 before');
        $section->addText('No spacing after this but 100 before', null, array('spaceBefore' => 100));
        $section->addText('No spacing before this paragraph');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:automatic-styles';
        $this->assertTrue($doc->elementExists($s2a));

        $element = "$s2a/style:style[8]/style:paragraph-properties";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('', $doc->getElementAttribute($element, 'fo:margin-top'));
        $this->assertEquals('5pt', $doc->getElementAttribute($element, 'fo:margin-bottom'));

        $element = "$s2a/style:style[12]/style:paragraph-properties";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('5pt', $doc->getElementAttribute($element, 'fo:margin-top'));
        $this->assertEquals('', $doc->getElementAttribute($element, 'fo:margin-bottom'));

        $doc->setDefaultFile('styles.xml');
        $element = '/office:document-styles/office:styles/style:style';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('Normal', $doc->getElementAttribute($element, 'style:name'));
        $element .= '/style:paragraph-properties';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('0pt', $doc->getElementAttribute($element, 'fo:margin-top'));
        $this->assertEquals('0pt', $doc->getElementAttribute($element, 'fo:margin-bottom'));
    }

    /**
     * Test Page Break Before
     */
    public function testPageBreakBefore()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('This is my first paragraph.');
        $section->addText('This is my second paragraph, on a new page.', null, array('pageBreakBefore' => true));
        $section->addText('This is my third paragraph, on same page as second.');
        $section->addText('This is my fourth paragraph, on a new page.', null, array('pageBreakBefore' => true));

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:automatic-styles';
        $this->assertTrue($doc->elementExists($s2a));

        $element = "$s2a/style:style[4]/style:paragraph-properties";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('', $doc->getElementAttribute($element, 'fo:break-before'));
        $element = "$s2a/style:style[6]/style:paragraph-properties";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('page', $doc->getElementAttribute($element, 'fo:break-before'));
        $element = "$s2a/style:style[8]/style:paragraph-properties";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('', $doc->getElementAttribute($element, 'fo:break-before'));
        $element = "$s2a/style:style[10]/style:paragraph-properties";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('page', $doc->getElementAttribute($element, 'fo:break-before'));
    }

    /**
     * Test Heading Page Break Before
     */
    public function testHeadingPageBreakBefore()
    {
        $phpWord = new PhpWord();
        $phpWord->addTitleStyle(1, null, array('pageBreakBefore' => true));
        $phpWord->addTitleStyle(2, null, array());
        $section = $phpWord->addSection();
        $section->addTitle('Section1 Heading1 #1', 1);
        $section->addTitle('Section1 Heading2 #1', 2);
        $section->addTitle('Section1 Heading1 #2', 1);
        $section->addTitle('Section1 Heading2 #2', 2);
        $section = $phpWord->addSection();
        $section->addTitle('Section2 Heading1 #1', 1);
        $section->addTitle('Section2 Heading2 #1', 2);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:automatic-styles';
        $this->assertTrue($doc->elementExists($s2a));

        $element = "$s2a/style:style[4]";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('HD1', $doc->getElementAttribute($element, 'style:name'));
        $this->assertEquals('Heading_1', $doc->getElementAttribute($element, 'style:parent-style-name'));
        $element .= '/style:paragraph-properties';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('page', $doc->getElementAttribute($element, 'fo:break-before'));

        $element = "$s2a/style:style[5]";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('HE1', $doc->getElementAttribute($element, 'style:name'));
        $this->assertEquals('Heading_1', $doc->getElementAttribute($element, 'style:parent-style-name'));
        $element .= '/style:paragraph-properties';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('auto', $doc->getElementAttribute($element, 'fo:break-before'));

        $element = "$s2a/style:style[6]";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('HD2', $doc->getElementAttribute($element, 'style:name'));
        $this->assertEquals('Heading_2', $doc->getElementAttribute($element, 'style:parent-style-name'));
        $element .= '/style:paragraph-properties';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('', $doc->getElementAttribute($element, 'fo:break-before'));

        $element = "$s2a/style:style[7]";
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('HE2', $doc->getElementAttribute($element, 'style:name'));
        $this->assertEquals('Heading_2', $doc->getElementAttribute($element, 'style:parent-style-name'));
        $element .= '/style:paragraph-properties';
        $this->assertTrue($doc->elementExists($element));
        $this->assertEquals('auto', $doc->getElementAttribute($element, 'fo:break-before'));

        $s2a = '/office:document-content/office:body/office:text/text:section[1]';
        $this->assertTrue($doc->elementExists($s2a));
        $element = "$s2a/text:h[1]";
        $this->assertEquals('HE1', $doc->getElementAttribute($element, 'text:style-name'));
        $this->assertEquals('1', $doc->getElementAttribute($element, 'text:outline-level'));
        $element .= '/text:span';
        $this->assertEquals('Heading_1', $doc->getElementAttribute($element, 'text:style-name'));
        $element = "$s2a/text:h[2]";
        $this->assertEquals('HD2', $doc->getElementAttribute($element, 'text:style-name'));
        $this->assertEquals('2', $doc->getElementAttribute($element, 'text:outline-level'));
        $element .= '/text:span';
        $this->assertEquals('Heading_2', $doc->getElementAttribute($element, 'text:style-name'));
        $element = "$s2a/text:h[3]";
        $this->assertEquals('HD1', $doc->getElementAttribute($element, 'text:style-name'));
        $this->assertEquals('1', $doc->getElementAttribute($element, 'text:outline-level'));
        $element .= '/text:span';
        $this->assertEquals('Heading_1', $doc->getElementAttribute($element, 'text:style-name'));
        $element = "$s2a/text:h[4]";
        $this->assertEquals('HD2', $doc->getElementAttribute($element, 'text:style-name'));
        $this->assertEquals('2', $doc->getElementAttribute($element, 'text:outline-level'));
        $element .= '/text:span';
        $this->assertEquals('Heading_2', $doc->getElementAttribute($element, 'text:style-name'));

        $s2a = '/office:document-content/office:body/office:text/text:section[2]';
        $this->assertTrue($doc->elementExists($s2a));
        $element = "$s2a/text:h[1]";
        $this->assertEquals('HE1', $doc->getElementAttribute($element, 'text:style-name'));
        $this->assertEquals('1', $doc->getElementAttribute($element, 'text:outline-level'));
        $element .= '/text:span';
        $this->assertEquals('Heading_1', $doc->getElementAttribute($element, 'text:style-name'));
        $element = "$s2a/text:h[2]";
        $this->assertEquals('HD2', $doc->getElementAttribute($element, 'text:style-name'));
        $this->assertEquals('2', $doc->getElementAttribute($element, 'text:outline-level'));
        $element .= '/text:span';
        $this->assertEquals('Heading_2', $doc->getElementAttribute($element, 'text:style-name'));

        $doc->setDefaultFile('styles.xml');
        $s2a = '/office:document-styles/office:styles';
        $this->assertTrue($doc->elementExists($s2a));
        $element = "$s2a/style:style[1]";
        $this->assertEquals('Heading_1', $doc->getElementAttribute($element, 'style:name'));
        $this->assertEquals('paragraph', $doc->getElementAttribute($element, 'style:family'));
        $element .= '/style:paragraph-properties';
        $this->assertEquals('page', $doc->getElementAttribute($element, 'fo:break-before'));
        $element = "$s2a/style:style[3]";
        $this->assertEquals('Heading_2', $doc->getElementAttribute($element, 'style:name'));
        $this->assertEquals('paragraph', $doc->getElementAttribute($element, 'style:family'));
        $element .= '/style:paragraph-properties';
        $this->assertEquals('', $doc->getElementAttribute($element, 'fo:break-before'));
    }

    /**
     * Test text run paragraph style using named style
     */
    public function testTextRun()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->addParagraphStyle('parstyle1', array('align' => 'start'));
        $phpWord->addParagraphStyle('parstyle2', array('align' => 'end'));
        $section = $phpWord->addSection();
        $trx = $section->addTextRun('parstyle1');
        $trx->addText('First text in textrun. ');
        $trx->addText('Second text - paragraph style is specified but ignored.', null, 'parstyle2');
        $section->addText('Third text added to section not textrun - paragraph style is specified and used.', null, 'parstyle2');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:automatic-styles';
        $element = "$s2a/style:style[3]";
        $this->assertEquals('P1_parstyle1', $doc->getElementAttribute($element, 'style:name'));
        $this->assertEquals('parstyle1', $doc->getElementAttribute($element, 'style:parent-style-name'));
        $element = "$s2a/style:style[9]";
        $this->assertEquals('P4_parstyle2', $doc->getElementAttribute($element, 'style:name'));
        $this->assertEquals('parstyle2', $doc->getElementAttribute($element, 'style:parent-style-name'));

        $s2a = '/office:document-content/office:body/office:text/text:section';
        $element = "$s2a/text:p[2]";
        $this->assertEquals('P1_parstyle1', $doc->getElementAttribute($element, 'text:style-name'));
        $element = "$s2a/text:p[3]";
        $this->assertEquals('P4_parstyle2', $doc->getElementAttribute($element, 'text:style-name'));

        $doc->setDefaultFile('styles.xml');
        $element = '/office:document-styles/office:styles/style:style[1]';
        $this->assertEquals('parstyle1', $doc->getElementAttribute($element, 'style:name'));
        $element .= '/style:paragraph-properties';
        $this->assertEquals('start', $doc->getElementAttribute($element, 'fo:text-align'));
        $element = '/office:document-styles/office:styles/style:style[2]';
        $this->assertEquals('parstyle2', $doc->getElementAttribute($element, 'style:name'));
        $element .= '/style:paragraph-properties';
        $this->assertEquals('end', $doc->getElementAttribute($element, 'fo:text-align'));
    }

    /**
     * Test text run paragraph style using unnamed style
     */
    public function testTextRunUnnamed()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $parstyle1 = array('align' => 'start');
        $parstyle2 = array('align' => 'end');
        $section = $phpWord->addSection();
        $trx = $section->addTextRun($parstyle1);
        $trx->addText('First text in textrun. ');
        $trx->addText('Second text - paragraph style is specified but ignored.', null, $parstyle2);
        $section->addText('Third text added to section not textrun - paragraph style is specified and used.', null, $parstyle2);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:automatic-styles';
        $element = "$s2a/style:style[3]";
        $this->assertEquals('P1', $doc->getElementAttribute($element, 'style:name'));
        $this->assertEquals('Normal', $doc->getElementAttribute($element, 'style:parent-style-name'));
        $element .= '/style:paragraph-properties';
        $this->assertEquals('start', $doc->getElementAttribute($element, 'fo:text-align'));
        $element = "$s2a/style:style[9]";
        $this->assertEquals('P4', $doc->getElementAttribute($element, 'style:name'));
        $this->assertEquals('Normal', $doc->getElementAttribute($element, 'style:parent-style-name'));
        $element .= '/style:paragraph-properties';
        $this->assertEquals('end', $doc->getElementAttribute($element, 'fo:text-align'));

        $s2a = '/office:document-content/office:body/office:text/text:section';
        $element = "$s2a/text:p[2]";
        $this->assertEquals('P1', $doc->getElementAttribute($element, 'text:style-name'));
        $element = "$s2a/text:p[3]";
        $this->assertEquals('P4', $doc->getElementAttribute($element, 'text:style-name'));
    }

    /**
     * Test Empty font and paragraph styles
     */
    public function testEmptyFontAndParagraphStyles()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $phpWord->addFontStyle('namedfont', array('name' => 'Courier New', 'size' => 8));
        $phpWord->addParagraphStyle('namedpar', array('lineHeight' => 1.08));
        $section->addText('Empty Font Style     and Empty Paragraph Style', '', '');
        $section->addText('Named Font Style     and Empty Paragraph Style', 'namedfont', '');
        $section->addText('Empty Font Style     and Named Paragraph Style', '', 'namedpar');
        $section->addText('Named Font Style     and Named Paragraph Style', 'namedfont', 'namedpar');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:body/office:text/text:section';
        $element = "$s2a/text:p[2]";
        $this->assertEquals('Normal', $doc->getElementAttribute($element, 'text:style-name'));
        $this->assertEquals(5, $doc->getElementAttribute("$element/text:s", 'text:c'));
        $this->assertFalse($doc->elementExists("$element/text:span"));
        $element = "$s2a/text:p[3]";
        $this->assertEquals('Normal', $doc->getElementAttribute($element, 'text:style-name'));
        $this->assertEquals('namedfont', $doc->getElementAttribute("$element/text:span", 'text:style-name'));
        $element = "$s2a/text:p[4]";
        $this->assertEquals('P1_namedpar', $doc->getElementAttribute($element, 'text:style-name'));
        $this->assertFalse($doc->elementExists("$element/text:span"));
        $element = "$s2a/text:p[5]";
        $this->assertEquals('P2_namedpar', $doc->getElementAttribute($element, 'text:style-name'));
        $this->assertEquals('namedfont', $doc->getElementAttribute("$element/text:span", 'text:style-name'));
    }
}
