<?php
declare(strict_types=1);
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

use PhpOffice\PhpWord\SimpleType\JcTable;
use PhpOffice\PhpWord\Style\Colors\BasicColor;
use PhpOffice\PhpWord\Style\Colors\Hex;
use PhpOffice\PhpWord\Style\Colors\Rgb;
use PhpOffice\PhpWord\Style\Lengths\Absolute;
use PhpOffice\PhpWord\Style\Lengths\Auto;
use PhpOffice\PhpWord\Style\Lengths\Percent;

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
        $styleTable = array('bgColor' => new Hex('FF0000'));
        $styleFirstRow = array('bordersFromArray' => array(
            'top' => new BorderSide(Absolute::from('eop', 3)),
        ));

        $object = new Table($styleTable, $styleFirstRow);
        $this->assertEquals('FF0000', $object->getBgColor()->toHex());

        $firstRow = $object->getFirstRow();
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Table', $firstRow);
        $this->assertEquals(3, $firstRow->getBorder('top')->getSize()->toInt('eop'));
        $this->assertEquals(0, $firstRow->getBorder('bottom')->getSize()->toInt('eop'));
    }

    /**
     * Test default values when passing no style
     */
    public function testDefaultValues()
    {
        $object = new Table();

        $this->assertNull($object->getBgColor()->toHex());
        $this->assertEquals(Table::LAYOUT_AUTO, $object->getLayout());
        $this->assertInstanceOf(Auto::class, $object->getIndent());
    }

    /**
     * Test setting style with normal value
     */
    public function testSetGetNormal()
    {
        $object = new Table();

        $attributes = array(
            'bgColor'            => new Hex('FF0000'),
            'cellMarginTop'      => Absolute::from('eop', 240),
            'cellMarginLeft'     => Absolute::from('eop', 240),
            'cellMarginRight'    => Absolute::from('eop', 240),
            'cellMarginBottom'   => Absolute::from('eop', 240),
            'alignment'          => JcTable::CENTER,
            'width'              => new Percent(100),
            'layout'             => Table::LAYOUT_FIXED,
        );
        foreach ($attributes as $key => $value) {
            $set = "set{$key}";
            $get = "get{$key}";
            $object->$set($value);
            $result = $object->$get();
            if ($result instanceof Absolute) {
                $result = $result->toInt('eop');
                $value = $value->toInt('eop');
            } elseif ($result instanceof Percent) {
                $result = $result->toInt();
                $value = $value->toInt();
            } elseif ($result instanceof BasicColor) {
                $result = $result->toHex();
                $value = $value->toHex();
            }
            $this->assertEquals($value, $result);
        }
    }

    /**
     * Test borders
     */
    public function testBorders()
    {
        $table = new Table();

        $this->assertFalse($table->hasBorder());
        $borders = array('top', 'bottom', 'start', 'end', 'insideH', 'insideV');
        $borderSides = array(
            array(Absolute::from('pt', rand(1, 20)), new Hex('f93de1'), new BorderStyle('double'), Absolute::from('pt', rand(1, 20)), true),
            array(Absolute::from('twip', rand(1, 400)), new Hex('000000'), new BorderStyle('outset'), Absolute::from('twip', rand(1, 400)), false),
            array(Absolute::from('eop', rand(1, 160)), new Rgb(255, 0, 100), new BorderStyle('dotted'), Absolute::from('eop', rand(1, 160)), true),
        );
        $lastBorderSide = array(new Absolute(0), new Hex(null), new BorderStyle('single'), new Absolute(0), false);
        foreach ($borderSides as $key => $borderSide) {
            $newBorder = new BorderSide(...$borderSide);

            foreach ($borders as $side) {
                $currentBorder = $table->getBorder($side);
                $this->assertEquals($lastBorderSide[0], $currentBorder->getSize(), "Size for border side #$key for side $side should match last border side still");
                $this->assertEquals($lastBorderSide[1], $currentBorder->getColor(), "Color for border side #$key for side $side should match last border side still");
                $this->assertEquals($lastBorderSide[2], $currentBorder->getStyle(), "Style for border side #$key for side $side should match last border side still");
                $this->assertEquals($lastBorderSide[3], $currentBorder->getSpace(), "Space for border side #$key for side $side should match last border side still");
                $this->assertEquals($lastBorderSide[4], $currentBorder->getShadow(), "Shadow for border side #$key for side $side should match last border side still");

                $table->setBorder($side, $newBorder);
                $updatedBorder = $table->getBorder($side);
                $this->assertEquals($borderSide[0], $updatedBorder->getSize(), "Size for border side #$key for side $side should match new border");
                $this->assertEquals($borderSide[1], $updatedBorder->getColor(), "Color for border side #$key for side $side should match new border");
                $this->assertEquals($borderSide[2], $updatedBorder->getStyle(), "Style for border side #$key for side $side should match new border");
                $this->assertEquals($borderSide[3], $updatedBorder->getSpace(), "Space for border side #$key for side $side should match new border");
                $this->assertEquals($borderSide[4], $updatedBorder->getShadow(), "Shadow for border side #$key for side $side should match new border");
            }

            $lastBorderSide = $borderSide;
        }
    }

    /**
     * Test invalid border
     * @depends testBorders
     * @expectedException \PhpOffice\PhpWord\Exception\Exception
     * @expectedExceptionMessage Invalid side `badside` provided
     */
    public function testGetBorderInvalid()
    {
        $table = new Table();
        $table->getBorder('badside');
    }

    /**
     * Test invalid border
     * @depends testBorders
     * @expectedException \PhpOffice\PhpWord\Exception\Exception
     * @expectedExceptionMessage Size must be specified
     */
    public function testSetBorderNullSize()
    {
        $table = new Table();
        $table->getBorder('top')->setSize(new Absolute(null));
    }

    /**
     * Test invalid border
     * @depends testBorders
     * @expectedException \PhpOffice\PhpWord\Exception\Exception
     * @expectedExceptionMessage Space must be specified
     */
    public function testSetBorderNullSpace()
    {
        $table = new Table();
        $table->getBorder('top')->setSpace(new Absolute(null));
    }

    /**
     * Test invalid border
     * @depends testBorders
     * @expectedException \PhpOffice\PhpWord\Exception\Exception
     * @expectedExceptionMessage Invalid side `badside` provided
     */
    public function testSetBorderInvalid()
    {
        $table = new Table();
        $table->setBorder('badside', new BorderSide());
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
        $object->setCellMargin(Absolute::from('twip', $value));
        $values = array();
        foreach ($parts as $part) {
            $get = "getCellMargin{$part}";
            $values[] = $value;
            $this->assertEquals($value, $object->$get()->toInt('twip'));
        }
        $this->assertEquals($values, array_map(function ($value) {
            return $value->toInt('twip');
        }, $object->getCellMargin()));
        $this->assertTrue($object->hasMargin());
    }

    /**
     * Set style value for various special value types
     */
    public function testSetStyleValue()
    {
        $object = new Table();
        $object->setStyleValue('bordersFromArray', array(
            'top'     => new BorderSide(Absolute::from('twip', 120)),
            'bottom'  => new BorderSide(Absolute::from('twip', 120)),
            'start'   => new BorderSide(Absolute::from('twip', 120)),
            'end'     => new BorderSide(Absolute::from('twip', 120)),
            'insideH' => new BorderSide(Absolute::from('twip', 120)),
            'insideV' => new BorderSide(Absolute::from('twip', 120)),
        ));
        $object->setStyleValue('cellMargin', Absolute::from('twip', 240));

        $this->assertEquals(array('top' => 120, 'bottom' => 120, 'insideH' => 120, 'insideV' => 120, 'start' => 120, 'end' => 120), array_map(function ($value) {
            return $value->getSize()->toInt('twip');
        }, $object->getBorders()));
        $this->assertEquals(array(240, 240, 240, 240), array_map(function ($value) {
            return $value->toInt('twip');
        }, $object->getCellMargin()));
    }

    /**
     * Tests table cell spacing
     */
    public function testTableCellSpacing()
    {
        $object = new Table();
        $this->assertNull($object->getCellSpacing()->toInt('twip'));

        $object = new Table(array('cellSpacing' => Absolute::from('twip', 20)));
        $this->assertEquals(20, $object->getCellSpacing()->toInt('twip'));
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
        $indent = Absolute::from('twip', 100);

        $table = new Table(array('indent' => $indent));

        $this->assertSame($indent, $table->getIndent());
    }
}
