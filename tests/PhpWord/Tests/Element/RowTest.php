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
        $oRow = new Row();

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
        $oRow = new Row($iVal, array('borderBottomSize' => 18, 'borderBottomColor' => '0000FF', 'bgColor' => '66BBFF'));

        $this->assertEquals($oRow->getHeight(), $iVal);
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Row', $oRow->getStyle());
    }

    /**
     * Add cell
     */
    public function testAddCell()
    {
        $oRow = new Row();
        $element = $oRow->addCell();

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Cell', $element);
        $this->assertCount(1, $oRow->getCells());
    }
}
