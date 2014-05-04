<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Tests\Element;

use PhpOffice\PhpWord\Element\Title;

/**
 * Test class for PhpOffice\PhpWord\Element\Title
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Element\Title
 * @runTestsInSeparateProcesses
 */
class TitleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Create new instance
     */
    public function testConstruct()
    {
        $oTitle = new Title('text');

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Title', $oTitle);
        $this->assertEquals($oTitle->getText(), 'text');
    }

    /**
     * Get style null
     */
    public function testStyleNull()
    {
        $oTitle = new Title('text');

        $this->assertEquals($oTitle->getStyle(), null);
    }

    /**
     * Get bookmark Id
     */
    public function testBookmarkID()
    {
        $oTitle = new Title('text');

        $iVal = rand(1, 1000);
        $oTitle->setBookmarkId($iVal);
        $this->assertEquals($oTitle->getBookmarkId(), $iVal);
    }
}
