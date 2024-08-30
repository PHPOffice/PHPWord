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

namespace PhpOffice\PhpWordTests\Shared;

use Exception;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\LineSpacingRule;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWordTests\AbstractWebServerEmbeddedTest;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Shared\Html.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Shared\Html
 */
class HtmlTest extends AbstractWebServerEmbeddedTest
{
    /**
     * Tear down after each test.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test unit conversion functions with various numbers.
     */
    public function testAddHtml(): void
    {
        $content = '';

        // Default
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        self::assertCount(0, $section->getElements());

        // Heading
        $styles = ['strong', 'em', 'sup', 'sub'];
        for ($level = 1; $level <= 6; ++$level) {
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
        self::assertCount(7, $section->getElements());

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
     * Test that html already in body element can be read.
     *
     * @ignore
     */
    public function testParseFullHtml(): void
    {
        $section = new Section(1);
        Html::addHtml($section, '<body><p>test paragraph1</p><p>test paragraph2</p></body>', true);

        self::assertCount(2, $section->getElements());
    }

    /**
     * Test HTML entities.
     */
    public function testParseHtmlEntities(): void
    {
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, 'text with entities &lt;my text&gt;');

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[1]/w:r/w:t'));
        self::assertEquals('text with entities <my text>', $doc->getElement('/w:document/w:body/w:p[1]/w:r/w:t')->nodeValue);
    }

    public function testParseStyle(): void
    {
        $html = '<style type="text/css">
        .pStyle {
          font-size:15px;
        }
        .tableStyle {
          width:100%;
          background-color:red;
        }
        </style>
        
        <p class="pStyle">Calculator</p>';
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, $html);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[2]'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[2]/w:r'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[2]/w:r/w:t'));
        self::assertEquals('Calculator', $doc->getElement('/w:document/w:body/w:p[2]/w:r/w:t')->nodeValue);
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[2]/w:r/w:rPr'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[2]/w:r/w:rPr/w:sz'));
        self::assertEquals('22.5', $doc->getElementAttribute('/w:document/w:body/w:p[2]/w:r/w:rPr/w:sz', 'w:val'));
    }

    public function testParseStyleTableClassName(): void
    {
        $html = '<style type="text/css">.pStyle { font-size:15px; }</style><table class="pStyle"><tr><td></td></tr></table>';
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, $html);

        self::assertInstanceOf(Table::class, $section->getElement(0));
        self::assertEquals('pStyle', $section->getElement(0)->getStyle()->getStyleName());
    }

    /**
     * Test underline.
     */
    public function testParseUnderline(): void
    {
        $html = '<u>test</u>';
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, $html);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:rPr/w:u'));
        self::assertEquals('single', $doc->getElementAttribute('/w:document/w:body/w:p/w:r/w:rPr/w:u', 'w:val'));
    }

    /**
     * Test text-decoration style.
     */
    public function testParseTextDecoration(): void
    {
        $html = '<span style="text-decoration: underline;">test</span>';
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, $html);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:rPr/w:u'));
        self::assertEquals('single', $doc->getElementAttribute('/w:document/w:body/w:p/w:r/w:rPr/w:u', 'w:val'));
    }

    /**
     * Test font-variant style.
     */
    public function testParseFontVariant(): void
    {
        $html = '<span style="font-variant: small-caps;">test</span>';
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, $html);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:rPr/w:smallCaps'));
        self::assertEquals('1', $doc->getElementAttribute('/w:document/w:body/w:p/w:r/w:rPr/w:smallCaps', 'w:val'));
    }

    /**
     * Test font.
     */
    public function testParseFont(): void
    {
        $html = '<font style="font-family: Arial;">test</font>';
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, $html);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:rPr'));
        //TODO check style
    }

    /**
     * Test line-height style.
     */
    public function testParseLineHeight(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, '<p style="line-height: 1.5;">test</p>');
        Html::addHtml($section, '<p style="line-height: 15pt;">test</p>');
        Html::addHtml($section, '<p style="line-height: 120%;">test</p>');
        Html::addHtml($section, '<p style="line-height: 0.17in;">test</p>');
        Html::addHtml($section, '<p style="line-height: normal;">test</p>');

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[1]/w:pPr/w:spacing'));
        self::assertEquals(Paragraph::LINE_HEIGHT * 1.5, $doc->getElementAttribute('/w:document/w:body/w:p[1]/w:pPr/w:spacing', 'w:line'));
        self::assertEquals(LineSpacingRule::AUTO, $doc->getElementAttribute('/w:document/w:body/w:p[1]/w:pPr/w:spacing', 'w:lineRule'));

        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[2]/w:pPr/w:spacing'));
        self::assertEquals(300, $doc->getElementAttribute('/w:document/w:body/w:p[2]/w:pPr/w:spacing', 'w:line'));
        self::assertEquals(LineSpacingRule::EXACT, $doc->getElementAttribute('/w:document/w:body/w:p[2]/w:pPr/w:spacing', 'w:lineRule'));

        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[3]/w:pPr/w:spacing'));
        self::assertEquals(Paragraph::LINE_HEIGHT * 1.2, $doc->getElementAttribute('/w:document/w:body/w:p[3]/w:pPr/w:spacing', 'w:line'));
        self::assertEquals(LineSpacingRule::AUTO, $doc->getElementAttribute('/w:document/w:body/w:p[3]/w:pPr/w:spacing', 'w:lineRule'));

        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[4]/w:pPr/w:spacing'));
        self::assertEquals(244.8, $doc->getElementAttribute('/w:document/w:body/w:p[4]/w:pPr/w:spacing', 'w:line'));
        self::assertEquals(LineSpacingRule::EXACT, $doc->getElementAttribute('/w:document/w:body/w:p[4]/w:pPr/w:spacing', 'w:lineRule'));

        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[5]/w:pPr/w:spacing'));
        self::assertEquals(Paragraph::LINE_HEIGHT, $doc->getElementAttribute('/w:document/w:body/w:p[5]/w:pPr/w:spacing', 'w:line'));
        self::assertEquals(LineSpacingRule::AUTO, $doc->getElementAttribute('/w:document/w:body/w:p[5]/w:pPr/w:spacing', 'w:lineRule'));
    }

    /**
     * Test text-indent style.
     */
    public function testParseTextIndent(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, '<p style="text-indent: 50px;">test</p>');

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:pPr/w:ind'));
        self::assertEquals(750, $doc->getElementAttribute('/w:document/w:body/w:p/w:pPr/w:ind', 'w:firstLine'));
    }

    /**
     * Test text-align style.
     */
    public function testParseTextAlign(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, '<p style="text-align: left;">test</p>');
        Html::addHtml($section, '<p style="text-align: right;">test</p>');
        Html::addHtml($section, '<p style="text-align: center;">test</p>');
        Html::addHtml($section, '<p style="text-align: justify;">test</p>');

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:pPr/w:jc'));
        self::assertEquals(Jc::START, $doc->getElementAttribute('/w:document/w:body/w:p[1]/w:pPr/w:jc', 'w:val'));
        self::assertEquals(Jc::END, $doc->getElementAttribute('/w:document/w:body/w:p[2]/w:pPr/w:jc', 'w:val'));
        self::assertEquals(Jc::CENTER, $doc->getElementAttribute('/w:document/w:body/w:p[3]/w:pPr/w:jc', 'w:val'));
        self::assertEquals(Jc::BOTH, $doc->getElementAttribute('/w:document/w:body/w:p[4]/w:pPr/w:jc', 'w:val'));
    }

    /**
     * Test font-size style.
     */
    public function testParseFontSize(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, '<span style="font-size: 10pt;">test</span>');
        Html::addHtml($section, '<span style="font-size: 10px;">test</span>');

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:rPr/w:sz'));
        self::assertEquals('20', $doc->getElementAttribute('/w:document/w:body/w:p[1]/w:r/w:rPr/w:sz', 'w:val'));
        self::assertEquals('15', $doc->getElementAttribute('/w:document/w:body/w:p[2]/w:r/w:rPr/w:sz', 'w:val'));
    }

    /**
     * Test direction style.
     */
    public function testParseTextDirection(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, '<span style="direction: rtl">test</span>');

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:rPr/w:rtl'));
    }

    /**
     * Test html lang.
     */
    public function testParseLang(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, '<span lang="fr-BE">test</span>');

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:rPr/w:lang'));
        self::assertEquals('fr-BE', $doc->getElementAttribute('/w:document/w:body/w:p/w:r/w:rPr/w:lang', 'w:val'));
    }

    /**
     * Test font-family style.
     */
    public function testParseFontFamily(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, '<span style="font-family: Arial">test</span>');
        Html::addHtml($section, '<span style="font-family: Times New Roman;">test</span>');

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:rPr/w:rFonts'));
        self::assertEquals('Arial', $doc->getElementAttribute('/w:document/w:body/w:p[1]/w:r/w:rPr/w:rFonts', 'w:ascii'));
        self::assertEquals('Times New Roman', $doc->getElementAttribute('/w:document/w:body/w:p[2]/w:r/w:rPr/w:rFonts', 'w:ascii'));
    }

    /**
     * Test parsing paragraph and span styles.
     */
    public function testParseParagraphAndSpanStyle(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, '<p style="text-align: center; margin-top: 15px; margin-bottom: 15px;"><span style="text-decoration: underline;">test</span></p>');

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:pPr/w:jc'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:pPr/w:spacing'));
        self::assertEquals(Jc::CENTER, $doc->getElementAttribute('/w:document/w:body/w:p[1]/w:pPr/w:jc', 'w:val'));
        self::assertEquals('single', $doc->getElementAttribute('/w:document/w:body/w:p[1]/w:r/w:rPr/w:u', 'w:val'));
    }

    /**
     * Test parsing paragraph with `page-break-after` style.
     */
    public function testParseParagraphWithPageBreak(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, '<p style="page-break-after:always;"></p>');

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:br'));
        self::assertEquals('page', $doc->getElementAttribute('/w:document/w:body/w:p/w:r/w:br', 'w:type'));
    }

    /**
     * Test parsing table.
     */
    public function testParseTable(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $html = '<table align="left" style="width: 50%; border: 12px #0000FF double">
                <thead>
                    <tr style="background-color: #FF0000; text-align: center; color: #FFFFFF; font-weight: bold">
                        <th style="width: 50pt"><p>header a</p></th>
                        <th style="width: 50; border-color: #00EE00; border-width: 3px"><span>header b</span></th>
                        <th style="border-color: #00AA00 #00BB00 #00CC00 #00DD00; border-width: 3px">header c</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td style="border-style: dotted;">1</td><td colspan="2">2</td></tr>
                    <tr><td>This is <b>bold</b> text</td><td>5</td><td><p>6</p></td></tr>
                </tbody>
            </table>';
        Html::addHtml($section, $html);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tr/w:tc'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tblPr/w:jc'));
        self::assertEquals(Jc::START, $doc->getElementAttribute('/w:document/w:body/w:tbl/w:tblPr/w:jc', 'w:val'));

        //check border colors
        self::assertEquals('00EE00', $doc->getElementAttribute('/w:document/w:body/w:tbl/w:tr[1]/w:tc[2]/w:tcPr/w:tcBorders/w:top', 'w:color'));
        self::assertEquals('00EE00', $doc->getElementAttribute('/w:document/w:body/w:tbl/w:tr[1]/w:tc[2]/w:tcPr/w:tcBorders/w:right', 'w:color'));
        self::assertEquals('00EE00', $doc->getElementAttribute('/w:document/w:body/w:tbl/w:tr[1]/w:tc[2]/w:tcPr/w:tcBorders/w:bottom', 'w:color'));
        self::assertEquals('00EE00', $doc->getElementAttribute('/w:document/w:body/w:tbl/w:tr[1]/w:tc[2]/w:tcPr/w:tcBorders/w:left', 'w:color'));

        self::assertEquals('00AA00', $doc->getElementAttribute('/w:document/w:body/w:tbl/w:tr[1]/w:tc[3]/w:tcPr/w:tcBorders/w:top', 'w:color'));
        self::assertEquals('00BB00', $doc->getElementAttribute('/w:document/w:body/w:tbl/w:tr[1]/w:tc[3]/w:tcPr/w:tcBorders/w:right', 'w:color'));
        self::assertEquals('00CC00', $doc->getElementAttribute('/w:document/w:body/w:tbl/w:tr[1]/w:tc[3]/w:tcPr/w:tcBorders/w:bottom', 'w:color'));
        self::assertEquals('00DD00', $doc->getElementAttribute('/w:document/w:body/w:tbl/w:tr[1]/w:tc[3]/w:tcPr/w:tcBorders/w:left', 'w:color'));

        //check borders are not propagated inside cells
        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tr[1]/w:tc[1]/w:p'));
        self::assertFalse($doc->elementExists('/w:document/w:body/w:tbl/w:tr[1]/w:tc[1]/w:p/w:pPr/w:pBdr'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tr[1]/w:tc[2]/w:p'));
        self::assertFalse($doc->elementExists('/w:document/w:body/w:tbl/w:tr[1]/w:tc[2]/w:p/w:pPr/w:pBdr'));
    }

    /**
     * Parse widths in tables and cells, which also allows for controlling column width.
     */
    public function testParseTableAndCellWidth(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection([
            'orientation' => \PhpOffice\PhpWord\Style\Section::ORIENTATION_LANDSCAPE,
        ]);

        // borders & backgrounds are here just for better visual comparison
        $html = <<<HTML
<table style="border: 1px #000000 solid; width: 100%;">
    <tr>
        <td style="width: 25%; border: 1px #000000 solid; text-align: center;">25%</td>
        <td>
            <table width="400" style="border: 1px #000000 solid; background-color: #EEEEEE;">
                <tr>
                    <th colspan="3" style="border: 1px #000000 solid;">400px</th>
                </tr>
                <tr>
                    <th>T2.R2.C1</th>
                    <th style="width: 50pt; border: 1px #FF0000 dashed; background-color: #FFFFFF">50pt</th>
                    <th>T2.R2.C3</th>
                </tr>
                <tr>
                    <th width="300" colspan="2" style="border: 1px #000000 solid;">300px</th>
                    <th style="border: 1px #000000 solid;">T2.R3.C3</th>
                </tr>
            </table>
        </td>
    </tr>
</table>
HTML;

        Html::addHtml($section, $html);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        // outer table grid
        $xpath = '/w:document/w:body/w:tbl/w:tblGrid/w:gridCol';
        self::assertTrue($doc->elementExists($xpath));
        self::assertEquals(25 * 50, $doc->getElement($xpath)->getAttribute('w:w'));
        self::assertEquals('dxa', $doc->getElement($xpath)->getAttribute('w:type'));

        // <td style="width: 25%; ...
        $xpath = '/w:document/w:body/w:tbl/w:tr/w:tc/w:tcPr/w:tcW';
        self::assertTrue($doc->elementExists($xpath));
        self::assertEquals(25 * 50, $doc->getElement($xpath)->getAttribute('w:w'));
        self::assertEquals('pct', $doc->getElement($xpath)->getAttribute('w:type'));

        // <table width="400" .. 400px = 6000 twips (400 / 96 * 1440)
        $xpath = '/w:document/w:body/w:tbl/w:tr/w:tc/w:tbl/w:tr/w:tc/w:tcPr/w:tcW';
        self::assertTrue($doc->elementExists($xpath));
        self::assertEquals(6000, $doc->getElement($xpath)->getAttribute('w:w'));
        self::assertEquals('dxa', $doc->getElement($xpath)->getAttribute('w:type'));

        // <th style="width: 50pt; .. 50pt = 750 twips (50 / 72 * 1440)
        $xpath = '/w:document/w:body/w:tbl/w:tr/w:tc/w:tbl/w:tr[2]/w:tc[2]/w:tcPr/w:tcW';
        self::assertTrue($doc->elementExists($xpath));
        self::assertEquals(1000, $doc->getElement($xpath)->getAttribute('w:w'));
        self::assertEquals('dxa', $doc->getElement($xpath)->getAttribute('w:type'));

        // <th width="300" .. 300px = 4500 twips (300 / 96 * 1440)
        $xpath = '/w:document/w:body/w:tbl/w:tr/w:tc/w:tbl/w:tr[3]/w:tc/w:tcPr/w:tcW';
        self::assertTrue($doc->elementExists($xpath));
        self::assertEquals(4500, $doc->getElement($xpath)->getAttribute('w:w'));
        self::assertEquals('dxa', $doc->getElement($xpath)->getAttribute('w:type'));
    }

    /**
     * Parse heights in rows, which also allows for controlling column height.
     */
    public function testParseTableRowHeight(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection([
            'orientation' => \PhpOffice\PhpWord\Style\Section::ORIENTATION_LANDSCAPE,
        ]);

        $html = <<<HTML
<table>
    <tr style="height: 100px;">
        <td>100px</td>
    </tr>
    <tr style="height: 200pt;">
        <td>200pt</td>
    </tr>
    <tr>
        <td>
            <table>
                <tr style="height: 300px;">
                    <td>300px</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
HTML;

        Html::addHtml($section, $html);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        // <tr style="height: 100; ... 100px = 1500 twips (100 / 96 * 1440)
        $xpath = '/w:document/w:body/w:tbl/w:tr/w:trPr/w:trHeight';
        self::assertTrue($doc->elementExists($xpath));
        self::assertEquals(1500, $doc->getElement($xpath)->getAttribute('w:val'));
        self::assertEquals('exact', $doc->getElement($xpath)->getAttribute('w:hRule'));

        // <tr style="height: 200pt; ... 200pt = 4000 twips (200 / 72 * 1440)
        $xpath = '/w:document/w:body/w:tbl/w:tr[2]/w:trPr/w:trHeight';
        self::assertTrue($doc->elementExists($xpath));
        self::assertEquals(4000, $doc->getElement($xpath)->getAttribute('w:val'));
        self::assertEquals('exact', $doc->getElement($xpath)->getAttribute('w:hRule'));

        // <tr style="width: 300; .. 300px = 4500 twips (300 / 72 * 1440)
        $xpath = '/w:document/w:body/w:tbl/w:tr[3]/w:tc/w:tbl/w:tr/w:trPr/w:trHeight';
        self::assertTrue($doc->elementExists($xpath));
        self::assertEquals(4500, $doc->getElement($xpath)->getAttribute('w:val'));
        self::assertEquals('exact', $doc->getElement($xpath)->getAttribute('w:hRule'));
    }

    /**
     * Test parsing table (attribute border).
     */
    public function testParseTableAttributeBorder(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $html = '<table border="10">
                <thead>
                    <tr>
                        <th>Header</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>Cell 1</td></tr>
                    <tr><td>Cell 2</td></tr>
                </tbody>
            </table>';
        Html::addHtml($section, $html);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tblPr'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tblPr/w:tblBorders'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tblPr/w:tblBorders/w:top'));
        // 10 pixels = 150 twips
        self::assertEquals(150, $doc->getElementAttribute('/w:document/w:body/w:tbl/w:tblPr/w:tblBorders/w:top', 'w:sz'));
    }

    /**
     * Test parsing background color for table rows and table cellspacing.
     */
    public function testParseTableCellspacingRowBgColor(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection([
            'orientation' => \PhpOffice\PhpWord\Style\Section::ORIENTATION_LANDSCAPE,
        ]);

        // borders & backgrounds are here just for better visual comparison
        $html = <<<HTML
<table cellspacing="3" bgColor="lightgreen" width="50%" align="center">
    <tr>
        <td>A</td>
        <td>B</td>
    </tr>
    <tr bgcolor="#FF0000">
        <td>C</td>
        <td>D</td>
    </tr>
</table>
HTML;

        Html::addHtml($section, $html);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $xpath = '/w:document/w:body/w:tbl/w:tblPr/w:tblCellSpacing';
        self::assertTrue($doc->elementExists($xpath));
        self::assertEquals(3 * 15, $doc->getElement($xpath)->getAttribute('w:w'));
        self::assertEquals('dxa', $doc->getElement($xpath)->getAttribute('w:type'));

        $xpath = '/w:document/w:body/w:tbl/w:tr[1]/w:tc[1]/w:tcPr/w:shd';
        self::assertTrue($doc->elementExists($xpath));
        self::assertEquals('lightgreen', $doc->getElement($xpath)->getAttribute('w:fill'));

        $xpath = '/w:document/w:body/w:tbl/w:tr[2]/w:tc[1]/w:tcPr/w:shd';
        self::assertTrue($doc->elementExists($xpath));
        self::assertEquals('FF0000', $doc->getElement($xpath)->getAttribute('w:fill'));
    }

    /**
     * Test parsing background color for table rows and table cellspacing.
     */
    public function testParseTableStyleAttributeInlineStyle(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection([
            'orientation' => \PhpOffice\PhpWord\Style\Section::ORIENTATION_LANDSCAPE,
        ]);

        $html = '<table style="background-color:red;width:100%;" bgColor="lightgreen" width="50%">
            <tr>
                <td>A</td>
            </tr>
        </table>';

        Html::addHtml($section, $html);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $xpath = '/w:document/w:body/w:tbl/w:tblPr/w:tblW';
        self::assertTrue($doc->elementExists($xpath));
        self::assertEquals(100 * 50, $doc->getElement($xpath)->getAttribute('w:w'));
        self::assertEquals('pct', $doc->getElement($xpath)->getAttribute('w:type'));

        $xpath = '/w:document/w:body/w:tbl/w:tr[1]/w:tc[1]/w:tcPr/w:shd';
        self::assertTrue($doc->elementExists($xpath));
        self::assertEquals('red', $doc->getElement($xpath)->getAttribute('w:fill'));
    }

    /**
     * Tests parsing of ul/li.
     */
    public function testParseList(): void
    {
        $phpWord = new PhpWord();
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
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:pPr/w:numPr/w:numId'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:t'));
        self::assertEquals('list item1', $doc->getElement('/w:document/w:body/w:p[1]/w:r/w:t')->nodeValue);
        self::assertEquals('list item2', $doc->getElement('/w:document/w:body/w:p[2]/w:r/w:t')->nodeValue);
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:rPr/w:b'));
    }

    /**
     * Tests parsing of ul/li.
     */
    public function testOrderedListNumbering(): void
    {
        $phpWord = new PhpWord();
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

        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:pPr/w:numPr/w:numId'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:t'));

        self::assertEquals('List 1 item 1', $doc->getElement('/w:document/w:body/w:p[1]/w:r/w:t')->nodeValue);
        self::assertEquals('List 2 item 1', $doc->getElement('/w:document/w:body/w:p[4]/w:r/w:t')->nodeValue);

        $firstListnumId = $doc->getElementAttribute('/w:document/w:body/w:p[1]/w:pPr/w:numPr/w:numId', 'w:val');
        $secondListnumId = $doc->getElementAttribute('/w:document/w:body/w:p[4]/w:pPr/w:numPr/w:numId', 'w:val');

        self::assertNotEquals($firstListnumId, $secondListnumId);
    }

    /**
     * Tests parsing of nested ul/li.
     */
    public function testOrderedNestedListNumbering(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $html = '<ol>
                <li>List 1 item 1</li>
                <li>List 1 item 2</li>
            </ol>
            <p>Some Text</p>
            <ol>
                <li>List 2 item 1</li>
                <li>
                    <ol>
                        <li>sub list 1</li>
                        <li>sub list 2</li>
                    </ol>
                </li>
            </ol>';
        Html::addHtml($section, $html, false, false);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:pPr/w:numPr/w:numId'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:t'));

        self::assertEquals('List 1 item 1', $doc->getElement('/w:document/w:body/w:p[1]/w:r/w:t')->nodeValue);
        self::assertEquals('List 2 item 1', $doc->getElement('/w:document/w:body/w:p[4]/w:r/w:t')->nodeValue);

        $firstListnumId = $doc->getElementAttribute('/w:document/w:body/w:p[1]/w:pPr/w:numPr/w:numId', 'w:val');
        $secondListnumId = $doc->getElementAttribute('/w:document/w:body/w:p[4]/w:pPr/w:numPr/w:numId', 'w:val');

        self::assertNotEquals($firstListnumId, $secondListnumId);
    }

    /**
     * Tests parsing of ul/li.
     */
    public function testParseListWithFormat(): void
    {
        $phpWord = new PhpWord();
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
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:pPr/w:numPr/w:numId'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:t'));
        self::assertEquals('list item2', $doc->getElement('/w:document/w:body/w:p[2]/w:r/w:t')->nodeValue);
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[1]/w:r[3]/w:rPr/w:b'));
        self::assertEquals('bold', $doc->getElement('/w:document/w:body/w:p[1]/w:r[3]/w:t')->nodeValue);
    }

    /**
     * Tests parsing of br.
     */
    public function testParseLineBreak(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $html = '<p>This is some text<br/>with a linebreak.</p>';
        Html::addHtml($section, $html);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:br'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:t'));
        self::assertEquals('This is some text', $doc->getElement('/w:document/w:body/w:p/w:r[1]/w:t')->nodeValue);
        self::assertEquals('with a linebreak.', $doc->getElement('/w:document/w:body/w:p/w:r[2]/w:t')->nodeValue);
    }

    /**
     * Test parsing of img.
     */
    public function testParseImage(): void
    {
        $src = __DIR__ . '/../_files/images/firefox.png';

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $html = '<p><img src="' . $src . '" width="150" height="200" style="float: right;"/><img src="' . $src . '" style="float: left;"/></p>';
        Html::addHtml($section, $html);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $baseXpath = '/w:document/w:body/w:p/w:r';
        self::assertTrue($doc->elementExists($baseXpath . '/w:pict/v:shape'));
        self::assertStringMatchesFormat('%Swidth:150px%S', $doc->getElementAttribute($baseXpath . '[1]/w:pict/v:shape', 'style'));
        self::assertStringMatchesFormat('%Sheight:200px%S', $doc->getElementAttribute($baseXpath . '[1]/w:pict/v:shape', 'style'));
        self::assertStringMatchesFormat('%Smso-position-horizontal:right%S', $doc->getElementAttribute($baseXpath . '[1]/w:pict/v:shape', 'style'));
        self::assertStringMatchesFormat('%Smso-position-horizontal:left%S', $doc->getElementAttribute($baseXpath . '[2]/w:pict/v:shape', 'style'));
    }

    /**
     * Test parsing of img.
     */
    public function testParseImageSizeInPixels(): void
    {
        $src = __DIR__ . '/../_files/images/firefox.png';

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $html = '<p><img src="' . $src . '" width="150px" height="200px" /></p>';
        Html::addHtml($section, $html);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $baseXpath = '/w:document/w:body/w:p/w:r';
        self::assertTrue($doc->elementExists($baseXpath . '/w:pict/v:shape'));
        self::assertStringMatchesFormat('%Swidth:150px%S', $doc->getElementAttribute($baseXpath . '[1]/w:pict/v:shape', 'style'));
        self::assertStringMatchesFormat('%Sheight:200px%S', $doc->getElementAttribute($baseXpath . '[1]/w:pict/v:shape', 'style'));
    }

    /**
     * Test parsing of img.
     */
    public function testParseImageSizeInPoints(): void
    {
        $src = __DIR__ . '/../_files/images/firefox.png';

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $html = '<p><img src="' . $src . '" width="150pt" height="200pt" /></p>';
        Html::addHtml($section, $html);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $baseXpath = '/w:document/w:body/w:p/w:r';
        self::assertTrue($doc->elementExists($baseXpath . '/w:pict/v:shape'));
        self::assertStringMatchesFormat('%Swidth:200px%S', $doc->getElementAttribute($baseXpath . '[1]/w:pict/v:shape', 'style'));
        self::assertStringMatchesFormat('%Sheight:266.66666666667%S', $doc->getElementAttribute($baseXpath . '[1]/w:pict/v:shape', 'style'));
    }

    /**
     * Test parsing of img.
     */
    public function testParseImageSizeWithoutUnits(): void
    {
        $src = __DIR__ . '/../_files/images/firefox.png';

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $html = '<p><img src="' . $src . '" width="150" height="200" /></p>';
        Html::addHtml($section, $html);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $baseXpath = '/w:document/w:body/w:p/w:r';
        self::assertTrue($doc->elementExists($baseXpath . '/w:pict/v:shape'));
        self::assertStringMatchesFormat('%Swidth:150px%S', $doc->getElementAttribute($baseXpath . '[1]/w:pict/v:shape', 'style'));
        self::assertStringMatchesFormat('%Sheight:200px%S', $doc->getElementAttribute($baseXpath . '[1]/w:pict/v:shape', 'style'));
    }

    /**
     * Test parsing of remote img.
     */
    public function testParseRemoteImage(): void
    {
        $src = self::getRemoteImageUrl();

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $html = '<p><img src="' . $src . '" width="150" height="200" style="float: right;"/><img src="' . $src . '" style="float: left;"/></p>';
        Html::addHtml($section, $html);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $baseXpath = '/w:document/w:body/w:p/w:r';
        self::assertTrue($doc->elementExists($baseXpath . '/w:pict/v:shape'));
    }

    /**
     * Test parsing of remote img without extension.
     */
    public function testParseRemoteImageWithoutExtension(): void
    {
        $src = self::getRemoteImageUrlWithoutExtension();

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $html = '<p><img src="' . $src . '" width="150" height="200" style="float: right;"/><img src="' . $src . '" style="float: left;"/></p>';
        Html::addHtml($section, $html);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $baseXpath = '/w:document/w:body/w:p/w:r';
        self::assertTrue($doc->elementExists($baseXpath . '/w:pict/v:shape'));
    }

    /**
     * Test parsing embedded image.
     */
    public function testParseEmbeddedImage(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $html = '<p><img src="data:image/jpeg;base64,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" name="Imagen 12" align="bottom" width="208" height="183" border="0"/></p>';
        Html::addHtml($section, $html);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $baseXpath = '/w:document/w:body/w:p/w:r';
        self::assertTrue($doc->elementExists($baseXpath . '/w:pict/v:shape'));
    }

    /**
     * Test parsing of remote img that can be found locally.
     */
    public function testParseRemoteLocalImage(): void
    {
        $src = 'https://fakedomain.io/images/firefox.png';
        $localPath = __DIR__ . '/../_files/images/';
        $options = [
            'IMG_SRC_SEARCH' => 'https://fakedomain.io/images/',
            'IMG_SRC_REPLACE' => $localPath,
        ];

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $html = '<p><img src="' . $src . '" width="150" height="200" style="float: right;"/></p>';
        Html::addHtml($section, $html, false, true, $options);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $baseXpath = '/w:document/w:body/w:p/w:r';
        self::assertTrue($doc->elementExists($baseXpath . '/w:pict/v:shape'));
    }

    /**
     * Test parsing of remote img that can be found locally.
     */
    public function testCouldNotLoadImage(): void
    {
        $this->expectException(Exception::class);
        $src = 'https://fakedomain.io/images/firefox.png';

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $html = '<p><img src="' . $src . '" width="150" height="200" style="float: right;"/></p>';
        Html::addHtml($section, $html, false, true);
    }

    public function testParseLink(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $html = '<p><a href="https://phpoffice.github.io/PHPWord/" style="text-decoration: underline">link text</a></p>';
        Html::addHtml($section, $html);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:hyperlink'));
        self::assertEquals('link text', $doc->getElement('/w:document/w:body/w:p/w:hyperlink/w:r/w:t')->nodeValue);
        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:hyperlink/w:r/w:rPr/w:u'));
        self::assertEquals('single', $doc->getElementAttribute('/w:document/w:body/w:p/w:hyperlink/w:r/w:rPr/w:u', 'w:val'));
    }

    public function testParseLink2(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addBookmark('bookmark');
        $html = '<p><a href="#bookmark">internal link text</a></p>';
        Html::addHtml($section, $html);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:hyperlink'));
        self::assertTrue($doc->getElement('/w:document/w:body/w:p/w:hyperlink')->hasAttribute('w:anchor'));
        self::assertEquals('bookmark', $doc->getElement('/w:document/w:body/w:p/w:hyperlink')->getAttribute('w:anchor'));
    }

    public function testParseLinkAllowsAbsenceOfHref(): void
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $html = '<p><a>text of href-less link</a></p>';
        Html::addHtml($section, $html);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:hyperlink'));
        self::assertEquals('text of href-less link', $doc->getElement('/w:document/w:body/w:p/w:hyperlink/w:r/w:t')->nodeValue);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $html = '<p><a href="">text of empty-href link</a></p>';
        Html::addHtml($section, $html);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:hyperlink'));
        self::assertEquals('text of empty-href link', $doc->getElement('/w:document/w:body/w:p/w:hyperlink/w:r/w:t')->nodeValue);
    }

    public function testParseMalformedStyleIsIgnored(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $html = '<p style="">text</p>';
        Html::addHtml($section, $html);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        self::assertFalse($doc->elementExists('/w:document/w:body/w:p[1]/w:pPr/w:jc'));
    }

    /**
     * Tests parsing hidden text.
     */
    public function testParseHiddenText(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $html = '<p style="display: hidden">This is some hidden text.</p>';
        Html::addHtml($section, $html);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:rPr/w:vanish'));
    }

    /**
     * Tests parsing letter spacing.
     */
    public function testParseLetterSpacing(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $html = '<p style="letter-spacing: 150px">This is some text with letter spacing.</p>';
        Html::addHtml($section, $html);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        self::assertTrue($doc->elementExists('/w:document/w:body/w:p/w:r/w:rPr/w:spacing'));
        self::assertEquals(150 * 15, $doc->getElement('/w:document/w:body/w:p/w:r/w:rPr/w:spacing')->getAttribute('w:val'));
    }

    /**
     * Tests checkbox input field.
     */
    public function testInputCheckbox(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $html = '<input type="checkbox" checked="true" /><input type="checkbox" />';
        Html::addHtml($section, $html);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[1]/w:r/w:fldChar/w:ffData/w:checkBox'));
        self::assertEquals(1, $doc->getElement('/w:document/w:body/w:p[1]/w:r/w:fldChar/w:ffData/w:checkBox/w:checked')->getAttribute('w:val'));

        self::assertTrue($doc->elementExists('/w:document/w:body/w:p[2]/w:r/w:fldChar/w:ffData/w:checkBox'));
        self::assertEquals(0, $doc->getElement('/w:document/w:body/w:p[2]/w:r/w:fldChar/w:ffData/w:checkBox/w:checked')->getAttribute('w:val'));
    }

    /**
     * Parse horizontal rule.
     */
    public function testParseHorizontalRule(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // borders & backgrounds are here just for better visual comparison
        $html = <<<HTML
<p>Simple default rule:</p>
<hr/>
<p>Custom style rule:</p>
<hr style="margin-top: 30px; margin-bottom: 0; border-bottom: 5px lightblue solid;" />
<p>END</p>
HTML;

        Html::addHtml($section, $html);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        // default rule
        $xpath = '/w:document/w:body/w:p[2]/w:pPr/w:pBdr/w:bottom';
        self::assertTrue($doc->elementExists($xpath));
        self::assertEquals('single', $doc->getElement($xpath)->getAttribute('w:val')); // solid
        self::assertEquals('1', $doc->getElement($xpath)->getAttribute('w:sz')); // 1 twip
        self::assertEquals('000000', $doc->getElement($xpath)->getAttribute('w:color')); // black

        // custom style rule
        $xpath = '/w:document/w:body/w:p[4]/w:pPr/w:pBdr/w:bottom';
        self::assertTrue($doc->elementExists($xpath));
        self::assertEquals('single', $doc->getElement($xpath)->getAttribute('w:val'));
        self::assertEquals((int) (5 * 15 / 2), $doc->getElement($xpath)->getAttribute('w:sz'));
        self::assertEquals('lightblue', $doc->getElement($xpath)->getAttribute('w:color'));

        $xpath = '/w:document/w:body/w:p[4]/w:pPr/w:spacing';
        self::assertTrue($doc->elementExists($xpath));
        self::assertEquals(450, $doc->getElement($xpath)->getAttribute('w:before'));
        self::assertEquals(0, $doc->getElement($xpath)->getAttribute('w:after'));
        self::assertEquals(240, $doc->getElement($xpath)->getAttribute('w:line'));
    }

    /**
     * Parse ordered list start & numbering style.
     */
    public function testParseOrderedList(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // borders & backgrounds are here just for better visual comparison
        $html = <<<HTML
<ol>
    <li>standard ordered list line 1</li>
    <li>standard ordered list line 2</li>
</ol>

<ol start="5" type="A">
    <li>ordered list alphabetical, <span style="background-color: #EEEEEE; color: #FF0000;">line 5 => E</span></li>
    <li>ordered list alphabetical, <span style="background-color: #EEEEEE; color: #FF0000;">line 6 => F</span></li>
</ol>

<ol start="3" type="i">
    <li>ordered list roman lower, line <b>3 => iii</b></li>
    <li>ordered list roman lower, line <b>4 => iv</b></li>
</ol>

HTML;

        Html::addHtml($section, $html);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        // compare numbering file
        $xmlFile = 'word/numbering.xml';

        // default - decimal start = 1
        $xpath = '/w:numbering/w:abstractNum[1]/w:lvl[1]/w:start';
        self::assertTrue($doc->elementExists($xpath, $xmlFile));
        self::assertEquals('1', $doc->getElement($xpath, $xmlFile)->getAttribute('w:val'));

        $xpath = '/w:numbering/w:abstractNum[1]/w:lvl[1]/w:numFmt';
        self::assertTrue($doc->elementExists($xpath, $xmlFile));
        self::assertEquals('decimal', $doc->getElement($xpath, $xmlFile)->getAttribute('w:val'));

        // second list - start = 5, type A = upperLetter
        $xpath = '/w:numbering/w:abstractNum[2]/w:lvl[1]/w:start';
        self::assertTrue($doc->elementExists($xpath, $xmlFile));
        self::assertEquals('5', $doc->getElement($xpath, $xmlFile)->getAttribute('w:val'));

        $xpath = '/w:numbering/w:abstractNum[2]/w:lvl[1]/w:numFmt';
        self::assertTrue($doc->elementExists($xpath, $xmlFile));
        self::assertEquals('upperLetter', $doc->getElement($xpath, $xmlFile)->getAttribute('w:val'));

        // third list - start = 3, type i = lowerRoman
        $xpath = '/w:numbering/w:abstractNum[3]/w:lvl[1]/w:start';
        self::assertTrue($doc->elementExists($xpath, $xmlFile));
        self::assertEquals('3', $doc->getElement($xpath, $xmlFile)->getAttribute('w:val'));

        $xpath = '/w:numbering/w:abstractNum[3]/w:lvl[1]/w:numFmt';
        self::assertTrue($doc->elementExists($xpath, $xmlFile));
        self::assertEquals('lowerRoman', $doc->getElement($xpath, $xmlFile)->getAttribute('w:val'));
    }

    /**
     * Parse ordered list start & numbering style.
     */
    public function testParseVerticalAlign(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // borders & backgrounds are here just for better visual comparison
        $html = <<<HTML
<table width="100%">
    <tr>
        <td width="20%" style="border: 1px #666666 solid;">default</td>
        <td width="20%" style="vertical-align: top; border: 1px #666666 solid;">top</td>
        <td width="20%" style="vertical-align: middle; border: 1px #666666 solid;">middle</td>
        <td width="20%" valign="bottom" style="border: 1px #666666 solid;">bottom</td>
        <td bgcolor="#DDDDDD"><br/><br/><br/><br/><br/><br/><br/></td>
    </tr>
</table>
HTML;

        Html::addHtml($section, $html);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $xpath = '/w:document/w:body/w:tbl/w:tr/w:tc[1]/w:tcPr/w:vAlign';
        self::assertFalse($doc->elementExists($xpath));

        $xpath = '/w:document/w:body/w:tbl/w:tr/w:tc[2]/w:tcPr/w:vAlign';
        self::assertTrue($doc->elementExists($xpath));
        self::assertEquals('top', $doc->getElement($xpath)->getAttribute('w:val'));

        $xpath = '/w:document/w:body/w:tbl/w:tr/w:tc[3]/w:tcPr/w:vAlign';
        self::assertTrue($doc->elementExists($xpath));
        self::assertEquals('center', $doc->getElement($xpath)->getAttribute('w:val'));

        $xpath = '/w:document/w:body/w:tbl/w:tr/w:tc[4]/w:tcPr/w:vAlign';
        self::assertTrue($doc->elementExists($xpath));
        self::assertEquals('bottom', $doc->getElement($xpath)->getAttribute('w:val'));
    }

    /**
     * Fix bug - don't decode double quotes inside double quoted string.
     */
    public function testDontDecodeAlreadyEncodedDoubleQuotes(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // borders & backgrounds are here just for better visual comparison
        $html = <<<HTML
<div style="font-family: Arial, &quot;Helvetice Neue&quot;">This would crash if inline quotes also decoded at loading XML into DOMDocument!</div>
HTML;

        Html::addHtml($section, $html);
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        self::assertIsObject($doc);
    }
}
