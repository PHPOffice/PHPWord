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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Style;

use PhpOffice\PhpWord\ComplexType\TblWidth as TblWidthComplexType;
use PhpOffice\PhpWord\SimpleType\JcTable;
use PhpOffice\PhpWord\SimpleType\TblWidth;

/**
 * Test class for PhpOffice\PhpWord\Style\Table
 *
 * @runTestsInSeparateProcesses
 */
class TableTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test class construction
     *
     * There are 3 variables for class constructor:
     * - $styleTable: Define table styles
     * - $styleFirstRow: Define style for the first row
     */
    public function testConstruct()
    {
        $styleTable = array('bgColor' => 'FF0000');
        $styleFirstRow = array('borderBottomSize' => 3);

        $object = new Table($styleTable, $styleFirstRow);
        $this->assertEquals('FF0000', $object->getBgColor());

        $firstRow = $object->getFirstRow();
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Table', $firstRow);
        $this->assertEquals(3, $firstRow->getBorderBottomSize());
    }

    /**
     * Test default values when passing no style
     */
    public function testDefaultValues()
    {
        $object = new Table();

        $this->assertNull($object->getBgColor());
        $this->assertEquals(Table::LAYOUT_AUTO, $object->getLayout());
        $this->assertEquals(TblWidth::AUTO, $object->getUnit());
        $this->assertNull($object->getIndent());
    }

    /**
     * Test setting style with normal value
     */
    public function testSetGetNormal()
    {
        $object = new Table();

        $attributes = array(
            'bgColor'            => 'FF0000',
            'borderTopSize'      => 4,
            'borderTopColor'     => 'FF0000',
            'borderLeftSize'     => 4,
            'borderLeftColor'    => 'FF0000',
            'borderRightSize'    => 4,
            'borderRightColor'   => 'FF0000',
            'borderBottomSize'   => 4,
            'borderBottomColor'  => 'FF0000',
            'borderInsideHSize'  => 4,
            'borderInsideHColor' => 'FF0000',
            'borderInsideVSize'  => 4,
            'borderInsideVColor' => 'FF0000',
            'cellMarginTop'      => 240,
            'cellMarginLeft'     => 240,
            'cellMarginRight'    => 240,
            'cellMarginBottom'   => 240,
            'alignment'          => JcTable::CENTER,
            'width'              => 100,
            'unit'               => 'pct',
            'layout'             => Table::LAYOUT_FIXED,
        );
        foreach ($attributes as $key => $value) {
            $set = "set{$key}";
            $get = "get{$key}";
            $object->$set($value);
            $this->assertEquals($value, $object->$get());
        }
    }

    /**
     * Test border color
     *
     * Set border color and test if each part has the same color
     * While looping, push values array to be asserted with getBorderColor
     */
    public function testBorderColor()
    {
        $object = new Table();
        $parts = array('Top', 'Left', 'Right', 'Bottom', 'InsideH', 'InsideV');

        $value = 'FF0000';
        $object->setBorderColor($value);
        $values = array();
        foreach ($parts as $part) {
            $get = "getBorder{$part}Color";
            $values[] = $value;
            $this->assertEquals($value, $object->$get());
        }
        $this->assertEquals($values, $object->getBorderColor());
    }

    /**
     * Test border size
     *
     * Set border size and test if each part has the same size
     * While looping, push values array to be asserted with getBorderSize
     * Value is in eights of a point, i.e. 4 / 8 = .5pt
     */
    public function testBorderSize()
    {
        $object = new Table();
        $parts = array('Top', 'Left', 'Right', 'Bottom', 'InsideH', 'InsideV');

        $value = 4;
        $object->setBorderSize($value);
        $values = array();
        foreach ($parts as $part) {
            $get = "getBorder{$part}Size";
            $values[] = $value;
            $this->assertEquals($value, $object->$get());
        }
        $this->assertEquals($values, $object->getBorderSize());
    }

    /**
     * Test cell margin
     *
     * Set cell margin and test if each part has the same margin
     * While looping, push values array to be asserted with getCellMargin
     * Value is in twips
     */
    public function testCellMargin()
    {
        $object = new Table();
        $parts = array('Top', 'Left', 'Right', 'Bottom');

        $value = 240;
        $object->setCellMargin($value);
        $values = array();
        foreach ($parts as $part) {
            $get = "getCellMargin{$part}";
            $values[] = $value;
            $this->assertEquals($value, $object->$get());
        }
        $this->assertEquals($values, $object->getCellMargin());
        $this->assertTrue($object->hasMargin());
    }

    /**
     * Set style value for various special value types
     */
    public function testSetStyleValue()
    {
        $object = new Table();
        $object->setStyleValue('borderSize', 120);
        $object->setStyleValue('cellMargin', 240);
        $object->setStyleValue('borderColor', '999999');

        $this->assertEquals(array(120, 120, 120, 120, 120, 120), $object->getBorderSize());
        $this->assertEquals(array(240, 240, 240, 240), $object->getCellMargin());
        $this->assertEquals(
            array('999999', '999999', '999999', '999999', '999999', '999999'),
            $object->getBorderColor()
        );
    }

    /**
     * Tests table cell spacing
     */
    public function testTableCellSpacing()
    {
        $object = new Table();
        $this->assertNull($object->getCellSpacing());

        $object = new Table(array('cellSpacing' => 20));
        $this->assertEquals(20, $object->getCellSpacing());
    }

    /**
     * Tests table floating position
     */
    public function testTablePosition()
    {
        $object = new Table();
        $this->assertNull($object->getPosition());

        $object->setPosition(array('vertAnchor' => TablePosition::VANCHOR_PAGE));
        $this->assertNotNull($object->getPosition());
        $this->assertEquals(TablePosition::VANCHOR_PAGE, $object->getPosition()->getVertAnchor());
    }

    public function testIndent()
    {
        $indent = new TblWidthComplexType(100, TblWidth::TWIP);

        $table = new Table(array('indent' => $indent));

        $this->assertSame($indent, $table->getIndent());
    }
}
