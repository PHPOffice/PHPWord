<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests\Shared;

use PhpOffice\PhpWord\Shared\String;

/**
 * Test class for PhpOffice\PhpWord\Shared\String
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Shared\String
 * @runTestsInSeparateProcesses
 */
class StringTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Is UTF8
     */
    public function testIsUTF8()
    {
        $this->assertTrue(String::isUTF8(''));
        $this->assertTrue(String::isUTF8('éééé'));
        $this->assertFalse(String::isUTF8(utf8_decode('éééé')));
    }

    /**
     * OOXML to PHP control character
     */
    public function testControlCharacterOOXML2PHP()
    {
        $this->assertEquals('', String::controlCharacterOOXML2PHP(''));
        $this->assertEquals(chr(0x08), String::controlCharacterOOXML2PHP('_x0008_'));
    }

    /**
     * PHP to OOXML control character
     */
    public function testControlCharacterPHP2OOXML()
    {
        $this->assertEquals('', String::controlCharacterPHP2OOXML(''));
        $this->assertEquals('_x0008_', String::controlCharacterPHP2OOXML(chr(0x08)));
    }
}
