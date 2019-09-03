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

namespace PhpOffice\PhpWord\Style\Colors;

/**
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Colors\Rgb
 */
class RgbTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Provided values must be 0–255. Provided `Rgb(0, 255, 256)`
     */
    public function testRgbConversions()
    {
        // Prepare test values [ original, expected ]
        $values = array(
            // 6 characters
            array(array(252, 148, 189), true, 'FC94BD', 'Valid RGB should be accepted'),
            array(array(239, 3, 203), true, 'EF03CB', 'Valid RGB should be accepted'),
            array(array(114, 174, 205), true, '72AECD', 'Valid RGB should be accepted'),

            array(array(0, 0, 0), true, '000000', 'Black should be valid'),
            array(array(255, 255, 255), true, 'FFFFFF', 'White should be valid'),

            // Invalid
            array(array(0, 255, 256), false, null, '4 character hex values should fail'),
        );
        // Conduct test
        foreach ($values as $value) {
            $message = 'RGB(' . implode(', ', $value[0]) . '): ' . $value[3];
            $result = new Rgb(...$value[0]);
            $this->assertEquals($value[2], $result->toHex(), $message);
            $this->assertEquals($value[2] === null ? null : '#' . $value[2], $result->toHex(true), $message);
            $this->assertEquals($value[2], $result->toHexOrName(), $message);
            $this->assertEquals($value[2] === null ? null : '#' . $value[2], $result->toHexOrName(true), $message);
            $this->assertEquals($value[1], $result->isSpecified(), $message);
            $this->assertEquals($value[0], $result->toRgb(), $message);
        }
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Provided values must be 0–255. Provided `Rgb(-1, 131, 253)`
     */
    public function testLowRed()
    {
        new Rgb(-1, 131, 253);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Provided values must be 0–255. Provided `Rgb(131, -1, 253)`
     */
    public function testLowGreen()
    {
        new Rgb(131, -1, 253);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Provided values must be 0–255. Provided `Rgb(131, 253, -1)`
     */
    public function testLowBlue()
    {
        new Rgb(131, 253, -1);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Provided values must be 0–255. Provided `Rgb(256, 131, 253)`
     */
    public function testHighRed()
    {
        new Rgb(256, 131, 253);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Provided values must be 0–255. Provided `Rgb(131, 256, 253)`
     */
    public function testHighGreen()
    {
        new Rgb(131, 256, 253);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Provided values must be 0–255. Provided `Rgb(131, 253, 256)`
     */
    public function testHighBlue()
    {
        new Rgb(131, 253, 256);
    }

    /**
     * @expectedException \TypeError
     * @expectedExceptionMessageRegEx (Argument 3 passed to PhpOffice\\PhpWord\\Style\\Colors\\Rgb::\_\_construct\(\) must be of the type integer, none given|Too few arguments to function PhpOffice\\PhpWord\\Style\\Colors\\Rgb::\_\_construct\(\), 2 passed)
     */
    public function testTooFewArgs()
    {
        new Rgb(131, 253);
    }
}
