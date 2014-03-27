<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests\Section;

use PhpOffice\PhpWord\Section\PageBreak;

/**
 * Test class for PhpOffice\PhpWord\Section\PageBreak
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Section\PageBreak
 * @runTestsInSeparateProcesses
 */
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
