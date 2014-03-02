<?php
namespace PHPWord\Tests;

use PHPUnit_Framework_TestCase;
use PHPWord;
use PHPWord_Shared_Font;

/**
 * Class PHPWord_Writer_Shared_FontTest
 *
 * @package PHPWord\Tests
 * @runTestsInSeparateProcesses
 */
class PHPWord_Writer_Shared_FontTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test various conversions
     */
    public function testConversions()
    {
        $PHPWord = new PHPWord();

        $original = 1;

        $result = PHPWord_Shared_Font::fontSizeToPixels($original);
        $this->assertEquals($original * 16 / 12, $result);

        $result = PHPWord_Shared_Font::inchSizeToPixels($original);
        $this->assertEquals($original * 96, $result);

        $result = PHPWord_Shared_Font::centimeterSizeToPixels($original);
        $this->assertEquals($original * 37.795275591, $result);

        $result = PHPWord_Shared_Font::centimeterSizeToTwips($original);
        $this->assertEquals($original * 565.217, $result);

        $result = PHPWord_Shared_Font::inchSizeToTwips($original);
        $this->assertEquals($original * 565.217 * 2.54, $result);

        $result = PHPWord_Shared_Font::pixelSizeToTwips($original);
        $this->assertEquals($original * 565.217 / 37.795275591, $result);

        $result = PHPWord_Shared_Font::pointSizeToTwips($original);
        $this->assertEquals($original * 20, $result);
    }
}