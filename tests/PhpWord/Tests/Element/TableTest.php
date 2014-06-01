<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
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
        $oTable = new Table();

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
        $oTable = new Table('tableStyle');

        $this->assertEquals($oTable->getStyle(), 'tableStyle');
    }

    /**
     * Get style array
     */
    public function testStyleArray()
    {
        $oTable = new Table(array(
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
        $oTable = new Table();
        $iVal   = rand(1, 1000);
        $oTable->setWidth($iVal);
        $this->assertEquals($oTable->getWidth(), $iVal);
    }

    /**
     * Add/get row
     */
    public function testRow()
    {
        $oTable  = new Table();
        $element = $oTable->addRow();
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Row', $element);
        $this->assertCount(1, $oTable->getRows());
    }

    /**
     * Add cell
     */
    public function testCell()
    {
        $oTable = new Table();
        $oTable->addRow();
        $element = $oTable->addCell();
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Cell', $element);
    }

    /**
     * Add cell
     */
    public function testCountColumns()
    {
        $oTable = new Table();
        $oTable->addRow();
        $element = $oTable->addCell();
        $this->assertEquals($oTable->countColumns(), 1);
        $element = $oTable->addCell();
        $element = $oTable->addCell();
        $this->assertEquals($oTable->countColumns(), 3);
    }
}
