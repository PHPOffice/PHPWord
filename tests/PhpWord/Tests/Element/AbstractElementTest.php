<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Tests\Element;

/**
 * Test class for PhpOffice\PhpWord\Element\AbstractElement
 *
 * @runTestsInSeparateProcesses
 */
class AbstractElementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test set/get element index
     */
    public function testElementIndex()
    {
        $stub = $this->getMockForAbstractClass('\PhpOffice\PhpWord\Element\AbstractElement');
        $ival = rand(0, 100);
        $stub->setElementIndex($ival);
        $this->assertEquals($stub->getElementIndex(), $ival);
    }

    /**
     * Test set/get element unique Id
     */
    public function testElementId()
    {
        $stub = $this->getMockForAbstractClass('\PhpOffice\PhpWord\Element\AbstractElement');
        $stub->setElementId();
        $this->assertEquals(strlen($stub->getElementId()), 6);
    }
}
