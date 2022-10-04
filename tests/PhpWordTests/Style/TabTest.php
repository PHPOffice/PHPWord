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

use PhpOffice\PhpWord\Style\Tab;

/**
 * Test class for PhpOffice\PhpWord\Style\Tab.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Tab
 */
class TabTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test get/set.
     */
    public function testGetSetProperties(): void
    {
        $object = new Tab();
        $properties = [
            'type' => [Tab::TAB_STOP_CLEAR, Tab::TAB_STOP_RIGHT],
            'leader' => [Tab::TAB_LEADER_NONE, Tab::TAB_LEADER_DOT],
            'position' => [0, 10],
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
