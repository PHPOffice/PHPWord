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
     * The tab stop of a level without one of its own is the left indent of the level.
     *
     * `CT_TabStop` requires a `w:pos`, and the tab stop of a level is where the
     * text of the level begins, which is the left indent of the level.
     */
    public function testTabStopOfALevelWithoutOneIsTheLeftIndent(): void
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
                        'text' => '%1.',
                        'left' => 360,
                        'hanging' => 360,
                    ],
                ],
            ]
        );

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $path = '/w:numbering/w:abstractNum/w:lvl/w:pPr/w:tabs/w:tab[@w:val="num"]';
        self::assertTrue($doc->elementExists($path, $xmlFile));
        self::assertSame('360', $doc->getElementAttribute($path, 'w:pos', $xmlFile));
    }

    /**
     * A level without a tab stop and without a left indent has no tab stop.
     *
     * `CT_TabStop` requires a `w:pos`, and a level without a left indent has no
     * place where the text of the level would begin.
     */
    public function testLevelWithoutALeftIndentHasNoTabStop(): void
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
                        'text' => '%1.',
                    ],
                ],
            ]
        );

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        self::assertFalse($doc->elementExists('/w:numbering/w:abstractNum/w:lvl/w:pPr/w:tabs', $xmlFile));
    }
}
