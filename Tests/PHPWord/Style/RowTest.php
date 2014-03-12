<?php
namespace PHPWord\Tests\Style;

use PHPWord_Style_Row;

/**
 * Class RowTest
 *
 * @package PHPWord\Tests
 * @runTestsInSeparateProcesses
 */
class RowTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test properties with normal value
     */
    public function testProperties()
    {
        $object = new PHPWord_Style_Row();

        $properties = array(
            'tblHeader' => true,
            'cantSplit' => false,
        );
        foreach ($properties as $key => $value) {
            // set/get
            $set = "set{$key}";
            $get = "get{$key}";
            $expected = $value ? 1 : 0;
            $object->$set($value);
            $this->assertEquals($expected, $object->$get());

            // setStyleValue
            $value = !$value;
            $expected = $value ? 1 : 0;
            $object->setStyleValue("_{$key}", $value);
            $this->assertEquals($expected, $object->$get());
        }
    }
}
