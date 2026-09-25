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

namespace PhpOffice\PhpWordTests\Writer\Word2007\Part;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\NumberFormat;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Part\Numbering.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\Word2007\Part\Numbering
 *
 * @runTestsInSeparateProcesses
 *
 * @since 0.10.0
 */
class NumberingTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed before each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    /**
     * Write footnotes.
     */
    public function testWriteNumbering(): void
    {
        $xmlFile = 'word/numbering.xml';

        $phpWord = new PhpWord();
        $phpWord->addNumberingStyle(
            'numStyle',
            [
                'type' => 'multilevel',
                'levels' => [
                    [
                        'start' => 1,
                        'format' => NumberFormat::DECIMAL,
                        'restart' => 1,
                        'suffix' => 'space',
                        'text' => '%1.',
                        'alignment' => Jc::START,
                        'left' => 360,
                        'hanging' => 360,
                        'tabPos' => 360,
                        'font' => 'Arial',
                        'hint' => 'default',
                    ],
                ],
            ]
        );

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        self::assertTrue($doc->elementExists('/w:numbering/w:abstractNum', $xmlFile));
    }

    /**
     * Numbering level font size and color are applied to the numbering symbol.
     *
     * @see https://github.com/PHPOffice/PHPWord/issues/2672
     */
    public function testNumberingLevelFontAndSize(): void
    {
        $xmlFile = 'word/numbering.xml';

        $phpWord = new PhpWord();
        $phpWord->addNumberingStyle(
            'numberStyle',
            [
                'type' => 'multilevel',
                'levels' => [
                    [
                        'format' => NumberFormat::DECIMAL,
                        'text' => '%1.',
                        'font' => 'Times New Roman',
                        'size' => 16,
                        'color' => '996633',
                    ],
                ],
            ]
        );

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        $parent = '/w:numbering/w:abstractNum/w:lvl/w:rPr';

        self::assertTrue($doc->elementExists($parent . '/w:rFonts', $xmlFile));
        self::assertEquals('Times New Roman', $doc->getElementAttribute("{$parent}/w:rFonts", 'w:ascii', $xmlFile));
        self::assertTrue($doc->elementExists($parent . '/w:sz', $xmlFile));
        self::assertEquals(32, $doc->getElement("{$parent}/w:sz", $xmlFile)->getAttribute('w:val'));
        self::assertTrue($doc->elementExists($parent . '/w:szCs', $xmlFile));
        self::assertEquals(32, $doc->getElement("{$parent}/w:szCs", $xmlFile)->getAttribute('w:val'));
        self::assertTrue($doc->elementExists($parent . '/w:color', $xmlFile));
        self::assertEquals('996633', $doc->getElement("{$parent}/w:color", $xmlFile)->getAttribute('w:val'));
    }

    /**
     * Levels without size/color keep the previous output (no empty w:sz / w:color).
     */
    public function testNumberingLevelWithoutSizeKeepsCleanRpr(): void
    {
        $xmlFile = 'word/numbering.xml';

        $phpWord = new PhpWord();
        $phpWord->addNumberingStyle(
            'plainStyle',
            [
                'type' => 'multilevel',
                'levels' => [
                    ['format' => NumberFormat::DECIMAL, 'text' => '%1.'],
                ],
            ]
        );

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');
        $parent = '/w:numbering/w:abstractNum/w:lvl/w:rPr';

        self::assertTrue($doc->elementExists($parent, $xmlFile));
        self::assertFalse($doc->elementExists($parent . '/w:sz', $xmlFile));
        self::assertFalse($doc->elementExists($parent . '/w:color', $xmlFile));
    }
}
