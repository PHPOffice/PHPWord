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
}
