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

namespace PhpOffice\PhpWordTests\Style;

use PhpOffice\PhpWord\ComplexType\TblWidth as TblWidthComplexType;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\SimpleType\JcTable;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\Style\Table;
use PhpOffice\PhpWord\Style\TablePosition;

/**
 * Test class for PhpOffice\PhpWord\Style\Table.
 *
 * @runTestsInSeparateProcesses
 */
class TableTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test class construction.
     *
     * There are 3 variables for class constructor:
     * - $styleTable: Define table styles
     * - $styleFirstRow: Define style for the first row
     */
    public function testConstruct(): void
    {
        $styleTable = ['bgColor' => 'FF0000'];
        $styleFirstRow = ['borderBottomSize' => 3];

        $object = new Table($styleTable, $styleFirstRow);
        self::assertEquals('FF0000', $object->getBgColor());

        $firstRow = $object->getFirstRow();
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Table', $firstRow);
        self::assertEquals(3, $firstRow->getBorderBottomSize());
    }

    /**
     * Test default values when passing no style.
     */
    public function testDefaultValues(): void
    {
        $object = new Table();

        self::assertNull($object->getBgColor());
        self::assertEquals(Table::LAYOUT_AUTO, $object->getLayout());
        self::assertEquals(TblWidth::AUTO, $object->getUnit());
        self::assertNull($object->getIndent());
    }

    /**
     * Test setting style with normal value.
     */
    public function testSetGetNormal(): void
    {
        $object = new Table();

        $attributes = [
            'bgColor' => 'FF0000',
            'borderTopSize' => 4,
            'borderTopColor' => 'FF0000',
            'borderLeftSize' => 4,
            'borderLeftColor' => 'FF0000',
            'borderRightSize' => 4,
            'borderRightColor' => 'FF0000',
            'borderBottomSize' => 4,
            'borderBottomColor' => 'FF0000',
            'borderInsideHSize' => 4,
            'borderInsideHColor' => 'FF0000',
            'borderInsideVSize' => 4,
            'borderInsideVColor' => 'FF0000',
            'cellMarginTop' => 240,
            'cellMarginLeft' => 240,
            'cellMarginRight' => 240,
            'cellMarginBottom' => 240,
            'alignment' => JcTable::CENTER,
            'width' => 100,
            'unit' => 'pct',
            'layout' => Table::LAYOUT_FIXED,
        ];
        foreach ($attributes as $key => $value) {
            $set = "set{$key}";
            $get = "get{$key}";
            $object->$set($value);
            self::assertEquals($value, $object->$get());
        }
    }

    public function testBidiVisual(): void
    {
        $object = new Table();
        self::assertNull($object->isBidiVisual());
        self::assertInstanceOf(Table::class, $object->setBidiVisual(true));
        self::assertTrue($object->isBidiVisual());
        self::assertInstanceOf(Table::class, $object->setBidiVisual(false));
        self::assertFalse($object->isBidiVisual());
        self::assertInstanceOf(Table::class, $object->setBidiVisual(null));
        self::assertNull($object->isBidiVisual());
    }

    public function testBidiVisualSettings(): void
    {
        Settings::setDefaultRtl(null);
        $object = new Table();
        self::assertNull($object->isBidiVisual());

        Settings::setDefaultRtl(true);
        $object = new Table();
        self::assertTrue($object->isBidiVisual());

        Settings::setDefaultRtl(false);
        $object = new Table();
        self::assertFalse($object->isBidiVisual());

        Settings::setDefaultRtl(null);
    }

    /**
     * Test border color.
     *
     * Set border color and test if each part has the same color
     * While looping, push values array to be asserted with getBorderColor
     */
    public function testBorderColor(): void
    {
        $object = new Table();
        $parts = ['Top', 'Left', 'Right', 'Bottom', 'InsideH', 'InsideV'];

        $value = 'FF0000';
        $object->setBorderColor($value);
        $values = [];
        foreach ($parts as $part) {
            $get = "getBorder{$part}Color";
            $values[] = $value;
            self::assertEquals($value, $object->$get());
        }
        self::assertEquals($values, $object->getBorderColor());
    }

    /**
     * Test border size.
     *
     * Set border size and test if each part has the same size
     * While looping, push values array to be asserted with getBorderSize
     * Value is in eights of a point, i.e. 4 / 8 = .5pt
     */
    public function testBorderSize(): void
    {
        $object = new Table();
        $parts = ['Top', 'Left', 'Right', 'Bottom', 'InsideH', 'InsideV'];

        $value = 4;
        $object->setBorderSize($value);
        $values = [];
        foreach ($parts as $part) {
            $get = "getBorder{$part}Size";
            $values[] = $value;
            self::assertEquals($value, $object->$get());
        }
        self::assertEquals($values, $object->getBorderSize());
    }

    /**
     * Test cell margin.
     *
     * Set cell margin and test if each part has the same margin
     * While looping, push values array to be asserted with getCellMargin
     * Value is in twips
     */
    public function testCellMargin(): void
    {
        $object = new Table();
        $parts = ['Top', 'Left', 'Right', 'Bottom'];

        $value = 240;
        $object->setCellMargin($value);
        $values = [];
        foreach ($parts as $part) {
            $get = "getCellMargin{$part}";
            $values[] = $value;
            self::assertEquals($value, $object->$get());
        }
        self::assertEquals($values, $object->getCellMargin());
        self::assertTrue($object->hasMargin());
    }

    /**
     * Set style value for various special value types.
     */
    public function testSetStyleValue(): void
    {
        $object = new Table();
        $object->setStyleValue('borderSize', 120);
        $object->setStyleValue('cellMargin', 240);
        $object->setStyleValue('borderColor', '999999');

        self::assertEquals([120, 120, 120, 120, 120, 120], $object->getBorderSize());
        self::assertEquals([240, 240, 240, 240], $object->getCellMargin());
        self::assertEquals(
            ['999999', '999999', '999999', '999999', '999999', '999999'],
            $object->getBorderColor()
        );
    }

    /**
     * Tests table cell spacing.
     */
    public function testTableCellSpacing(): void
    {
        $object = new Table();
        self::assertNull($object->getCellSpacing());

        $object = new Table(['cellSpacing' => 20]);
        self::assertEquals(20, $object->getCellSpacing());
    }

    /**
     * Tests table floating position.
     */
    public function testTablePosition(): void
    {
        $object = new Table();
        self::assertNull($object->getPosition());

        $object->setPosition(['vertAnchor' => TablePosition::VANCHOR_PAGE]);
        self::assertNotNull($object->getPosition());
        self::assertEquals(TablePosition::VANCHOR_PAGE, $object->getPosition()->getVertAnchor());
    }

    public function testIndent(): void
    {
        $indent = new TblWidthComplexType(100, TblWidth::TWIP);

        $table = new Table(['indent' => $indent]);

        self::assertSame($indent, $table->getIndent());
    }
}
