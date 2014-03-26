<?php
namespace PhpOffice\PhpWord\Tests\Style;

use PhpOffice\PhpWord\Style\Table;

/**
 * @runTestsInSeparateProcesses
 */
class TableTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test class construction
     *
     * There are 3 variables for class constructor:
     * - $styleTable: Define table styles
     * - $styleFirstRow: Define style for the first row
     * - $styleLastRow: Define style for the last row (reserved)
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
     * Test setting style with normal value
     */
    public function testSetGetNormal()
    {
        $object = new Table();

        $attributes = array(
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
        foreach ($parts as $part) {
            $get = "getCellMargin{$part}";
            $values[] = $value;
            $this->assertEquals($value, $object->$get());
        }
        $this->assertEquals($values, $object->getCellMargin());
    }
}
