<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests\Element;

use PhpOffice\PhpWord\Element\Row;

/**
 * Test class for PhpOffice\PhpWord\Element\Row
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Element\Row
 * @runTestsInSeparateProcesses
 */
class RowTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Create new instance
     */
    public function testConstruct()
    {
        $iVal = rand(1, 1000);
        $oRow = new Row('section', $iVal);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Row', $oRow);
        $this->assertEquals($oRow->getHeight(), null);
        $this->assertInternalType('array', $oRow->getCells());
        $this->assertCount(0, $oRow->getCells());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Row', $oRow->getStyle());
    }

    /**
     * Create new instance with parameters
     */
    public function testConstructWithParams()
    {
        $iVal = rand(1, 1000);
        $iVal2 = rand(1, 1000);
        $oRow = new Row(
            'section',
            $iVal,
            $iVal2,
            array('borderBottomSize' => 18, 'borderBottomColor' => '0000FF', 'bgColor' => '66BBFF')
        );

        $this->assertEquals($oRow->getHeight(), $iVal2);
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Row', $oRow->getStyle());
    }

    /**
     * Add cell
     */
    public function testAddCell()
    {
        $oRow = new Row('section', 1);
        $element = $oRow->addCell();

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Cell', $element);
        $this->assertCount(1, $oRow->getCells());
    }
}
