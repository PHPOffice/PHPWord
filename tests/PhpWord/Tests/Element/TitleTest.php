<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
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
     * Get style not null
     */
    public function testStyleNotNull()
    {
        $oTitle = new Title('text', 1, 'style');

        $this->assertEquals($oTitle->getStyle(), 'style');
    }

    /**
     * Get anchor
     */
    public function testAnchor()
    {
        $oTitle = new Title('text');

        $iVal = rand(1, 1000);
        $oTitle->setAnchor($iVal);
        $this->assertEquals($oTitle->getAnchor(), $iVal);
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
