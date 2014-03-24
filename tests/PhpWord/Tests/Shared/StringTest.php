<?php
namespace PhpOffice\PhpWord\Tests\Shared;

use PhpOffice\PhpWord\Shared\String;

/**
 * @coversDefaultClass          \PhpOffice\PhpWord\Shared\String
 * @runTestsInSeparateProcesses
 */
class StringTest extends \PHPUnit_Framework_TestCase
{
    public function testIsUTF8()
    {
        $this->assertTrue(String::isUTF8(''));
        $this->assertTrue(String::isUTF8('éééé'));
        $this->assertFalse(String::isUTF8(utf8_decode('éééé')));
    }

    public function testControlCharacterOOXML2PHP()
    {
        $this->assertEquals('', String::controlCharacterOOXML2PHP(''));
        $this->assertEquals(chr(0x08), String::controlCharacterOOXML2PHP('_x0008_'));
    }

    public function testControlCharacterPHP2OOXML()
    {
        $this->assertEquals('', String::controlCharacterPHP2OOXML(''));
        $this->assertEquals('_x0008_', String::controlCharacterPHP2OOXML(chr(0x08)));
    }
}
