<?php
namespace PHPWord\Tests\Section;

use PHPWord_Section_TextBreak;
use PHPWord_Style_Paragraph;
use PHPWord_Style_Font;

/**
 * @package             PHPWord\Tests
 * @coversDefaultClass  PHPWord_Section_TextBreak
 * @runTestsInSeparateProcesses
 */
class TextBreakTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Construct with empty value
     */
    public function testConstruct()
    {
        $object = new PHPWord_Section_TextBreak();
        $this->assertNull($object->getFontStyle());
        $this->assertNull($object->getParagraphStyle());
    }

    /**
     * Construct with style object
     */
    public function testConstructWithStyleObject()
    {
        $fStyle = new PHPWord_Style_Font();
        $pStyle = new PHPWord_Style_Paragraph();
        $object = new PHPWord_Section_TextBreak($fStyle, $pStyle);
        $this->assertEquals($fStyle, $object->getFontStyle());
        $this->assertEquals($pStyle, $object->getParagraphStyle());
    }

    /**
     * Construct with style array
     */
    public function testConstructWithStyleArray()
    {
        $fStyle = array('size' => 12);
        $pStyle = array('spacing' => 240);
        $object = new PHPWord_Section_TextBreak($fStyle, $pStyle);
        $this->assertInstanceOf('PHPWord_Style_Font', $object->getFontStyle());
        $this->assertInstanceOf('PHPWord_Style_Paragraph', $object->getParagraphStyle());
    }

    /**
     * Construct with style name
     */
    public function testConstructWithStyleName()
    {
        $fStyle = 'fStyle';
        $pStyle = 'pStyle';
        $object = new PHPWord_Section_TextBreak($fStyle, $pStyle);
        $this->assertEquals($fStyle, $object->getFontStyle());
        $this->assertEquals($pStyle, $object->getParagraphStyle());
    }
}
