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

namespace PhpOffice\PhpWordTests\Writer\Word2007\Style;

use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Style\Table.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\Word2007\Style\Table
 *
 * @runTestsInSeparateProcesses
 */
class TableCellTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed before each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    /**
     * Test write styles.
     */
    public function testCellPadding(): void
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $table = $section->addTable();
        $table->addRow();

        $testValTop = Converter::pixelToTwip(10);
        $testValRight = Converter::pixelToTwip(11);
        $testValBottom = Converter::pixelToTwip(12);
        $testValLeft = Converter::pixelToTwip(13);

        $cellStyle = [
            'paddingTop' => $testValTop,
            'paddingRight' => $testValRight,
            'paddingBottom' => $testValBottom,
            'paddingLeft' => $testValLeft,
        ];
        $table->addCell(null, $cellStyle)->addText('Some text');
        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $path = '/w:document/w:body/w:tbl/w:tr/w:tc/w:tcPr/w:tcMar/w:top';
        self::assertTrue($doc->elementExists($path));
        self::assertEquals($testValTop, $doc->getElementAttribute($path, 'w:w'));
        self::assertEquals(TblWidth::TWIP, $doc->getElementAttribute($path, 'w:type'));

        $path = '/w:document/w:body/w:tbl/w:tr/w:tc/w:tcPr/w:tcMar/w:start';
        self::assertTrue($doc->elementExists($path));
        self::assertEquals($testValLeft, $doc->getElementAttribute($path, 'w:w'));
        self::assertEquals(TblWidth::TWIP, $doc->getElementAttribute($path, 'w:type'));

        $path = '/w:document/w:body/w:tbl/w:tr/w:tc/w:tcPr/w:tcMar/w:bottom';
        self::assertTrue($doc->elementExists($path));
        self::assertEquals($testValBottom, $doc->getElementAttribute($path, 'w:w'));
        self::assertEquals(TblWidth::TWIP, $doc->getElementAttribute($path, 'w:type'));

        $path = '/w:document/w:body/w:tbl/w:tr/w:tc/w:tcPr/w:tcMar/w:end';
        self::assertTrue($doc->elementExists($path));
        self::assertEquals($testValRight, $doc->getElementAttribute($path, 'w:w'));
        self::assertEquals(TblWidth::TWIP, $doc->getElementAttribute($path, 'w:type'));
    }
}
