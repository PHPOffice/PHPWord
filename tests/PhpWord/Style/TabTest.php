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
 * @copyright   2010-2016 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Style;

/**
 * Test class for PhpOffice\PhpWord\Style\Tab
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Tab
 */
class TabTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test get/set
     */
    public function testGetSetProperties()
    {
        $object = new Tab();
        $properties = array(
            'type'     => array(Tab::TAB_STOP_CLEAR, Tab::TAB_STOP_RIGHT),
            'leader'   => array(Tab::TAB_LEADER_NONE, Tab::TAB_LEADER_DOT),
            'position' => array(0, 10),
        );
        foreach ($properties as $property => $value) {
            list($default, $expected) = $value;
            $get = "get{$property}";
            $set = "set{$property}";

            $this->assertEquals($default, $object->$get()); // Default value

            $object->$set($expected);

            $this->assertEquals($expected, $object->$get()); // New value
        }
    }
}
