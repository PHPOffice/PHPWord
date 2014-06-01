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

use PhpOffice\PhpWord\Style\Spacing;

/**
 * Test class for PhpOffice\PhpWord\Style\Spacing
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Spacing
 */
class SpacingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test get/set
     */
    public function testGetSetProperties()
    {
        $object = new Spacing();
        $properties = array(
            'before' => array(null, 10),
            'after' => array(null, 10),
            'line' => array(null, 10),
            'rule' => array('auto', 'exact'),
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
