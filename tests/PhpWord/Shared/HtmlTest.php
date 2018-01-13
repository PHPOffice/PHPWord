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
 * @copyright   2010-2017 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Shared;

use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Shared\Html
 * @coversDefaultClass \PhpOffice\PhpWord\Shared\Html
 */
class HtmlTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test unit conversion functions with various numbers
     */
    public function testAddHtml()
    {
        $content = '';

        // Default
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $this->assertCount(0, $section->getElements());

        // Heading
        $styles = array('strong', 'em', 'sup', 'sub');
        for ($level = 1; $level <= 6; $level++) {
            $content .= "<h{$level}>Heading {$level}</h{$level}>";
        }

        // Styles
        $content .= '<p style="text-decoration: underline; text-decoration: line-through; '
                  . 'text-align: center; color: #999; background-color: #000; font-weight: bold; font-style: italic;">';
        foreach ($styles as $style) {
            $content .= "<{$style}>{$style}</{$style}>";
        }
        $content .= '</p>';

        // Add HTML
        Html::addHtml($section, $content);
        $this->assertCount(7, $section->getElements());

        // Other parts
        $section = $phpWord->addSection();
        $content = '';
        $content .= '<table><tr><th>Header</th><td>Content</td></tr></table>';
        $content .= '<ul><li>Bullet</li><ul><li>Bullet</li></ul></ul>';
        $content .= '<ol><li>Bullet</li></ol>';
        $content .= "'Single Quoted Text'";
        $content .= '"Double Quoted Text"';
        $content .= '& Ampersand';
        $content .= '&lt;&gt;&ldquo;&lsquo;&rsquo;&laquo;&raquo;&lsaquo;&rsaquo;';
        $content .= '&amp;&bull;&deg;&hellip;&trade;&copy;&reg;&mdash;';
        $content .= '&ndash;&nbsp;&emsp;&ensp;&sup2;&sup3;&frac14;&frac12;&frac34;';
        Html::addHtml($section, $content);
    }

    /**
     * Test that html already in body element can be read
     * @ignore
     */
    public function testParseFullHtml()
    {
        $section = new Section(1);
        Html::addHtml($section, '<body><p>test paragraph1</p><p>test paragraph2</p></body>', true);

        $this->assertCount(2, $section->getElements());
    }

    /**
     * Test underline
     */
    public function testParseUnderline()
    {
        $html = '<u>test</u>';
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, $html);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:rPr/w:u'));
        $this->assertEquals('single', $doc->getElementAttribute('/w:document/w:body/w:p/w:r/w:rPr/w:u', 'w:val'));
    }

    /**
     * Test text-decoration style
     */
    public function testParseTextDecoration()
    {
        $html = '<span style="text-decoration: underline;">test</span>';
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, $html);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:rPr/w:u'));
        $this->assertEquals('single', $doc->getElementAttribute('/w:document/w:body/w:p/w:r/w:rPr/w:u', 'w:val'));
    }

    /**
     * Test text-align style
     */
    public function testParseTextAlign()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, '<p style="text-align: left;">test</p>');
        Html::addHtml($section, '<p style="text-align: right;">test</p>');
        Html::addHtml($section, '<p style="text-align: center;">test</p>');
        Html::addHtml($section, '<p style="text-align: justify;">test</p>');

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p/w:pPr/w:jc'));
        $this->assertEquals(Jc::START, $doc->getElementAttribute('/w:document/w:body/w:p[1]/w:pPr/w:jc', 'w:val'));
        $this->assertEquals(Jc::END, $doc->getElementAttribute('/w:document/w:body/w:p[2]/w:pPr/w:jc', 'w:val'));
        $this->assertEquals(Jc::CENTER, $doc->getElementAttribute('/w:document/w:body/w:p[3]/w:pPr/w:jc', 'w:val'));
        $this->assertEquals(Jc::BOTH, $doc->getElementAttribute('/w:document/w:body/w:p[4]/w:pPr/w:jc', 'w:val'));
    }

    /**
     * Test font-size style
     */
    public function testParseFontSize()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, '<span style="font-size: 10pt;">test</span>');
        Html::addHtml($section, '<span style="font-size: 10px;">test</span>');

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:rPr/w:sz'));
        $this->assertEquals('20', $doc->getElementAttribute('/w:document/w:body/w:p[1]/w:r/w:rPr/w:sz', 'w:val'));
        $this->assertEquals('15', $doc->getElementAttribute('/w:document/w:body/w:p[2]/w:r/w:rPr/w:sz', 'w:val'));
    }

    /**
     * Test font-family style
     */
    public function testParseFontFamily()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, '<span style="font-family: Arial">test</span>');
        Html::addHtml($section, '<span style="font-family: Times New Roman;">test</span>');

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:rPr/w:rFonts'));
        $this->assertEquals('Arial', $doc->getElementAttribute('/w:document/w:body/w:p[1]/w:r/w:rPr/w:rFonts', 'w:ascii'));
        $this->assertEquals('Times New Roman', $doc->getElementAttribute('/w:document/w:body/w:p[2]/w:r/w:rPr/w:rFonts', 'w:ascii'));
    }

    /**
     * Test parsing paragraph and span styles
     */
    public function testParseParagraphAndSpanStyle()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, '<p style="text-align: center; margin-top: 15px; margin-bottom: 15px;"><span style="text-decoration: underline;">test</span></p>');

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p/w:pPr/w:jc'));
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p/w:pPr/w:spacing'));
        $this->assertEquals(Jc::CENTER, $doc->getElementAttribute('/w:document/w:body/w:p[1]/w:pPr/w:jc', 'w:val'));
        $this->assertEquals('single', $doc->getElementAttribute('/w:document/w:body/w:p[1]/w:r/w:rPr/w:u', 'w:val'));
    }

    /**
     * Test parsing table
     */
    public function testParseTable()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $html = '<table align="left" style="width: 50%; border: 6px #0000FF solid;">
                <thead>
                    <tr style="background-color: #FF0000; text-align: center; color: #FFFFFF; font-weight: bold; ">
                        <th style="width: 50pt">header a</th>
                        <th style="width: 50">header b</th>
                        <th style="border-color: #00FF00; border-width: 3px">header c</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td style="border-style: dotted;">1</td><td colspan="2">2</td></tr>
                    <tr><td>4</td><td>5</td><td>6</td></tr>
                </tbody>
            </table>';
        Html::addHtml($section, $html);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:tbl'));
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tr/w:tc'));
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tblPr/w:jc'));
        $this->assertEquals(Jc::START, $doc->getElementAttribute('/w:document/w:body/w:tbl/w:tblPr/w:jc', 'w:val'));
    }

    /**
     * Tests parsing of ul/li
     */
    public function testParseList()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $html = '<ul>
                <li>
                    <span style="font-family: arial,helvetica,sans-serif;">
                        <span style="font-size: 12px;">list item1</span>
                    </span>
                </li>
                <li>
                    <span style="font-family: arial,helvetica,sans-serif;">
                        <span style="font-size: 10px; font-weight: bold;">list item2</span>
                    </span>
                </li>
            </ul>';
        Html::addHtml($section, $html, false, false);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p/w:pPr/w:numPr/w:numId'));
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:t'));
        $this->assertEquals('list item1', $doc->getElement('/w:document/w:body/w:p[1]/w:r/w:t')->nodeValue);
        $this->assertEquals('list item2', $doc->getElement('/w:document/w:body/w:p[2]/w:r/w:t')->nodeValue);
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:rPr/w:b'));
    }

    /**
     * Tests parsing of ul/li
     */
    public function tesOrderedListNumbering()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $html = '<ol>
                <li>List 1 item 1</li>
                <li>List 1 item 2</li>
            </ol>
            <p>Some Text</p>
            <ol>
                <li>List 2 item 1</li>
                <li>List 2 item 2</li>
            </ol>';
        Html::addHtml($section, $html, false, false);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        echo $doc->printXml();
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p/w:pPr/w:numPr/w:numId'));
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:t'));

        $this->assertEquals('List 1 item 1', $doc->getElement('/w:document/w:body/w:p[1]/w:r/w:t')->nodeValue);
        $this->assertEquals('List 2 item 1', $doc->getElement('/w:document/w:body/w:p[4]/w:r/w:t')->nodeValue);

        $firstListnumId = $doc->getElementAttribute('/w:document/w:body/w:p[1]/w:pPr/w:numPr/w:numId', 'w:val');
        $secondListnumId = $doc->getElementAttribute('/w:document/w:body/w:p[4]/w:pPr/w:numPr/w:numId', 'w:val');

        $this->assertNotEquals($firstListnumId, $secondListnumId);
    }

    /**
     * Tests parsing of ul/li
     */
    public function testParseListWithFormat()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $html = preg_replace('/\s+/', ' ', '<ul>
                <li>Some text before
                    <span style="font-family: arial,helvetica,sans-serif;">
                        <span style="font-size: 12px;">list item1 <b>bold</b> with text after bold</span>
                    </span>
                    and some after
                </li>
                <li>
                    <span style="font-family: arial,helvetica,sans-serif;">
                        <span style="font-size: 12px;">list item2</span>
                    </span>
                </li>
            </ul>');
        Html::addHtml($section, $html, false, false);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p/w:pPr/w:numPr/w:numId'));
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:t'));
        $this->assertEquals('list item2', $doc->getElement('/w:document/w:body/w:p[2]/w:r/w:t')->nodeValue);
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p[1]/w:r[3]/w:rPr/w:b'));
        $this->assertEquals('bold', $doc->getElement('/w:document/w:body/w:p[1]/w:r[3]/w:t')->nodeValue);
    }

    /**
     * Tests parsing of br
     */
    public function testParseLineBreak()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $html = '<p>This is some text<br/>with a linebreak.</p>';
        Html::addHtml($section, $html);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p/w:br'));
        $this->assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:t'));
        $this->assertEquals('This is some text', $doc->getElement('/w:document/w:body/w:p/w:r[1]/w:t')->nodeValue);
        $this->assertEquals('with a linebreak.', $doc->getElement('/w:document/w:body/w:p/w:r[2]/w:t')->nodeValue);
    }

    /**
     * Test parsing of img
     */
    public function testParseImage()
    {
        $src = __DIR__ . '/../_files/images/firefox.png';

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $html = '<p><img src="' . $src . '" width="150" height="200" style="float: right;"/><img src="' . $src . '" style="float: left;"/></p>';
        Html::addHtml($section, $html);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $baseXpath = '/w:document/w:body/w:p/w:r';
        $this->assertTrue($doc->elementExists($baseXpath . '/w:pict/v:shape'));
        $this->assertStringMatchesFormat('%Swidth:150pt%S', $doc->getElementAttribute($baseXpath . '[1]/w:pict/v:shape', 'style'));
        $this->assertStringMatchesFormat('%Sheight:200pt%S', $doc->getElementAttribute($baseXpath . '[1]/w:pict/v:shape', 'style'));
        $this->assertStringMatchesFormat('%Smso-position-horizontal:right%S', $doc->getElementAttribute($baseXpath . '[1]/w:pict/v:shape', 'style'));
        $this->assertStringMatchesFormat('%Smso-position-horizontal:left%S', $doc->getElementAttribute($baseXpath . '[2]/w:pict/v:shape', 'style'));
    }
}
