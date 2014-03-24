<?php
namespace PhpOffice\PhpWord\Tests\Style;

use PhpOffice\PhpWord\Style\Image;

/**
 * @runTestsInSeparateProcesses
 */
class ImageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test setting style with normal value
     */
    public function testSetGetNormal()
    {
        $object = new Image();

        $properties = array(
            'width' => 200,
            'height' => 200,
            'align' => 'left',
            'marginTop' => 240,
            'marginLeft' => 240,
            'wrappingStyle' => 'inline',
        );
        foreach ($properties as $key => $value) {
            $set = "set{$key}";
            $get = "get{$key}";
            $object->$set($value);
            $this->assertEquals($value, $object->$get());
        }
    }

    /**
     * Test setStyleValue method
     */
    public function testSetStyleValue()
    {
        $object = new Image();

        $properties = array(
            'width' => 200,
            'height' => 200,
            'align' => 'left',
            'marginTop' => 240,
            'marginLeft' => 240,
        );
        foreach ($properties as $key => $value) {
            $get = "get{$key}";
            $object->setStyleValue("_{$key}", $value);
            $this->assertEquals($value, $object->$get());
        }
    }

    /**
     * Test setWrappingStyle exception
     *
     * @expectedException \InvalidArgumentException
     */
    public function testSetWrappingStyleException()
    {
        $object = new Image();
        $object->setWrappingStyle('foo');
    }
}
