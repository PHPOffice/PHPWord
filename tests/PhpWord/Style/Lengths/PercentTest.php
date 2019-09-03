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

namespace PhpOffice\PhpWord\Style\Lengths;

/**
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Lengths\Percent
 */
class PercentTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test initialization
     */
    public function test()
    {
        $floats = array(
            0,
            2.5,
            50,
            100,
            250,
        );
        foreach ($floats as $float) {
            $length = new Percent($float);
            $this->assertEquals(round($float), $length->toInt());
            $this->assertEquals((float) $float, $length->toFloat());
        }
    }

    /**
     * Test initialization
     */
    public function testFromMixed()
    {
        $values = array(
            '0'    => 0,
            '2.5'  => 0.05,
            '50'   => 1,
            '100'  => 2,
            '250'  => 5,
            '0%'   => 0,
            '2.5%' => 2.5,
            '50%'  => 50,
            '100%' => 100,
            '250%' => 250,
        );
        foreach ($values as $input => $expected) {
            $length = Percent::fromMixed($input);
            $this->assertEquals(round($expected), $length->toInt(), sprintf('Value \'%s\' should convert to \'%s\'', $input, round($expected)));
            $this->assertEquals($expected, $length->toFloat(), sprintf('Value \'%s\' should convert to \'%s\'', $input, $expected));
        }
    }

    public function testFromPercent()
    {
        $original = new Percent(5);
        $new = Percent::fromMixed($original);
        $this->assertNotSame($original, $new, 'Lengths should be cloned to avoid accidental manipulation');
        $this->assertEquals($original->toInt(), $new->toInt());
        $this->assertEquals($original->toFloat(), $new->toFloat());
    }

    /**
     * @expectedException \PHPUnit\Framework\Error\Warning
     * @expectedExceptionMessage Percent length `not a number` could not be converted to a float
     */
    public function testInvalidUnit()
    {
        Percent::fromMixed('not a number');
    }

    public function testValueInvalidUnit()
    {
        $length = @Percent::fromMixed('not a number');
        $this->assertNull($length->toInt());
    }
}
