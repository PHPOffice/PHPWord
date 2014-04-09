<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests;

use PhpOffice\PhpWord\Footnote;

/**
 * Test class for PhpOffice\PhpWord\Footnote
 *
 * @runTestsInSeparateProcesses
 */
class FootnoteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test add, get, and count footnote elements and links
     */
    public function testFootnote()
    {
        $footnote1 = new \PhpOffice\PhpWord\Element\Footnote('default');
        $footnote2 = new \PhpOffice\PhpWord\Element\Footnote('first');
        $rId = Footnote::addElement($footnote1);
        Footnote::setElement(1, $footnote2);

        $this->assertEquals(1, $rId);
        $this->assertEquals(1, count(Footnote::getElements()));
        $this->assertEquals($footnote2, Footnote::getElement(1));
        $this->assertNull(Footnote::getElement(2));

        Footnote::resetElements();
        $this->assertEquals(0, Footnote::countElements());
    }
}
