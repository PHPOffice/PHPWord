<?php
namespace PHPWord\Tests\Shared;

use PHPWord_Shared_String;

/**
 * Class StringTest
 *
 * @package PHPWord\Tests
 * @runTestsInSeparateProcesses
 */
class StringTest extends \PHPUnit_Framework_TestCase
{
    public function testIsUTF8()
    {
      $this->assertTrue(PHPWord_Shared_String::IsUTF8(''));
      $this->assertTrue(PHPWord_Shared_String::IsUTF8('éééé'));
      $this->assertFalse(PHPWord_Shared_String::IsUTF8(utf8_decode('éééé')));
    }

  public function testControlCharacterOOXML2PHP()
  {
    $this->assertEquals('', PHPWord_Shared_String::ControlCharacterOOXML2PHP(''));
    $this->assertEquals(chr(0x08), PHPWord_Shared_String::ControlCharacterOOXML2PHP('_x0008_'));
  }

  public function testControlCharacterPHP2OOXML()
  {
    $this->assertEquals('', PHPWord_Shared_String::ControlCharacterPHP2OOXML(''));
    $this->assertEquals('_x0008_', PHPWord_Shared_String::ControlCharacterPHP2OOXML(chr(0x08)));
  }
}
