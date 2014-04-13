<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests\Element;

use PhpOffice\PhpWord\Element\Table;

/**
 * Test class for PhpOffice\PhpWord\Element\Table
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Element\Table
 * @runTestsInSeparateProcesses
 */
class TableTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Create new instance
     */
    public function testConstruct()
    {
        $oTable = new Table('section', 1);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Table', $oTable);
        $this->assertEquals($oTable->getStyle(), null);
        $this->assertEquals($oTable->getWidth(), null);
        $this->assertEquals($oTable->getRows(), array());
        $this->assertCount(0, $oTable->getRows());
    }

    /**
     * Get style name
     */
    public function testStyleText()
    {
        $oTable = new Table('section', 1, 'tableStyle');

        $this->assertEquals($oTable->getStyle(), 'tableStyle');
    }

    /**
     * Get style array
     */
    public function testStyleArray()
    {
        $oTable = new Table('section', 1, array(
            'borderSize' => 6,
            'borderColor' => '006699',
            'cellMargin' => 80
        ));

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Table', $oTable->getStyle());
    }

    /**
     * Set/get width
     */
    public function testWidth()
    {
        $oTable = new Table('section', 1);
        $iVal   = rand(1, 1000);
        $oTable->setWidth($iVal);
        $this->assertEquals($oTable->getWidth(), $iVal);
    }

    /**
     * Add/get row
     */
    public function testRow()
    {
        $oTable  = new Table('section', 1);
        $element = $oTable->addRow();
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Row', $element);
        $this->assertCount(1, $oTable->getRows());
    }

    /**
     * Add cell
     */
    public function testCell()
    {
        $oTable = new Table('section', 1);
        $oTable->addRow();
        $element = $oTable->addCell();
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Cell', $element);
    }

    /**
     * Add cell
     */
    public function testCountColumns()
    {
        $oTable = new Table('section', 1);
        $oTable->addRow();
        $element = $oTable->addCell();
        $this->assertEquals($oTable->countColumns(), 1);
        $element = $oTable->addCell();
        $element = $oTable->addCell();
        $this->assertEquals($oTable->countColumns(), 3);
    }
}
