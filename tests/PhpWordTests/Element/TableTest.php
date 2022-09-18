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
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWordTests\Element;

use PhpOffice\PhpWord\Element\Table;

/**
 * Test class for PhpOffice\PhpWord\Element\Table.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Element\Table
 *
 * @runTestsInSeparateProcesses
 */
class TableTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Create new instance.
     */
    public function testConstruct(): void
    {
        $oTable = new Table();

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Table', $oTable);
        self::assertNull($oTable->getStyle());
        self::assertNull($oTable->getWidth());
        self::assertEquals([], $oTable->getRows());
        self::assertCount(0, $oTable->getRows());
    }

    /**
     * Get style name.
     */
    public function testStyleText(): void
    {
        $oTable = new Table('tableStyle');

        self::assertEquals('tableStyle', $oTable->getStyle());
    }

    /**
     * Get style array.
     */
    public function testStyleArray(): void
    {
        $oTable = new Table(['borderSize' => 6, 'borderColor' => '006699', 'cellMargin' => 80]);

        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Table', $oTable->getStyle());
    }

    /**
     * Set/get width.
     */
    public function testWidth(): void
    {
        $oTable = new Table();
        $iVal = mt_rand(1, 1000);
        $oTable->setWidth($iVal);
        self::assertEquals($iVal, $oTable->getWidth());
    }

    /**
     * Add/get row.
     */
    public function testRow(): void
    {
        $oTable = new Table();
        $element = $oTable->addRow();
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Row', $element);
        self::assertCount(1, $oTable->getRows());
    }

    /**
     * Add cell.
     */
    public function testCell(): void
    {
        $oTable = new Table();
        $oTable->addRow();
        $element = $oTable->addCell();
        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Cell', $element);
    }

    /**
     * Add cell.
     */
    public function testCountColumns(): void
    {
        $oTable = new Table();
        $oTable->addRow();
        $oTable->addCell();
        self::assertEquals($oTable->countColumns(), 1);
        $oTable->addCell();
        $oTable->addCell();
        self::assertEquals($oTable->countColumns(), 3);
    }
}
