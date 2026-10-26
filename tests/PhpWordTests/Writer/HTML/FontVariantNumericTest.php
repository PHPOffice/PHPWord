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

namespace PhpOffice\PhpWordTests\Writer\HTML;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Reader\HTML as HtmlReader;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Writer\HTML as HtmlWriter;

/**
 * Test class for PhpOffice\PhpWord\Writer\HTML\Element subnamespace.
 */
class FontVariantNumericTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test unmatched elements.
     */
    public function testFontVariantNumeric(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText(
            'Default: 0123456789',
            ['name' => 'Times New Roman', 'size' => 12, 'fallbackFont' => 'serif']
        );

        $section->addText(
            'Lining-nums (uppercase): 0123456789',
            ['name' => 'Times New Roman', 'size' => 12, 'numberForms' => Font::NUMBER_FORMS_LINING]
        );

        $section->addText(
            'Oldstyle-nums (lowercase): 0123456789',
            ['name' => 'Times New Roman', 'size' => 12, 'numberForms' => Font::NUMBER_FORMS_OLDSTYLE]
        );

        $section->addText(
            '0123456789 Default',
            ['name' => 'Times New Roman', 'size' => 12]
        );

        $section->addText(
            '0123456789 proportional-nums',
            ['name' => 'Times New Roman', 'size' => 12, 'numberSpacing' => Font::NUMBER_SPACING_PROPORTIONAL]
        );

        $section->addText(
            '0123456789 tabular-nums',
            ['name' => 'Times New Roman', 'size' => 12, 'numberSpacing' => Font::NUMBER_SPACING_TABULAR]
        );

        $section->addText(
            '0123456789 both proportional and oldstyle',
            ['name' => 'Times New Roman', 'size' => 12, 'numberSpacing' => Font::NUMBER_SPACING_PROPORTIONAL, 'numberForms' => Font::NUMBER_FORMS_OLDSTYLE]
        );

        $writer = new HtmlWriter($phpWord);
        $content = $writer->getContent();

        $phpWord2 = (new HtmlReader())->loadFromString($content);
        $writer2 = new HtmlWriter($phpWord2);
        $content2 = $writer2->getContent();
        $expected = [
            '<p><span style="font-family: \'Times New Roman\', serif; font-size: 12pt;">Default: 0123456789</span></p>',
            '<p><span style="font-family: \'Times New Roman\'; font-size: 12pt; font-variant-numeric: lining-nums;">Lining-nums (uppercase): 0123456789</span></p>',
            '<p><span style="font-family: \'Times New Roman\'; font-size: 12pt; font-variant-numeric: oldstyle-nums;">Oldstyle-nums (lowercase): 0123456789</span></p>',
            '<p><span style="font-family: \'Times New Roman\'; font-size: 12pt;">0123456789 Default</span></p>',
            '<p><span style="font-family: \'Times New Roman\'; font-size: 12pt; font-variant-numeric: proportional-nums;">0123456789 proportional-nums</span></p>',
            '<p><span style="font-family: \'Times New Roman\'; font-size: 12pt; font-variant-numeric: tabular-nums;">0123456789 tabular-nums</span></p>',
            '<p><span style="font-family: \'Times New Roman\'; font-size: 12pt; font-variant-numeric: proportional-nums oldstyle-nums;">0123456789 both proportional and oldstyle</span></p>',
        ];
        foreach ($expected as $line) {
            self::assertStringContainsString($line, $content2);
        }
    }
}
