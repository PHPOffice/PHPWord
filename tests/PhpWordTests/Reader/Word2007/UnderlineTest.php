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
class UnderlineTest extends AbstractTestReader
{
    public function testUnderline(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Test with a valid color (hex)
        $text = 'This text has an underlined color.';
        $fontStyle = [
            'underline' => Font::UNDERLINE_SINGLE,
            'underlineColor' => 'FF4030',
        ];
        $section->addText($text, $fontStyle);

        // Test with a valid color (hex)
        $text = 'So does this.';
        $fontStyle = [
            'underline' => Font::UNDERLINE_SINGLE,
            'underlineColor' => 'green',
        ];
        $section->addText($text, $fontStyle);

        // Test with a valid color (hex)
        $text = 'This does not.';
        $fontStyle = [
            'underline' => Font::UNDERLINE_DOUBLE,
        ];
        $section->addText($text, $fontStyle);

        // Test with a valid color (hex)
        $text = 'This has no underline.';
        $section->addText($text);

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
                            $underline = $fontStyle->getUnderline();
                            $underlineColor = $fontStyle->getUnderlineColor();
                            $outArray[] = [$texts->getText(), $underline, $underlineColor];
                        }
                    }
                }
            }
        }

        $expected = [
            ['This text has an underlined color.', 'single', 'FF4030'],
            ['So does this.', 'single', 'green'],
            ['This does not.', 'double', ''],
            ['This has no underline.', 'none', ''],
        ];
        self::assertSame($expected, $outArray);
    }
}
