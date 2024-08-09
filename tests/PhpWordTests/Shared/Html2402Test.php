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

use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\Style\Table as TableStyle;
use PhpOffice\PhpWord\Writer\HTML as HtmlWriter;

/**
 * Test class for PhpOffice\PhpWord\Shared\Html.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Shared\Html
 */
class Html2402Test extends \PHPUnit\Framework\TestCase
{
    public function testParseTableBorder0(): void
    {
        $html = <<<HTML
<table align="center" border="0" style="width: 50%;">
                <thead>
                    <tr>
                        <th>header a</th>
                        <th>header          b</th>
                        <th><span style="background-color: #00FF00;">header c</span></th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td style="border-style: dotted; border-color: #F00">1</td><td colspan="2">2</td></tr>
                    <tr><td>This is <b>bold</b> text</td><td></td><td>6</td></tr>
                </tbody>
            </table>
HTML;
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, $html, false, false);
        $elements = $section->getElements();
        $table = $elements[0];
        self::assertInstanceOf(Table::class, $table);
        $style = $table->getStyle();
        self::assertInstanceOf(TableStyle::class, $style);
        self::assertSame('none', $style->getBorderBottomStyle());
        $rows = $table->getRows();
        self::assertCount(3, $rows);
        $cells = $rows[1]->getCells();
        self::assertCount(2, $cells);
        self::assertSame('dotted', $cells[0]->getStyle()->getBorderRightStyle());
        self::assertSame('FF0000', $cells[0]->getStyle()->getBorderRightColor());
        self::assertEmpty($cells[1]->getStyle()->getBorderRightStyle());
        $writer = new HtmlWriter($phpWord);
        $content = $writer->getContent();
        $substring = 'table-layout: auto; border-top-style: none; border-top-width: 0pt; border-left-style: none; border-left-width: 0pt; border-bottom-style: none; border-bottom-width: 0pt; border-right-style: none; border-right-width: 0pt;';
        $count = substr_count($content, $substring);
        $expected = substr_count($content, '<table ')
            + substr_count($content, '<th ')
            + substr_count($content, '<td ') - 1;
        self::assertSame($expected, $count);
        $substring2 = 'border-top-style: dotted; border-top-color: #FF0000; border-left-style: dotted; border-left-color: #FF0000; border-bottom-style: dotted; border-bottom-color: #FF0000; border-right-style: dotted; border-right-color: #FF0000;';
        self::assertSame(1, substr_count($content, $substring2));
        self::assertStringContainsString('style="background-color: #00FF00;">header c</span>', $content);
    }

    public function testParseTableStyleBorderNone(): void
    {
        $html = <<<HTML
<table align="center" style="width: 50%;border:none;">
                <thead>
                    <tr>
                        <th>header a</th>
                        <th>header          b</th>
                        <th><span style="background-color: #00FF00;">header c</span></th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td style="border-style: dotted; border-color: red">1</td><td colspan="2">2</td></tr>
                    <tr><td>This is <b>bold</b> text</td><td></td><td>6</td></tr>
                </tbody>
            </table>
HTML;
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, $html, false, false);
        $elements = $section->getElements();
        $table = $elements[0];
        self::assertInstanceOf(Table::class, $table);
        $style = $table->getStyle();
        self::assertInstanceOf(TableStyle::class, $style);
        self::assertSame('none', $style->getBorderBottomStyle());
        $rows = $table->getRows();
        self::assertCount(3, $rows);
        $cells = $rows[1]->getCells();
        self::assertCount(2, $cells);
        self::assertSame('dotted', $cells[0]->getStyle()->getBorderRightStyle());
        self::assertSame('ff0000', $cells[0]->getStyle()->getBorderRightColor());
        self::assertEmpty($cells[1]->getStyle()->getBorderRightStyle());
        $writer = new HtmlWriter($phpWord);
        $content = $writer->getContent();
        $substring = 'table-layout: auto; border-top-style: none; border-left-style: none; border-bottom-style: none; border-right-style: none;';
        $count = substr_count($content, $substring);
        $expected = substr_count($content, '<table ')
            + substr_count($content, '<th ')
            + substr_count($content, '<td ') - 1;
        self::assertSame($expected, $count);
        $substring2 = 'border-top-style: dotted; border-top-color: #ff0000; border-left-style: dotted; border-left-color: #ff0000; border-bottom-style: dotted; border-bottom-color: #ff0000; border-right-style: dotted; border-right-color: #ff0000;';
        self::assertSame(1, substr_count($content, $substring2));
    }

    public function testParseTableStyleBorderHiddenSameAsNone(): void
    {
        $html = <<<HTML
<table align="center" style="width: 50%;border:hidden;">
                <thead>
                    <tr>
                        <th>header a</th>
                        <th>header          b</th>
                        <th><span style="background-color: #00FF00;">header c</span></th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td style="border-style: dotted; border-color: red">1</td><td colspan="2">2</td></tr>
                    <tr><td>This is <b>bold</b> text</td><td></td><td>6</td></tr>
                </tbody>
            </table>
HTML;
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, $html, false, false);
        $elements = $section->getElements();
        $table = $elements[0];
        self::assertInstanceOf(Table::class, $table);
        $style = $table->getStyle();
        self::assertInstanceOf(TableStyle::class, $style);
        self::assertSame('none', $style->getBorderBottomStyle());
        $rows = $table->getRows();
        self::assertCount(3, $rows);
        $cells = $rows[1]->getCells();
        self::assertCount(2, $cells);
        self::assertSame('dotted', $cells[0]->getStyle()->getBorderRightStyle());
        self::assertSame('ff0000', $cells[0]->getStyle()->getBorderRightColor());
        self::assertEmpty($cells[1]->getStyle()->getBorderRightStyle());
    }

    public function testParseTableStyleBorder2px(): void
    {
        $html = <<<HTML
<table align="center" style="width: 50%;border:2px dashed green;">
                <thead>
                    <tr>
                        <th>header a</th>
                        <th>header          b</th>
                        <th><span style="background-color: #00FF00;">header c</span></th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td style="border: thick dotted red;">1</td><td colspan="2">2</td></tr>
                    <tr><td>This is <b>bold</b> text</td><td></td><td>6</td></tr>
                </tbody>
            </table>
HTML;
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, $html, false, false);
        $elements = $section->getElements();
        $table = $elements[0];
        self::assertInstanceOf(Table::class, $table);
        $style = $table->getStyle();
        self::assertInstanceOf(TableStyle::class, $style);
        self::assertSame('dashed', $style->getBorderBottomStyle());
        self::assertSame('dashed', $style->getBorderInsideHStyle());
        self::assertSame('dashed', $style->getBorderInsideVStyle());
        self::assertSame(15, $style->getBorderBottomSize());
        self::assertSame('00ff00', $style->getBorderBottomColor());
        $rows = $table->getRows();
        self::assertCount(3, $rows);
        $cells = $rows[1]->getCells();
        self::assertCount(2, $cells);
        self::assertSame('dotted', $cells[0]->getStyle()->getBorderRightStyle());
        self::assertSame('ff0000', $cells[0]->getStyle()->getBorderRightColor());
        self::assertEmpty($cells[1]->getStyle()->getBorderRightStyle());
        $writer = new HtmlWriter($phpWord);
        $content = $writer->getContent();

        $substring = 'table-layout: auto; border-top-style: dashed; border-top-color: #00ff00; border-top-width: 0.75pt; border-left-style: dashed; border-left-color: #00ff00; border-left-width: 0.75pt; border-bottom-style: dashed; border-bottom-color: #00ff00; border-bottom-width: 0.75pt; border-right-style: dashed; border-right-color: #00ff00; border-right-width: 0.75pt;';
        $count = substr_count($content, $substring);
        $expected = substr_count($content, '<table ')
            + substr_count($content, '<th ')
            + substr_count($content, '<td ') - 1;
        self::assertSame($expected, $count);
        $substring2 = 'border-top-style: dotted; border-top-color: #ff0000; border-top-width: 1.75pt; border-left-style: dotted; border-left-color: #ff0000; border-left-width: 1.75pt; border-bottom-style: dotted; border-bottom-color: #ff0000; border-bottom-width: 1.75pt; border-right-style: dotted; border-right-color: #ff0000; border-right-width: 1.75pt;';
        self::assertSame(1, substr_count($content, $substring2));
    }
}
