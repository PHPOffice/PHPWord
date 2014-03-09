<?php
namespace PHPWord\Tests;

use PHPUnit_Framework_TestCase;
use PHPWord;
use PHPWord_Style_Font;

/**
 * Class PHPWord_Style_FontTest
 *
 * @package PHPWord\Tests
 * @runTestsInSeparateProcesses
 */
class PHPWord_Style_FontTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test initiation for style type and paragraph style
     */
    public function testInitiation()
    {
        $object = new PHPWord_Style_Font('text', array('align' => 'both'));

        $this->assertEquals('text', $object->getStyleType());
        $this->assertInstanceOf('PHPWord_Style_Paragraph', $object->getParagraphStyle());
    }

    /**
     * Test setting style values with null or empty value
     */
    public function testSetStyleValueWithNullOrEmpty()
    {
        $object = new PHPWord_Style_Font();

        $attributes = array(
            'name' => PHPWord::DEFAULT_FONT_NAME,
            'size' => PHPWord::DEFAULT_FONT_SIZE,
            'bold' => false,
            'italic' => false,
            'superScript' => false,
            'subScript' => false,
            'underline' => PHPWord_Style_Font::UNDERLINE_NONE,
            'strikethrough' => false,
            'color' => PHPWord::DEFAULT_FONT_COLOR,
            'fgColor' => null,
        );
        foreach ($attributes as $key => $default) {
            $method = 'get' . ucwords($key);
            $object->setStyleValue("_$key", null);
            $this->assertEquals($default, $object->$method());
            $object->setStyleValue("_$key", '');
            $this->assertEquals($default, $object->$method());
        }
    }

    /**
     * Test setting style values with normal value
     */
    public function testSetStyleValueNormal()
    {
        $object = new PHPWord_Style_Font();

        $attributes = array(
            'name' => 'Times New Roman',
            'size' => 9,
            'bold' => true,
            'italic' => true,
            'superScript' => true,
            'subScript' => true,
            'underline' => PHPWord_Style_Font::UNDERLINE_HEAVY,
            'strikethrough' => true,
            'color' => '999999',
            'fgColor' => '999999',
        );
        foreach ($attributes as $key => $value) {
            $method = 'get' . ucwords($key);
            $object->setStyleValue("_$key", $value);
            $this->assertEquals($value, $object->$method());
        }
    }

}
