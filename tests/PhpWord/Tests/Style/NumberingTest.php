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

use PhpOffice\PhpWord\Style\Numbering;

/**
 * Test class for PhpOffice\PhpWord\Style\Numbering
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Numbering
 */
class NumberingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test get/set
     */
    public function testGetSetProperties()
    {
        $this->object = new Numbering();
        $this->properties = array(
            'numId'  => array(null, 1),
            'type'   => array(null, 'singleLevel'),
        );
        foreach ($this->properties as $property => $value) {
            list($default, $expected) = $value;
            $get = "get{$property}";
            $set = "set{$property}";

            $this->assertEquals($default, $this->object->$get()); // Default value

            $this->object->$set($expected);

            $this->assertEquals($expected, $this->object->$get()); // New value
        }
    }

    /**
     * Test get level
     */
    public function testGetLevels()
    {
        $this->object = new Numbering();

        $this->assertEmpty($this->object->getLevels());
    }
}
