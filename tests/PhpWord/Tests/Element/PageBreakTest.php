<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests\Element;

use PhpOffice\PhpWord\Element\PageBreak;

/**
 * Test class for PhpOffice\PhpWord\Element\PageBreak
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Element\PageBreak
 * @runTestsInSeparateProcesses
 */
class PageBreakTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Executed before each method of the class
     */
    public function testConstruct()
    {
        $oPageBreak = new PageBreak();

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\PageBreak', $oPageBreak);
    }
}
