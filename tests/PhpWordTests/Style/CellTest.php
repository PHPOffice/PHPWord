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

namespace PhpOffice\PhpWordTests\Style;

use PhpOffice\PhpWord\SimpleType\VerticalJc;
use PhpOffice\PhpWord\Style\Cell;

/**
 * Test class for PhpOffice\PhpWord\Style\Cell.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Cell
 *
 * @runTestsInSeparateProcesses
 */
class CellTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test setting style with normal value.
     */
    public function testSetGetNormal(): void
    {
        $object = new Cell();

        $attributes = [
            'valign' => VerticalJc::TOP,
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
        ];
        foreach ($attributes as $key => $value) {
            $set = "set{$key}";
            $get = "get{$key}";

            self::assertNull($object->$get()); // Init with null value

            $object->$set($value);

            self::assertEquals($value, $object->$get());
        }
    }

    /**
     * Test border color.
     */
    public function testBorderColor(): void
    {
        $object = new Cell();

        $value = 'FF0000';

        $object->setStyleValue('borderColor', $value);
        $expected = [$value, $value, $value, $value];
        self::assertEquals($expected, $object->getBorderColor());
    }

    /**
     * Test border size.
     */
    public function testBorderSize(): void
    {
        $object = new Cell();

        $value = 120;
        $expected = [$value, $value, $value, $value];
        $object->setStyleValue('borderSize', $value);
        self::assertEquals($expected, $object->getBorderSize());
    }
}
