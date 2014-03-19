<?php
namespace PHPWord\Tests\Shared;

use PhpOffice\PhpWord\Shared\String;

/**
 * @package                     PHPWord\Tests
 * @coversDefaultClass          PhpOffice\PhpWord\Shared\String
 * @runTestsInSeparateProcesses
 */
class StringTest extends \PHPUnit_Framework_TestCase
{
    public function testIsUTF8()
    {
        $this->assertTrue(String::IsUTF8(''));
        $this->assertTrue(String::IsUTF8('éééé'));
        $this->assertFalse(String::IsUTF8(utf8_decode('éééé')));
    }

    public function testControlCharacterOOXML2PHP()
    {
        $this->assertEquals('', String::ControlCharacterOOXML2PHP(''));
        $this->assertEquals(chr(0x08), String::ControlCharacterOOXML2PHP('_x0008_'));
    }

    public function testControlCharacterPHP2OOXML()
    {
        $this->assertEquals('', String::ControlCharacterPHP2OOXML(''));
        $this->assertEquals('_x0008_', String::ControlCharacterPHP2OOXML(chr(0x08)));
    }
}