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
use PhpOffice\PhpWordTests\AbstractWebServerEmbedded;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Shared\Html.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Shared\Html
 */
class Html2903Test extends AbstractWebServerEmbedded
{
    /**
     * Tear down after each test.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    /**
     * The `border` shorthand does not have a fixed CSS token order - width, style and color may
     * appear in any order. Real-world CSS (and every browser/editor) emits "width style color",
     * not the "width color style" order the parser used to assume.
     */
    public function testParseBorderShorthandOrderIndependent(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $html = '<table>
                <tr>
                    <td style="border: 2px dashed #FF00FF">hex color</td>
                    <td style="border: 2px dashed magenta">named color</td>
                </tr>
            </table>';
        Html::addHtml($section, $html);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        // 2px = 30 twips (2 / 96 * 1440), halved for Word's bolder rendering = 15
        self::assertSame('dashed', $doc->getElementAttribute('/w:document/w:body/w:tbl/w:tr/w:tc[1]/w:tcPr/w:tcBorders/w:top', 'w:val'));
        self::assertSame('FF00FF', $doc->getElementAttribute('/w:document/w:body/w:tbl/w:tr/w:tc[1]/w:tcPr/w:tcBorders/w:top', 'w:color'));
        self::assertSame('15', $doc->getElementAttribute('/w:document/w:body/w:tbl/w:tr/w:tc[1]/w:tcPr/w:tcBorders/w:top', 'w:sz'));

        self::assertSame('dashed', $doc->getElementAttribute('/w:document/w:body/w:tbl/w:tr/w:tc[2]/w:tcPr/w:tcBorders/w:top', 'w:val'));
        self::assertSame('ff00ff', $doc->getElementAttribute('/w:document/w:body/w:tbl/w:tr/w:tc[2]/w:tcPr/w:tcBorders/w:top', 'w:color')); // magenta
        self::assertSame('15', $doc->getElementAttribute('/w:document/w:body/w:tbl/w:tr/w:tc[2]/w:tcPr/w:tcBorders/w:top', 'w:sz'));
    }

    /**
     * `border-color` used to be dropped entirely for named colors, because the parser decided
     * single- vs. multi-color by counting "#" characters in the value.
     */
    public function testParseBorderColorNamedColor(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, '<table><tr><td style="border-color: red; border-width: 1px;">cell</td></tr></table>');

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        self::assertSame('ff0000', $doc->getElementAttribute('/w:document/w:body/w:tbl/w:tr/w:tc/w:tcPr/w:tcBorders/w:top', 'w:color'));
    }

    /**
     * The `border-width` longhand used to be stored in points but read back everywhere else as
     * twips, rendering roughly 20x thinner than intended. It should produce the same size as the
     * equivalent `border` shorthand.
     */
    public function testParseBorderWidthLonghandUnit(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $html = '<table>
                <tr>
                    <td style="border-width: 1px; border-style: solid; border-color: #000000;">longhand</td>
                    <td style="border: 1px solid #000000;">shorthand</td>
                </tr>
            </table>';
        Html::addHtml($section, $html);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        // 1px = 15 twips (1 / 96 * 1440), halved for Word's bolder rendering = 7
        self::assertSame('7', $doc->getElementAttribute('/w:document/w:body/w:tbl/w:tr/w:tc[1]/w:tcPr/w:tcBorders/w:top', 'w:sz'));
        self::assertSame('7', $doc->getElementAttribute('/w:document/w:body/w:tbl/w:tr/w:tc[2]/w:tcPr/w:tcBorders/w:top', 'w:sz'));
    }

    /**
     * CSS border-style keywords beyond none/dashed/dotted/double used to silently fall back to
     * "single" (solid).
     */
    public function testParseBorderStyleExtendedKeywords(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $html = '<table>
                <tr>
                    <td style="border-style: groove; border-width: 1px;">groove</td>
                    <td style="border-style: ridge; border-width: 1px;">ridge</td>
                    <td style="border-style: inset; border-width: 1px;">inset</td>
                    <td style="border-style: outset; border-width: 1px;">outset</td>
                </tr>
            </table>';
        Html::addHtml($section, $html);

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        self::assertSame('threeDEngrave', $doc->getElementAttribute('/w:document/w:body/w:tbl/w:tr/w:tc[1]/w:tcPr/w:tcBorders/w:top', 'w:val'));
        self::assertSame('threeDEmboss', $doc->getElementAttribute('/w:document/w:body/w:tbl/w:tr/w:tc[2]/w:tcPr/w:tcBorders/w:top', 'w:val'));
        self::assertSame('inset', $doc->getElementAttribute('/w:document/w:body/w:tbl/w:tr/w:tc[3]/w:tcPr/w:tcBorders/w:top', 'w:val'));
        self::assertSame('outset', $doc->getElementAttribute('/w:document/w:body/w:tbl/w:tr/w:tc[4]/w:tcPr/w:tcBorders/w:top', 'w:val'));
    }

    /**
     * `Style\Border::hasBorder()` gates purely on border size being set, so a bare
     * `border-style`/`border-color` declaration without a width used to be silently dropped
     * entirely, even though CSS itself defaults an unset border-width to "medium" whenever a
     * style/color is present.
     */
    public function testParseBorderStyleWithoutWidthStillRenders(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, '<table><tr><td style="border-style: dashed;">cell</td></tr></table>');

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tr/w:tc/w:tcPr/w:tcBorders/w:top'));
        self::assertSame('dashed', $doc->getElementAttribute('/w:document/w:body/w:tbl/w:tr/w:tc/w:tcPr/w:tcBorders/w:top', 'w:val'));
        self::assertNotNull($doc->getElementAttribute('/w:document/w:body/w:tbl/w:tr/w:tc/w:tcPr/w:tcBorders/w:top', 'w:sz'));
    }

    /**
     * Writer\Word2007\Style\Table::writeBorder() used to never pass border style through to its
     * XML writer (only size and color), so a table's own <w:tblBorders> always rendered every
     * side as "single" no matter what border-style was configured.
     */
    public function testParseTableLevelBorderStyleIsWritten(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        Html::addHtml($section, '<table style="border: 1px double #000000;"><tr><td>cell</td></tr></table>');

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        self::assertSame('double', $doc->getElementAttribute('/w:document/w:body/w:tbl/w:tblPr/w:tblBorders/w:top', 'w:val'));
    }
}
