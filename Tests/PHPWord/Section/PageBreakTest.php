<?php
namespace PHPWord\Tests\Section;

use PHPWord_Section_PageBreak;

class PageBreakTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Executed before each method of the class
     */
    public function testConstruct()
    {
        // Section Settings
        $oPageBreak = new PHPWord_Section_PageBreak();

        $this->assertInstanceOf('PHPWord_Section_PageBreak', $oPageBreak);
    }
}
