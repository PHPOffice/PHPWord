<?php
namespace PhpOffice\PhpWord\Tests\Section;

use PhpOffice\PhpWord\Section\TextBreak;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;

/**
 * @coversDefaultClass          \PhpOffice\PhpWord\Section\TextBreak
 * @runTestsInSeparateProcesses
 */
class TextBreakTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Construct with empty value
     */
    public function testConstruct()
    {
        $object = new TextBreak();
        $this->assertNull($object->getFontStyle());
        $this->assertNull($object->getParagraphStyle());
    }

    /**
     * Construct with style object
     */
    public function testConstructWithStyleObject()
    {
        $fStyle = new Font();
        $pStyle = new Paragraph();
        $object = new TextBreak($fStyle, $pStyle);
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
        $object = new TextBreak($fStyle, $pStyle);
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Font', $object->getFontStyle());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $object->getParagraphStyle());
    }

    /**
     * Construct with style name
     */
    public function testConstructWithStyleName()
    {
        $fStyle = 'fStyle';
        $pStyle = 'pStyle';
        $object = new TextBreak($fStyle, $pStyle);
        $this->assertEquals($fStyle, $object->getFontStyle());
        $this->assertEquals($pStyle, $object->getParagraphStyle());
    }
}
