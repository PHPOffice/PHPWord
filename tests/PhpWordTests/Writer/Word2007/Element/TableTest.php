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
declare(strict_types=1);

namespace PhpOffice\PhpWordTests\Writer\Word2007\Element;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Element subnamespace.
 */
class TableTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Executed after each method of the class.
     */
    protected function tearDown(): void
    {
        TestHelperDOCX::clear();
    }

    public static function testTableNormal(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Before table (normal).');
        $table = $section->addTable(['width' => 5000, 'unit' => TblWidth::PERCENT]);
        $row = $table->addRow();
        $tc = $table->addCell();
        $tc->addText('R1C1');
        $tc = $table->addCell();
        $tc->addText('R1C2');
        $row = $table->addRow();
        $tc = $table->addCell();
        $tc->addText('R2C1');
        $tc = $table->addCell();
        $tc->addText('R2C2');
        $row = $table->addRow();
        $tc = $table->addCell();
        $tc->addText('R3C1');
        $tc = $table->addCell();
        $tc->addText('R3C2');
        $section->addText('After table.');

        $doc = TestHelperDOCX::getDocument($phpWord);
        self::assertFalse($doc->elementExists('/w:document/w:body/w:tbl[2]'), 'should be only 1 table');

        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tr[1]'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tr[1]/w:tc'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tr[1]/w:tc[2]'));
        self::assertFalse($doc->elementExists('/w:document/w:body/w:tbl/w:tr[1]/w:tc[3]'));

        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tr[2]'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tr[2]/w:tc'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tr[2]/w:tc[2]'));
        self::assertFalse($doc->elementExists('/w:document/w:body/w:tbl/w:tr[2]/w:tc[3]'));

        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tr[3]'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tr[3]/w:tc'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tr[3]/w:tc[2]'));
        self::assertFalse($doc->elementExists('/w:document/w:body/w:tbl/w:tr[3]/w:tc[3]'));
    }

    public static function testSomeRowWithNoCells(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Before table (row 2 has no cells).');
        $table = $section->addTable(['width' => 5000, 'unit' => TblWidth::PERCENT]);
        $row = $table->addRow();
        $tc = $table->addCell();
        $tc->addText('R1C1');
        $tc = $table->addCell();
        $tc->addText('R1C2');
        $row = $table->addRow();
        $row = $table->addRow();
        $tc = $table->addCell();
        $tc->addText('R3C1');
        $tc = $table->addCell();
        $tc->addText('R3C2');
        $section->addText('After table.');

        $doc = TestHelperDOCX::getDocument($phpWord);
        self::assertFalse($doc->elementExists('/w:document/w:body/w:tbl[2]'), 'should be only 1 table');

        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tr[1]'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tr[1]/w:tc'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tr[1]/w:tc[2]'));
        self::assertFalse($doc->elementExists('/w:document/w:body/w:tbl/w:tr[1]/w:tc[3]'));

        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tr[2]'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tr[2]/w:tc'));
        self::assertFalse($doc->elementExists('/w:document/w:body/w:tbl/w:tr[2]/w:tc[2]'));

        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tr[3]'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tr[3]/w:tc'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tr[3]/w:tc[2]'));
        self::assertFalse($doc->elementExists('/w:document/w:body/w:tbl/w:tr[3]/w:tc[3]'));
    }

    public static function testOnly1RowWithNoCells(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Before table (only 1 row and it has no cells).');
        $table = $section->addTable(['width' => 5000, 'unit' => TblWidth::PERCENT]);
        $row = $table->addRow();
        $section->addText('After table.');

        $doc = TestHelperDOCX::getDocument($phpWord);
        self::assertFalse($doc->elementExists('/w:document/w:body/w:tbl[2]'), 'only 1 table should be written');

        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tr[1]'));
        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tr[1]/w:tc'));
        self::assertFalse($doc->elementExists('/w:document/w:body/w:tbl/w:tr[1]/w:tc[2]'));

        self::assertFalse($doc->elementExists('/w:document/w:body/w:tbl/w:tr[2]'));
    }

    public static function testNoRows(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Before table (no rows therefore omitted).');
        $table = $section->addTable(['width' => 5000, 'unit' => TblWidth::PERCENT]);
        $section->addText('After table.');

        $doc = TestHelperDOCX::getDocument($phpWord);
        self::assertFalse($doc->elementExists('/w:document/w:body/w:tbl[1]'), 'no table should be written');
    }

    /**
     * A column of the grid of a table has a width and nothing else.
     *
     * `CT_TblGridCol` declares a `w:w` and no `w:type`, and the width of a
     * column is in twips either way.
     */
    public static function testColumnOfTheGridHasNoType(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $table = $section->addTable(['width' => 5000, 'unit' => TblWidth::PERCENT]);
        $row = $table->addRow();
        $cell = $row->addCell(5000);
        $cell->addText('R1C1');

        $doc = TestHelperDOCX::getDocument($phpWord);

        $path = '/w:document/w:body/w:tbl/w:tblGrid/w:gridCol';
        self::assertTrue($doc->elementExists($path));
        self::assertFalse($doc->hasElementAttribute($path, 'w:type'));
    }

    /**
     * A table has properties of its own, whether it was given any or not.
     *
     * `CT_Tbl` names `w:tblPr` before `w:tblGrid` and does not make it
     * optional, so a table without properties is a table whose grid a reader
     * that walks the sequence stops at.
     */
    public static function testATableWithoutAStyleStillHasProperties(): void
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $table = $section->addTable();
        $table->addRow()->addCell()->addText('R1C1');

        $doc = TestHelperDOCX::getDocument($phpWord);

        self::assertTrue($doc->elementExists('/w:document/w:body/w:tbl/w:tblPr'));
    }
}
