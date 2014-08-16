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

namespace PhpOffice\PhpWord\Tests\Style;

use PhpOffice\PhpWord\Style\Cell;

/**
 * Test class for PhpOffice\PhpWord\Style\Cell
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Cell
 * @runTestsInSeparateProcesses
 */
class CellTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test setting style with normal value
     */
    public function testSetGetNormal()
    {
        $object = new Cell();

        $attributes = array(
            'valign' => Cell::VALIGN_TOP,
            'textDirection' => Cell::TEXT_DIR_BTLR,
            'bgColor' => 'FFFF00',
            'borderTopSize' => 120,
            'borderTopColor' => 'FFFF00',
            'borderLeftSize' => 120,
            'borderLeftColor' => 'FFFF00',
            'borderRightSize' => 120,
            'borderRightColor' => 'FFFF00',
            'borderBottomSize' => 120,
            'borderBottomColor' => 'FFFF00',
            'gridSpan' => 2,
            'vMerge' => Cell::VMERGE_RESTART,
        );
        foreach ($attributes as $key => $value) {
            $set = "set{$key}";
            $get = "get{$key}";

            $this->assertNull($object->$get()); // Init with null value

            $object->$set($value);

            $this->assertEquals($value, $object->$get());
        }
    }

    /**
     * Test border color
     */
    public function testBorderColor()
    {
        $object = new Cell();

        $value = 'FF0000';

        $object->setStyleValue('borderColor', $value);
        $expected = array($value, $value, $value, $value);
        $this->assertEquals($expected, $object->getBorderColor());
    }

    /**
     * Test border size
     */
    public function testBorderSize()
    {
        $object = new Cell();

        $value = 120;
        $expected = array($value, $value, $value, $value);
        $object->setStyleValue('borderSize', $value);
        $this->assertEquals($expected, $object->getBorderSize());
    }
}
