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

namespace PhpOffice\PhpWordTests;

use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style;

/**
 * Test class for PhpOffice\PhpWord\Style.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Style
 */
class StyleTest extends \PHPUnit\Framework\TestCase
{
    protected function tearDown(): void
    {
        Style::resetStyles();
    }

    /**
     * Add and get paragraph, font, link, title, and table styles.
     *
     * @covers ::addFontStyle
     * @covers ::addLinkStyle
     * @covers ::addNumberingStyle
     * @covers ::addParagraphStyle
     * @covers ::addTableStyle
     * @covers ::addTitleStyle
     * @covers ::countStyles
     * @covers ::getStyle
     * @covers ::getStyles
     * @covers ::resetStyles
     * @covers ::setDefaultParagraphStyle
     */
    public function testStyles(): void
    {
        Style::resetStyles();
        self::assertCount(0, Style::getStyles());
        $paragraph = ['alignment' => Jc::CENTER];
        $font = ['italic' => true, '_bold' => true];
        $table = ['bgColor' => 'CCCCCC'];
        $numbering = [
            'type' => 'multilevel',
            'levels' => [
                [
                    'start' => 1,
                    'format' => 'decimal',
                    'restart' => 1,
                    'suffix' => 'space',
                    'text' => '%1.',
                    'alignment' => Jc::START,
                ],
            ],
        ];

        $styles = [
            'Paragraph' => Style\Paragraph::class,
            'Font' => Style\Font::class,
            'Link' => Style\Font::class,
            'Table' => Style\Table::class,
            'Heading_1' => Style\Font::class,
            'Normal' => Style\Paragraph::class,
            'Numbering' => Style\Numbering::class,
        ];

        Style::addParagraphStyle('Paragraph', $paragraph);
        Style::addFontStyle('Font', $font);
        Style::addLinkStyle('Link', $font);
        Style::addNumberingStyle('Numbering', $numbering);
        Style::addTitleStyle(1, $font);
        Style::addTableStyle('Table', $table);
        Style::setDefaultParagraphStyle($paragraph);

        self::assertCount(count($styles), Style::getStyles());
        foreach ($styles as $name => $style) {
            self::assertInstanceOf($style, Style::getStyle($name));
        }
        self::assertNull(Style::getStyle('Unknown'));
    }

    /**
     * Test default paragraph style.
     *
     * @covers ::setDefaultParagraphStyle
     */
    public function testDefaultParagraphStyle(): void
    {
        $paragraph = ['alignment' => Jc::CENTER];

        Style::setDefaultParagraphStyle($paragraph);

        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', Style::getStyle('Normal'));
    }
}
