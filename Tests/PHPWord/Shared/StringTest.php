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
    /**
     * Test getIsMbstringEnabled() and getIsIconvEnabled()
     */
    public function testGetIsMbstringAndIconvEnabled()
    {
        $features = array(
            'mbstring' => 'mb_convert_encoding',
            'iconv' => 'iconv',
        );
        foreach ($features as $key => $val) {
            $expected = function_exists($val);
            $get = "getIs{$key}Enabled";
            $firstResult = PHPWord_Shared_String::$get();
            $this->assertEquals($expected, $firstResult);
            $secondResult = PHPWord_Shared_String::$get();
            $this->assertEquals($firstResult, $secondResult);
        }
    }

    /**
     * Test FormatNumber()
     */
    public function testFormatNumber()
    {
        $expected = '1022.12';
        $returned = PHPWord_Shared_String::FormatNumber('1022.1234');
        $this->assertEquals($expected, $returned);
    }
}
