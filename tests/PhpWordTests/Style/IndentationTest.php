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

use PhpOffice\PhpWord\Style\Indentation;

/**
 * Test class for PhpOffice\PhpWord\Style\Indentation.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Indentation
 */
class IndentationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test get/set.
     */
    public function testGetSetProperties(): void
    {
        $object = new Indentation();
        $properties = [
            'left' => [0, 10],
            'right' => [0, 10],
            'firstLine' => [null, 20],
            'hanging' => [null, 20],
        ];
        foreach ($properties as $property => $value) {
            [$default, $expected] = $value;
            $get = "get{$property}";
            $set = "set{$property}";

            self::assertEquals($default, $object->$get()); // Default value

            $object->$set($expected);

            self::assertEquals($expected, $object->$get()); // New value
        }
    }
}
