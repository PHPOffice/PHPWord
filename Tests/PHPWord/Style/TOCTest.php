<?php
namespace PHPWord\Tests\Style;

use PHPWord_Style_TOC;

/**
 * Class TOCTest
 *
 * @package PHPWord\Tests
 * @covers  PHPWord_Style_TOC
 * @runTestsInSeparateProcesses
 */
class TOCTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test properties with normal value
     */
    public function testProperties()
    {
        $object = new PHPWord_Style_TOC();

        $properties = array(
            'tabPos' => 9062,
            'tabLeader' => PHPWord_Style_TOC::TABLEADER_DOT,
            'indent' => 200,
        );
        foreach ($properties as $key => $value) {
            // set/get
            $set = "set{$key}";
            $get = "get{$key}";
            $object->$set($value);
            $this->assertEquals($value, $object->$get());

            // setStyleValue
            $object->setStyleValue("_{$key}", null);
            $this->assertEquals(null, $object->$get());
        }
    }
}
