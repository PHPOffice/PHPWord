<?php
namespace PHPWord\Tests\Style;

use PHPWord_Style_Cell;

/**
 * Class CellTest
 *
 * @package PHPWord\Tests
 * @runTestsInSeparateProcesses
 */
class CellTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test setting style with normal value
     */
    public function testSetGetNormal()
    {
        $object = new PHPWord_Style_Cell();

        $attributes = array(
            'valign' => 'left',
            'textDirection' => PHPWord_Style_Cell::TEXT_DIR_BTLR,
            'bgColor' => 'FFFF00',
            'borderTopSize' => 120,
            'borderTopColor' => 'FFFF00',
            'borderLeftSize' => 120,
            'borderLeftColor' => 'FFFF00',
            'borderRightSize' => 120,
            'borderRightColor' => 'FFFF00',
            'borderBottomSize' => 120,
            'borderBottomColor' => 'FFFF00',
            'gridSpan' => 2,
            'vMerge' => 2,
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
     */
    public function testBorderColor()
    {
        $object = new PHPWord_Style_Cell();

        $default = '000000';
        $value = 'FF0000';

        $this->assertEquals($default, $object->getDefaultBorderColor());

        $object->setStyleValue('_defaultBorderColor', $value);
        $this->assertEquals($value, $object->getDefaultBorderColor());

        $object->setStyleValue('_borderColor', $value);
        $expected = array($value, $value, $value, $value);
        $this->assertEquals($expected, $object->getBorderColor());
    }

    /**
     * Test border size
     */
    public function testBorderSize()
    {
        $object = new PHPWord_Style_Cell();

        $value = 120;
        $expected = array($value, $value, $value, $value);
        $object->setStyleValue('_borderSize', $value);
        $this->assertEquals($expected, $object->getBorderSize());
    }
}
