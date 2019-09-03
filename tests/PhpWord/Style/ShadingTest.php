<?php
declare(strict_types=1);
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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Style;

use PhpOffice\PhpWord\Style\Colors\BasicColor;
use PhpOffice\PhpWord\Style\Colors\Hex;

/**
 * Test class for PhpOffice\PhpWord\Style\Shading
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Shading
 */
class ShadingTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test get/set
     */
    public function testGetSetProperties()
    {
        $object = new Shading();
        $properties = array(
            'pattern' => array('clear', 'solid'),
            'color'   => array(null, new Hex('FF0000')),
            'fill'    => array(null, new Hex('FF0000')),
        );
        foreach ($properties as $property => $value) {
            list($default, $expected) = $value;

            $this->assertEquals($default, $this->get($object, $property)); // Default value

            $this->set($object, $property, $expected);

            if ($expected instanceof BasicColor) {
                $expected = $expected->toHex();
            }
            $this->assertEquals($expected, $this->get($object, $property)); // New value
        }
    }

    private function get(Shading $object, string $property)
    {
        $get = "get{$property}";

        $result = $object->$get();
        if ($result instanceof BasicColor) {
            $result = $result->toHex();
        }

        return $result;
    }

    private function set(Shading $object, string $property, $expected)
    {
        $set = "set$property";

        return $object->$set($expected);
    }
}
