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

use PhpOffice\PhpWord\Style\Row;

/**
 * Test class for PhpOffice\PhpWord\Style\Row.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Row
 *
 * @runTestsInSeparateProcesses
 */
class RowTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test properties with boolean value.
     */
    public function testBooleanValue(): void
    {
        $object = new Row();

        $properties = [
            'tblHeader' => true,
            'cantSplit' => false,
            'exactHeight' => true,
        ];
        foreach ($properties as $key => $value) {
            // set/get
            $set = "set{$key}";
            $get = "is{$key}";
            $expected = $value ? 1 : 0;
            $object->$set($value);
            self::assertEquals($expected, $object->$get());

            // setStyleValue
            $value = !$value;
            $expected = $value ? 1 : 0;
            $object->setStyleValue("{$key}", $value);
            self::assertEquals($expected, $object->$get());
        }
    }

    /**
     * Test properties with nonboolean values, which will return default value.
     */
    public function testNonBooleanValue(): void
    {
        $object = new Row();

        $properties = [
            'tblHeader' => 'a',
            'cantSplit' => 'b',
            'exactHeight' => 'c',
        ];
        foreach ($properties as $key => $value) {
            $set = "set{$key}";
            $get = "is{$key}";
            $object->$set($value);
            self::assertFalse($object->$get());
        }
    }
}
