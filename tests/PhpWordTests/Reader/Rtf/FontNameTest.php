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

namespace PhpOffice\PhpWordTests\Reader\Rtf;

use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Language;
use PhpOffice\PhpWordTests\AbstractTestReader;

/**
 * Test class for PhpOffice\PhpWord\Reader\Word2007\Styles.
 */
class FontNameTest extends AbstractTestReader
{
    public function testUnderline(): void
    {
        $phpWord = new PhpWord();
        $phpWord->getSettings()
            ->setThemeFontLang(new Language(Language::EN_US));
        $phpWord->getSettings()
            ->getThemeFontLang()
            ->setLangId(Language::EN_US_ID); // needed for RTF
        $section = $phpWord->addSection();

        $text = 'This text has an underlined color.';
        // Test with a valid color (hex)
        $fontStyle = [
            'underline' => Font::UNDERLINE_SINGLE,
            'underlineColor' => 'FF4030',
            'color' => '4030FF',
            'fgColor' => 'FFFF00',
            'name' => 'Times New Roman',
            'size' => 12,
        ];
        $section->addText($text, $fontStyle);

        $text = 'This text does not.';
        // Test with a valid color (hex)
        $fontStyle = [
            'underline' => Font::UNDERLINE_WAVYDOUBLE,
            //'underlineColor' => 'FF4030',
        ];
        $section->addText($text, $fontStyle);

        $text = 'This text has a green underlined color.';
        // Test with a valid color (hex)
        $fontStyle = [
            'underline' => Font::UNDERLINE_SINGLE,
            //'underlineColor' => '00FF00',
            'underlineColor' => 'green',
            'name' => 'Courier New',
        ];
        $section->addText($text, $fontStyle);

        $text = 'This text has no underline.';
        // Test with a valid color (hex)
        $section->addText($text);

        $newWord = $this->writeAndReload($phpWord, 'RTF');

        $section = $newWord->getSection(0);
        /** @var string[][] */
        $outArray = [];
        foreach ($section->getElements() as $element) {
            if ($element instanceof TextRun) {
                foreach ($element->getElements() as $texts) {
                    if ($texts instanceof Text) {
                        $fontStyle = $texts->getFontStyle();
                        if ($fontStyle instanceof Font) {
                            $fontName = $fontStyle->getName();
                            $fontSize = $fontStyle->getSize();
                            $outArray[] = [$texts->getText(), $fontName, $fontSize];
                        }
                    }
                }
            }
        }

        // text, color, underline, underlineColor, fgColor
        $expected = [
            ['This text has an underlined color.', 'Times New Roman', 12],
            ['This text does not.', null, 10],
            ['This text has a green underlined color.', 'Courier New', 10],
            ['This text has no underline.', null, 10],
        ];
        self::assertSame($expected, $outArray);
    }
}
