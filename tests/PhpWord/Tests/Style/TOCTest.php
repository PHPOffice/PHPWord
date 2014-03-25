<?php
namespace PhpOffice\PhpWord\Tests\Style;

use PhpOffice\PhpWord\Style\TOC;

/**
 * @coversDefaultClass          \PhpOffice\PhpWord\Style\TOC
 * @runTestsInSeparateProcesses
 */
class TOCTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test properties with normal value
     */
    public function testProperties()
    {
        $object = new TOC();

        $properties = array(
            'tabPos'    => 9062,
            'tabLeader' => TOC::TABLEADER_DOT,
            'indent'    => 200,
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
