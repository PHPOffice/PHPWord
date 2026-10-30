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

use PhpOffice\PhpWord\ComplexType\TblWidth as TblWidthComplexType;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\Style\Table;
use PhpOffice\PhpWord\Style\TablePosition;
use PhpOffice\PhpWordTests\TestHelperDOCX;

/**
 * Test class for PhpOffice\PhpWord\Writer\Word2007\Style\Table.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Writer\Word2007\Style\Table
 *
 * @runTestsInSeparateProcesses
 */
class TableTest extends \PHPUnit\Framework\TestCase
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
    public function testTableLayout(): void
    {
        $tableStyle = new Table();
        $tableStyle->setLayout(Table::LAYOUT_FIXED);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $table = $section->addTable($tableStyle);
        $table->addRow();

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $path = '/w:document/w:body/w:tbl/w:tblPr/w:tblLayout';
        self::assertTrue($doc->elementExists($path));
        self::assertEquals(Table::LAYOUT_FIXED, $doc->getElementAttribute($path, 'w:type'));
    }

    /**
     * Test write styles.
     */
    public function testCellSpacing(): void
    {
        $tableStyle = new Table();
        $tableStyle->setCellSpacing(10.3);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $table = $section->addTable($tableStyle);
        $table->addRow();

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $path = '/w:document/w:body/w:tbl/w:tblPr/w:tblCellSpacing';
        self::assertTrue($doc->elementExists($path));
        self::assertEquals(10.3, $doc->getElementAttribute($path, 'w:w'));
        self::assertEquals(TblWidth::TWIP, $doc->getElementAttribute($path, 'w:type'));
    }

    /**
     * Test write table position.
     */
    public function testTablePosition(): void
    {
        $tablePosition = [
            'leftFromText' => 10,
            'rightFromText' => 20,
            'topFromText' => 30,
            'bottomFromText' => 40,
            'vertAnchor' => TablePosition::VANCHOR_PAGE,
            'horzAnchor' => TablePosition::HANCHOR_MARGIN,
            'tblpXSpec' => TablePosition::XALIGN_CENTER,
            'tblpX' => 50,
            'tblpYSpec' => TablePosition::YALIGN_TOP,
            'tblpY' => 60,
        ];
        $tableStyle = new Table();
        $tableStyle->setPosition($tablePosition);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $table = $section->addTable($tableStyle);
        $table->addRow();

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $path = '/w:document/w:body/w:tbl/w:tblPr/w:tblpPr';
        self::assertTrue($doc->elementExists($path));
        self::assertEquals(10, $doc->getElementAttribute($path, 'w:leftFromText'));
        self::assertEquals(20, $doc->getElementAttribute($path, 'w:rightFromText'));
        self::assertEquals(30, $doc->getElementAttribute($path, 'w:topFromText'));
        self::assertEquals(40, $doc->getElementAttribute($path, 'w:bottomFromText'));
        self::assertEquals(TablePosition::VANCHOR_PAGE, $doc->getElementAttribute($path, 'w:vertAnchor'));
        self::assertEquals(TablePosition::HANCHOR_MARGIN, $doc->getElementAttribute($path, 'w:horzAnchor'));
        self::assertEquals(TablePosition::XALIGN_CENTER, $doc->getElementAttribute($path, 'w:tblpXSpec'));
        self::assertEquals(50, $doc->getElementAttribute($path, 'w:tblpX'));
        self::assertEquals(TablePosition::YALIGN_TOP, $doc->getElementAttribute($path, 'w:tblpYSpec'));
        self::assertEquals(60, $doc->getElementAttribute($path, 'w:tblpY'));
    }

    public function testIndent(): void
    {
        $value = 100;
        $type = TblWidth::TWIP;

        $tableStyle = new Table();
        $tableStyle->setIndent(new TblWidthComplexType($value, $type));

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $table = $section->addTable($tableStyle);
        $table->addRow();

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $path = '/w:document/w:body/w:tbl/w:tblPr/w:tblInd';
        self::assertTrue($doc->elementExists($path));
        self::assertSame($value, (int) $doc->getElementAttribute($path, 'w:w'));
        self::assertSame($type, $doc->getElementAttribute($path, 'w:type'));
    }

    public function testRigthToLeft(): void
    {
        $tableStyle = new Table();
        $tableStyle->setBidiVisual(true);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $table = $section->addTable($tableStyle);
        $table->addRow();

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $path = '/w:document/w:body/w:tbl/w:tblPr/w:bidiVisual';
        self::assertTrue($doc->elementExists($path));
        self::assertSame('on', $doc->getElementAttribute($path, 'w:val'));
    }

    /**
     * A table that is not right to left says so in words.
     *
     * The SDK reads the value of `w:bidiVisual` as an enumeration of `on` and
     * `off` and refuses to read `0`, which the schema's `ST_OnOff` allows.
     */
    public function testNotRightToLeft(): void
    {
        $tableStyle = new Table();
        $tableStyle->setBidiVisual(false);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $table = $section->addTable($tableStyle);
        $table->addRow();

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $path = '/w:document/w:body/w:tbl/w:tblPr/w:bidiVisual';
        self::assertTrue($doc->elementExists($path));
        self::assertSame('off', $doc->getElementAttribute($path, 'w:val'));
    }

    /**
     * The shading of a table belongs in the properties of the table.
     *
     * `CT_TblPrBase` takes a `w:shd` of its own, and `CT_Tbl` has no `w:tcPr`:
     * a shading written outside the properties of the table never reaches the
     * document.
     */
    public function testShadingIsWrittenInThePropertiesOfTheTable(): void
    {
        $tableStyle = new Table();
        $tableStyle->setShading(['fill' => '00FF00', 'type' => 'clear']);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $table = $section->addTable($tableStyle);
        $table->addRow();

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $path = '/w:document/w:body/w:tbl/w:tblPr/w:shd';
        self::assertTrue($doc->elementExists($path));
        self::assertSame('00FF00', $doc->getElementAttribute($path, 'w:fill'));
        self::assertFalse($doc->elementExists('/w:document/w:body/w:tbl/w:tcPr'));
    }

    /**
     * The borders of the first row of a table are the borders of a cell.
     *
     * The properties of a cell take a `w:tcBorders`, and a `w:tblBorders` in them
     * is not a thing the schema knows.
     */
    public function testBordersOfTheFirstRowAreWrittenAsTheBordersOfACell(): void
    {
        $tableStyle = new Table([], ['borderSize' => 10, 'borderColor' => '000000']);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $table = $section->addTable($tableStyle);
        $table->addRow();

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $path = '/w:document/w:body/w:tbl/w:tblStylePr[@w:type="firstRow"]/w:tcPr/w:tcBorders';
        self::assertTrue($doc->elementExists($path));
        self::assertFalse($doc->elementExists('/w:document/w:body/w:tbl/w:tblStylePr/w:tcPr/w:tblBorders'));
    }

    /**
     * The shading of the first row of a table belongs in the properties of the cell.
     *
     * `CT_TcPr` takes a `w:shd` of its own, and a `w:tcPr` inside a `w:tcPr` is
     * not a thing the schema knows.
     */
    public function testShadingOfTheFirstRowIsWrittenInThePropertiesOfTheCell(): void
    {
        $tableStyle = new Table([], ['shading' => ['fill' => 'FF0000', 'type' => 'clear']]);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $table = $section->addTable($tableStyle);
        $table->addRow();

        $doc = TestHelperDOCX::getDocument($phpWord, 'Word2007');

        $path = '/w:document/w:body/w:tbl/w:tblStylePr[@w:type="firstRow"]/w:tcPr/w:shd';
        self::assertTrue($doc->elementExists($path));
        self::assertSame('FF0000', $doc->getElementAttribute($path, 'w:fill'));
        self::assertFalse($doc->elementExists('/w:document/w:body/w:tbl/w:tblStylePr/w:tcPr/w:tcPr'));
    }
}
