<?php
namespace PHPWord\Tests;

use PHPUnit_Framework_TestCase;
use PHPWord_Style_Cell;

/**
 * Class PHPWord_Style_CellTest
 *
 * @package PHPWord\Tests
 * @runTestsInSeparateProcesses
 */
class PHPWord_Style_CellTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test setting style with normal value
     */
    public function testSetGetNormal()
    {
        $object = new PHPWord_Style_Cell();

        $attributes = array(
            'valign' => null,
            'textDirection' => null,
            'bgColor' => null,
            'borderTopSize' => null,
            'borderTopColor' => null,
            'borderLeftSize' => null,
            'borderLeftColor' => null,
            'borderRightSize' => null,
            'borderRightColor' => null,
            'borderBottomSize' => null,
            'borderBottomColor' => null,
            'gridSpan' => null,
            'vMerge' => null,
        );
            //'defaultBorderColor' => null,
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
