<?php
namespace PhpOffice\PhpWord\Tests\Section;

use PhpOffice\PhpWord\Section\PageBreak;

class PageBreakTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Executed before each method of the class
     */
    public function testConstruct()
    {
        // Section Settings
        $oPageBreak = new PageBreak();

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\PageBreak', $oPageBreak);
    }
}
