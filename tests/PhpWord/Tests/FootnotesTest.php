<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests;

use PhpOffice\PhpWord\Footnotes;

/**
 * Test class for PhpOffice\PhpWord\Footnotes
 *
 * @runTestsInSeparateProcesses
 */
class FootnotesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test footnote collection
     */
    public function testFootnotes()
    {
        $footnote1 = new \PhpOffice\PhpWord\Element\Footnote();
        $footnote2 = new \PhpOffice\PhpWord\Element\Footnote();
        $rId = Footnotes::addElement($footnote1);
        Footnotes::setElement(1, $footnote2);

        $this->assertEquals(1, $rId);
        $this->assertEquals(1, count(Footnotes::getElements()));
        $this->assertEquals($footnote2, Footnotes::getElement(1));
        $this->assertNull(Footnotes::getElement(2));

        Footnotes::resetElements();
        $this->assertEquals(0, Footnotes::countElements());
    }
}
