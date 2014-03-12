<?php
namespace PHPWord\Tests\Section;

use PHPWord_Section_TextBreak;

class TextBreakTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Executed before each method of the class
     */
    public function testConstruct()
    {
        // Section Settings
        $oTextBreak = new PHPWord_Section_TextBreak();

        $this->assertInstanceOf('PHPWord_Section_TextBreak', $oTextBreak);
    }
}
