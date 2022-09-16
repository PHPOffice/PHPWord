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

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for Headers, Footers, Tabs in ODT.
 */
class ParagraphTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed after each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test page break.
     */
    public function testPageBreak(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Text on first page');
        $section->addPageBreak();
        $section->addText('Text on second page');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');

        $s2a = '/office:document-content/office:automatic-styles';
        $element = "$s2a/style:style[1]";
        self::assertEquals('PB', $doc->getElementAttribute($element, 'style:name'));
        $element .= '/style:paragraph-properties';
        self::assertEquals('page', $doc->getElementAttribute($element, 'fo:break-after'));
        self::assertEquals('0cm', $doc->getElementAttribute($element, 'fo:margin-top'));
        self::assertEquals('0cm', $doc->getElementAttribute($element, 'fo:margin-bottom'));

        $s2a = '/office:document-content/office:body/office:text/text:section';
        $element = "$s2a/text:p[3]";
        self::assertEquals('PB', $doc->getElementAttribute($element, 'text:style-name'));
    }

    /**
     * Test normal/indent.
     */
    public function testNormalIndent(): void
    {
        $phpWord = new PhpWord();
        $cvt = Converter::INCH_TO_TWIP;
        $indent1 = ['indentation' => ['left' => 0.50 * $cvt]];
        $indent2 = ['indentation' => ['left' => 1.00 * $cvt, 'right' => 1.05 * $cvt]];
        $indent3 = ['indentation' => ['left' => -0.50 * $cvt]];
        $indent4 = ['indentation' => ['left' => 0 * $cvt]];
        $phpWord->setDefaultParagraphStyle($indent1);
        $section = $phpWord->addSection();
        $section->addText('Should use default indent (0.5)');
        $section->addText('Should use non-default indent (1.0) on both sides, and here\'s an extra long line to prove it', null, $indent2);
        $section->addText('Should use non-default indent (-0.5)', null, $indent3);
        $section->addText('Should use non-default indent (0)', null, $indent4);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:automatic-styles';
        self::assertTrue($doc->elementExists($s2a));

        $element = "$s2a/style:style[4]";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('Normal', $doc->getElementAttribute($element, 'style:parent-style-name'));
        $element .= '/style:paragraph-properties';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('', $doc->getElementAttribute($element, 'fo:margin-left'));
        self::assertEquals('', $doc->getElementAttribute($element, 'fo:margin-right'));

        $element = "$s2a/style:style[6]/style:paragraph-properties";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('1in', $doc->getElementAttribute($element, 'fo:margin-left'));
        self::assertEquals('1.05in', $doc->getElementAttribute($element, 'fo:margin-right'));

        $element = "$s2a/style:style[8]/style:paragraph-properties";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('-0.5in', $doc->getElementAttribute($element, 'fo:margin-left'));
        self::assertEquals('0in', $doc->getElementAttribute($element, 'fo:margin-right'));

        $element = "$s2a/style:style[10]/style:paragraph-properties";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('0in', $doc->getElementAttribute($element, 'fo:margin-left'));
        self::assertEquals('0in', $doc->getElementAttribute($element, 'fo:margin-right'));

        $doc->setDefaultFile('styles.xml');
        $element = '/office:document-styles/office:styles/style:style';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('Normal', $doc->getElementAttribute($element, 'style:name'));
        $element .= '/style:paragraph-properties';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('0.5in', $doc->getElementAttribute($element, 'fo:margin-left'));
        self::assertEquals('0in', $doc->getElementAttribute($element, 'fo:margin-right'));
    }

    /**
     * Test textAlign.
     */
    public function testTextAlign(): void
    {
        $phpWord = new PhpWord();
        $align1 = ['alignment' => 'end'];
        $align2 = ['alignment' => 'start'];
        $phpWord->setDefaultParagraphStyle($align1);
        $section = $phpWord->addSection();
        $section->addText('Should use default alignment (right for this doc)');
        $section->addText('Explicit left alignment', null, $align2);
        $section->addText('Explicit right alignment', null, $align1);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:automatic-styles';
        self::assertTrue($doc->elementExists($s2a));

        $element = "$s2a/style:style[4]";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('Normal', $doc->getElementAttribute($element, 'style:parent-style-name'));
        $element .= '/style:paragraph-properties';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('', $doc->getElementAttribute($element, 'fo:text-align'));

        $element = "$s2a/style:style[6]/style:paragraph-properties";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('start', $doc->getElementAttribute($element, 'fo:text-align'));

        $element = "$s2a/style:style[8]/style:paragraph-properties";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('end', $doc->getElementAttribute($element, 'fo:text-align'));

        $doc->setDefaultFile('styles.xml');
        $element = '/office:document-styles/office:styles/style:style';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('Normal', $doc->getElementAttribute($element, 'style:name'));
        $element .= '/style:paragraph-properties';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('end', $doc->getElementAttribute($element, 'fo:text-align'));
    }

    /**
     * Test lineHeight.
     */
    public function testLineHeight(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Should use line height 1.08, and here\'s a long line which ought to overflow onto a second line to prove it', null, ['lineHeight' => 1.08]);
        $section->addText('Should use line height 1.20, and here\'s a long line which ought to overflow onto a second line to prove it', null, ['lineHeight' => 1.20]);
        $section->addText('Should use line height 0.90, and here\'s a long line which ought to overflow onto a second line to prove it', null, ['lineHeight' => 0.90]);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:automatic-styles';
        self::assertTrue($doc->elementExists($s2a));

        $element = "$s2a/style:style[4]/style:paragraph-properties";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('108%', $doc->getElementAttribute($element, 'fo:line-height'));

        $element = "$s2a/style:style[6]/style:paragraph-properties";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('120%', $doc->getElementAttribute($element, 'fo:line-height'));

        $element = "$s2a/style:style[8]/style:paragraph-properties";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('90%', $doc->getElementAttribute($element, 'fo:line-height'));
    }

    /**
     * Test SpaceBeforeAfter.
     */
    public function testSpaceBeforeAfter(): void
    {
        $phpWord = new PhpWord();
        $phpWord->setDefaultParagraphStyle(['spaceBefore' => 0, 'spaceAfter' => 0]);
        $section = $phpWord->addSection();
        $section->addText('No spacing between this paragraph and next');
        $section->addText('No spacing between this paragraph and previous');
        $section->addText('No spacing before this but 100 after', null, ['spaceAfter' => 100]);
        $section->addText('No spacing for this paragraph but previous specified 100 after and next specifies 100 before');
        $section->addText('No spacing after this but 100 before', null, ['spaceBefore' => 100]);
        $section->addText('No spacing before this paragraph');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:automatic-styles';
        self::assertTrue($doc->elementExists($s2a));

        $element = "$s2a/style:style[8]/style:paragraph-properties";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('', $doc->getElementAttribute($element, 'fo:margin-top'));
        self::assertEquals('5pt', $doc->getElementAttribute($element, 'fo:margin-bottom'));

        $element = "$s2a/style:style[12]/style:paragraph-properties";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('5pt', $doc->getElementAttribute($element, 'fo:margin-top'));
        self::assertEquals('', $doc->getElementAttribute($element, 'fo:margin-bottom'));

        $doc->setDefaultFile('styles.xml');
        $element = '/office:document-styles/office:styles/style:style';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('Normal', $doc->getElementAttribute($element, 'style:name'));
        $element .= '/style:paragraph-properties';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('0pt', $doc->getElementAttribute($element, 'fo:margin-top'));
        self::assertEquals('0pt', $doc->getElementAttribute($element, 'fo:margin-bottom'));
    }

    /**
     * Test Page Break Before.
     */
    public function testPageBreakBefore(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('This is my first paragraph.');
        $section->addText('This is my second paragraph, on a new page.', null, ['pageBreakBefore' => true]);
        $section->addText('This is my third paragraph, on same page as second.');
        $section->addText('This is my fourth paragraph, on a new page.', null, ['pageBreakBefore' => true]);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:automatic-styles';
        self::assertTrue($doc->elementExists($s2a));

        $element = "$s2a/style:style[4]/style:paragraph-properties";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('', $doc->getElementAttribute($element, 'fo:break-before'));
        $element = "$s2a/style:style[6]/style:paragraph-properties";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('page', $doc->getElementAttribute($element, 'fo:break-before'));
        $element = "$s2a/style:style[8]/style:paragraph-properties";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('', $doc->getElementAttribute($element, 'fo:break-before'));
        $element = "$s2a/style:style[10]/style:paragraph-properties";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('page', $doc->getElementAttribute($element, 'fo:break-before'));
    }

    /**
     * Test Heading Page Break Before.
     */
    public function testHeadingPageBreakBefore(): void
    {
        $phpWord = new PhpWord();
        $phpWord->addTitleStyle(1, null, ['pageBreakBefore' => true]);
        $phpWord->addTitleStyle(2, null, []);
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
        self::assertTrue($doc->elementExists($s2a));

        $element = "$s2a/style:style[4]";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('HD1', $doc->getElementAttribute($element, 'style:name'));
        self::assertEquals('Heading_1', $doc->getElementAttribute($element, 'style:parent-style-name'));
        $element .= '/style:paragraph-properties';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('page', $doc->getElementAttribute($element, 'fo:break-before'));

        $element = "$s2a/style:style[5]";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('HE1', $doc->getElementAttribute($element, 'style:name'));
        self::assertEquals('Heading_1', $doc->getElementAttribute($element, 'style:parent-style-name'));
        $element .= '/style:paragraph-properties';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('auto', $doc->getElementAttribute($element, 'fo:break-before'));

        $element = "$s2a/style:style[6]";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('HD2', $doc->getElementAttribute($element, 'style:name'));
        self::assertEquals('Heading_2', $doc->getElementAttribute($element, 'style:parent-style-name'));
        $element .= '/style:paragraph-properties';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('', $doc->getElementAttribute($element, 'fo:break-before'));

        $element = "$s2a/style:style[7]";
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('HE2', $doc->getElementAttribute($element, 'style:name'));
        self::assertEquals('Heading_2', $doc->getElementAttribute($element, 'style:parent-style-name'));
        $element .= '/style:paragraph-properties';
        self::assertTrue($doc->elementExists($element));
        self::assertEquals('auto', $doc->getElementAttribute($element, 'fo:break-before'));

        $s2a = '/office:document-content/office:body/office:text/text:section[1]';
        self::assertTrue($doc->elementExists($s2a));
        $element = "$s2a/text:h[1]";
        self::assertEquals('HE1', $doc->getElementAttribute($element, 'text:style-name'));
        self::assertEquals('1', $doc->getElementAttribute($element, 'text:outline-level'));
        $element .= '/text:span';
        self::assertEquals('Heading_1', $doc->getElementAttribute($element, 'text:style-name'));
        $element = "$s2a/text:h[2]";
        self::assertEquals('HD2', $doc->getElementAttribute($element, 'text:style-name'));
        self::assertEquals('2', $doc->getElementAttribute($element, 'text:outline-level'));
        $element .= '/text:span';
        self::assertEquals('Heading_2', $doc->getElementAttribute($element, 'text:style-name'));
        $element = "$s2a/text:h[3]";
        self::assertEquals('HD1', $doc->getElementAttribute($element, 'text:style-name'));
        self::assertEquals('1', $doc->getElementAttribute($element, 'text:outline-level'));
        $element .= '/text:span';
        self::assertEquals('Heading_1', $doc->getElementAttribute($element, 'text:style-name'));
        $element = "$s2a/text:h[4]";
        self::assertEquals('HD2', $doc->getElementAttribute($element, 'text:style-name'));
        self::assertEquals('2', $doc->getElementAttribute($element, 'text:outline-level'));
        $element .= '/text:span';
        self::assertEquals('Heading_2', $doc->getElementAttribute($element, 'text:style-name'));

        $s2a = '/office:document-content/office:body/office:text/text:section[2]';
        self::assertTrue($doc->elementExists($s2a));
        $element = "$s2a/text:h[1]";
        self::assertEquals('HE1', $doc->getElementAttribute($element, 'text:style-name'));
        self::assertEquals('1', $doc->getElementAttribute($element, 'text:outline-level'));
        $element .= '/text:span';
        self::assertEquals('Heading_1', $doc->getElementAttribute($element, 'text:style-name'));
        $element = "$s2a/text:h[2]";
        self::assertEquals('HD2', $doc->getElementAttribute($element, 'text:style-name'));
        self::assertEquals('2', $doc->getElementAttribute($element, 'text:outline-level'));
        $element .= '/text:span';
        self::assertEquals('Heading_2', $doc->getElementAttribute($element, 'text:style-name'));

        $doc->setDefaultFile('styles.xml');
        $s2a = '/office:document-styles/office:styles';
        self::assertTrue($doc->elementExists($s2a));
        $element = "$s2a/style:style[1]";
        self::assertEquals('Heading_1', $doc->getElementAttribute($element, 'style:name'));
        self::assertEquals('paragraph', $doc->getElementAttribute($element, 'style:family'));
        $element .= '/style:paragraph-properties';
        self::assertEquals('page', $doc->getElementAttribute($element, 'fo:break-before'));
        $element = "$s2a/style:style[3]";
        self::assertEquals('Heading_2', $doc->getElementAttribute($element, 'style:name'));
        self::assertEquals('paragraph', $doc->getElementAttribute($element, 'style:family'));
        $element .= '/style:paragraph-properties';
        self::assertEquals('', $doc->getElementAttribute($element, 'fo:break-before'));
    }

    /**
     * Test text run paragraph style using named style.
     */
    public function testTextRun(): void
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->addParagraphStyle('parstyle1', ['align' => 'start']);
        $phpWord->addParagraphStyle('parstyle2', ['align' => 'end']);
        $section = $phpWord->addSection();
        $trx = $section->addTextRun('parstyle1');
        $trx->addText('First text in textrun. ');
        $trx->addText('Second text - paragraph style is specified but ignored.', null, 'parstyle2');
        $section->addText('Third text added to section not textrun - paragraph style is specified and used.', null, 'parstyle2');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:automatic-styles';
        $element = "$s2a/style:style[3]";
        self::assertEquals('P1_parstyle1', $doc->getElementAttribute($element, 'style:name'));
        self::assertEquals('parstyle1', $doc->getElementAttribute($element, 'style:parent-style-name'));
        $element = "$s2a/style:style[9]";
        self::assertEquals('P4_parstyle2', $doc->getElementAttribute($element, 'style:name'));
        self::assertEquals('parstyle2', $doc->getElementAttribute($element, 'style:parent-style-name'));

        $s2a = '/office:document-content/office:body/office:text/text:section';
        $element = "$s2a/text:p[2]";
        self::assertEquals('P1_parstyle1', $doc->getElementAttribute($element, 'text:style-name'));
        $element = "$s2a/text:p[3]";
        self::assertEquals('P4_parstyle2', $doc->getElementAttribute($element, 'text:style-name'));

        $doc->setDefaultFile('styles.xml');
        $element = '/office:document-styles/office:styles/style:style[1]';
        self::assertEquals('parstyle1', $doc->getElementAttribute($element, 'style:name'));
        $element .= '/style:paragraph-properties';
        self::assertEquals('start', $doc->getElementAttribute($element, 'fo:text-align'));
        $element = '/office:document-styles/office:styles/style:style[2]';
        self::assertEquals('parstyle2', $doc->getElementAttribute($element, 'style:name'));
        $element .= '/style:paragraph-properties';
        self::assertEquals('end', $doc->getElementAttribute($element, 'fo:text-align'));
    }

    /**
     * Test text run paragraph style using unnamed style.
     */
    public function testTextRunUnnamed(): void
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $parstyle1 = ['align' => 'start'];
        $parstyle2 = ['align' => 'end'];
        $section = $phpWord->addSection();
        $trx = $section->addTextRun($parstyle1);
        $trx->addText('First text in textrun. ');
        $trx->addText('Second text - paragraph style is specified but ignored.', null, $parstyle2);
        $section->addText('Third text added to section not textrun - paragraph style is specified and used.', null, $parstyle2);

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:automatic-styles';
        $element = "$s2a/style:style[3]";
        self::assertEquals('P1', $doc->getElementAttribute($element, 'style:name'));
        self::assertEquals('Normal', $doc->getElementAttribute($element, 'style:parent-style-name'));
        $element .= '/style:paragraph-properties';
        self::assertEquals('start', $doc->getElementAttribute($element, 'fo:text-align'));
        $element = "$s2a/style:style[9]";
        self::assertEquals('P4', $doc->getElementAttribute($element, 'style:name'));
        self::assertEquals('Normal', $doc->getElementAttribute($element, 'style:parent-style-name'));
        $element .= '/style:paragraph-properties';
        self::assertEquals('end', $doc->getElementAttribute($element, 'fo:text-align'));

        $s2a = '/office:document-content/office:body/office:text/text:section';
        $element = "$s2a/text:p[2]";
        self::assertEquals('P1', $doc->getElementAttribute($element, 'text:style-name'));
        $element = "$s2a/text:p[3]";
        self::assertEquals('P4', $doc->getElementAttribute($element, 'text:style-name'));
    }

    /**
     * Test Empty font and paragraph styles.
     */
    public function testEmptyFontAndParagraphStyles(): void
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $phpWord->addFontStyle('namedfont', ['name' => 'Courier New', 'size' => 8]);
        $phpWord->addParagraphStyle('namedpar', ['lineHeight' => 1.08]);
        $section->addText('Empty Font Style     and Empty Paragraph Style', '', '');
        $section->addText('Named Font Style     and Empty Paragraph Style', 'namedfont', '');
        $section->addText('Empty Font Style     and Named Paragraph Style', '', 'namedpar');
        $section->addText('Named Font Style     and Named Paragraph Style', 'namedfont', 'namedpar');

        $doc = TestHelperDOCX::getDocument($phpWord, 'ODText');
        $s2a = '/office:document-content/office:body/office:text/text:section';
        $element = "$s2a/text:p[2]";
        self::assertEquals('Normal', $doc->getElementAttribute($element, 'text:style-name'));
        self::assertEquals(5, $doc->getElementAttribute("$element/text:s", 'text:c'));
        self::assertFalse($doc->elementExists("$element/text:span"));
        $element = "$s2a/text:p[3]";
        self::assertEquals('Normal', $doc->getElementAttribute($element, 'text:style-name'));
        self::assertEquals('namedfont', $doc->getElementAttribute("$element/text:span", 'text:style-name'));
        $element = "$s2a/text:p[4]";
        self::assertEquals('P1_namedpar', $doc->getElementAttribute($element, 'text:style-name'));
        self::assertFalse($doc->elementExists("$element/text:span"));
        $element = "$s2a/text:p[5]";
        self::assertEquals('P2_namedpar', $doc->getElementAttribute($element, 'text:style-name'));
        self::assertEquals('namedfont', $doc->getElementAttribute("$element/text:span", 'text:style-name'));
    }
}
