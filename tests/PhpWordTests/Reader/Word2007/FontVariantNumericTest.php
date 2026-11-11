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

namespace PhpOffice\PhpWordTests\Reader\Word2007;

use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWordTests\AbstractTestReader;

/**
 * Test class for PhpOffice\PhpWord\Reader\Word2007\Styles.
 */
class FontVariantNumericTest extends AbstractTestReader
{
    public function testUnderline(): void
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

        $newWord = $this->writeAndReload($phpWord);

        $section = $newWord->getSection(0);
        /** @var string[][] */
        $outArray = [];
        foreach ($section->getElements() as $element) {
            if ($element instanceof TextRun) {
                foreach ($element->getElements() as $texts) {
                    if ($texts instanceof Text) {
                        $fontStyle = $texts->getFontStyle();
                        if ($fontStyle instanceof Font) {
                            $numberSpacing = $fontStyle->getNumberSpacing();
                            $numberForms = $fontStyle->getNumberForms();
                            $outArray[] = [$texts->getText(), $numberSpacing, $numberForms];
                        }
                    }
                }
            }
        }

        $expected = [
            ['Default: 0123456789', '', ''],
            ['Lining-nums (uppercase): 0123456789', '', Font::NUMBER_FORMS_LINING],
            ['Oldstyle-nums (lowercase): 0123456789', '', Font::NUMBER_FORMS_OLDSTYLE],
            ['0123456789 Default', '', ''],
            ['0123456789 proportional-nums', Font::NUMBER_SPACING_PROPORTIONAL, ''],
            ['0123456789 tabular-nums', Font::NUMBER_SPACING_TABULAR, ''],
            ['0123456789 both proportional and oldstyle', Font::NUMBER_SPACING_PROPORTIONAL, Font::NUMBER_FORMS_OLDSTYLE],
        ];
        self::assertSame($expected, $outArray);
    }
}
