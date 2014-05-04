<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests;

use PhpOffice\PhpWord\Endnotes;

/**
 * Test class for PhpOffice\PhpWord\Endnotes
 *
 * @runTestsInSeparateProcesses
 */
class EndnotesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test endnote collection
     */
    public function testEndnotes()
    {
        $endnote1 = new \PhpOffice\PhpWord\Element\Endnote();
        $endnote2 = new \PhpOffice\PhpWord\Element\Endnote();
        $rId = Endnotes::addElement($endnote1);
        Endnotes::setElement(1, $endnote2);

        $this->assertEquals(1, $rId);
        $this->assertEquals(1, count(Endnotes::getElements()));
        $this->assertEquals($endnote2, Endnotes::getElement(1));
        $this->assertNull(Endnotes::getElement(2));

        Endnotes::resetElements();
        $this->assertEquals(0, Endnotes::countElements());
    }
}
