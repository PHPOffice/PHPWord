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

use PhpOffice\PhpWord\Element\Row;

/**
 * Test class for PhpOffice\PhpWord\Element\Row.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Element\Row
 *
 * @runTestsInSeparateProcesses
 */
class RowTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Create new instance.
     */
    public function testConstruct(): void
    {
        $oRow = new Row();

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Row', $oRow);
        self::assertNull($oRow->getHeight());
        self::assertIsArray($oRow->getCells());
        self::assertCount(0, $oRow->getCells());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Row', $oRow->getStyle());
    }

    /**
     * Create new instance with parameters.
     */
    public function testConstructWithParams(): void
    {
        $iVal = mt_rand(1, 1000);
        $oRow = new Row($iVal, ['borderBottomSize' => 18, 'borderBottomColor' => '0000FF', 'bgColor' => '66BBFF']);

        self::assertEquals($iVal, $oRow->getHeight());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Row', $oRow->getStyle());
    }

    /**
     * Add cell.
     */
    public function testAddCell(): void
    {
        $oRow = new Row();
        $element = $oRow->addCell();

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Cell', $element);
        self::assertCount(1, $oRow->getCells());
    }
}
