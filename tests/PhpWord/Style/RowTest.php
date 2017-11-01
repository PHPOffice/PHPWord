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
 * Test class for PhpOffice\PhpWord\Style\Row
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Row
 * @runTestsInSeparateProcesses
 */
class RowTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test properties with boolean value
     */
    public function testBooleanValue()
    {
        $object = new Row();

        $properties = array(
            'tblHeader'   => true,
            'cantSplit'   => false,
            'exactHeight' => true,
        );
        foreach ($properties as $key => $value) {
            // set/get
            $set = "set{$key}";
            $get = "get{$key}";
            $expected = $value ? 1 : 0;
            $object->$set($value);
            $this->assertEquals($expected, $object->$get());

            // setStyleValue
            $value = !$value;
            $expected = $value ? 1 : 0;
            $object->setStyleValue("{$key}", $value);
            $this->assertEquals($expected, $object->$get());
        }
    }

    /**
     * Test properties with nonboolean values, which will return default value
     */
    public function testNonBooleanValue()
    {
        $object = new Row();

        $properties = array(
            'tblHeader'   => 'a',
            'cantSplit'   => 'b',
            'exactHeight' => 'c',
        );
        foreach ($properties as $key => $value) {
            $set = "set{$key}";
            $get = "get{$key}";
            $object->$set($value);
            $this->assertFalse($object->$get());
        }
    }
}
