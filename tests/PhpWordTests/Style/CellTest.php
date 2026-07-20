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
    public function testSetGetNormalInt(): void
    {
        $object = new Cell();

        foreach ([
            'borderTopSize' => 120,
            'borderLeftSize' => 120,
            'borderRightSize' => 120,
            'borderBottomSize' => 120,
            'gridSpan' => 2,
        ] as $key => $value) {
            $set = "set{$key}";
            $get = "get{$key}";

            self::assertNull($object->$get()); // Init with null value

            $object->$set($value);

            self::assertEquals($value, $object->$get());
        }
    }

    public function testSetGetNormalString(): void
    {
        $object = new Cell();

        foreach ([
            'valign' => VerticalJc::TOP,
            'textDirection' => Cell::TEXT_DIR_BTLR,
            'bgColor' => 'FFFF00',
            'borderTopColor' => 'FFFF00',
            'borderLeftColor' => 'FFFF00',
            'borderRightColor' => 'FFFF00',
            'borderBottomColor' => 'FFFF00',
            'vMerge' => Cell::VMERGE_RESTART,
        ] as $key => $value) {
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

    /**
     * Test cell padding.
     */
    public function testPadding(): void
    {
        $object = new Cell();
        $methods = [
            'paddingTop' => 10,
            'paddingBottom' => 20,
            'paddingLeft' => 30,
            'paddingRight' => 40,
        ];

        foreach ($methods as $methodName => $methodValue) {
            $object->setStyleValue($methodName, $methodValue);
            $getterName = 'get' . ucfirst($methodName);

            self::assertEquals($methodValue, $object->$getterName());
        }
    }
}
