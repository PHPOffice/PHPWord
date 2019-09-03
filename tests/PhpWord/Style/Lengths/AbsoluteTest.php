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

require_once 'DpiHelper.php';

/**
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Lengths\Absolute
 */
class AbsoluteTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test unit conversion functions with various numbers
     */
    public function testUnitConversions()
    {
        $values = array();
        $values[] = 0; // zero value
        $values[] = rand(1, 100) / 100; // fraction number
        $values[] = rand(1, 100); // integer

        foreach ($values as $value) {
            $result = Absolute::from('cm', $value);
            $this->assertEquals($value / 2.54 * 1440, $result->toFloat('twip'));

            $result = Absolute::from('cm', $value);
            $this->assertEquals($value / 2.54, $result->toFloat('in'));

            $result = Absolute::from('cm', $value);
            $this->assertEquals(round($value / 2.54 * 96), $result->toPixels(new DpiHelper(96)));

            $result = Absolute::from('cm', $value);
            $this->assertEquals($value / 2.54 * 72, $result->toFloat('pt'));

            $result = Absolute::from('cm', $value);
            $this->assertEquals($value / 2.54 * 72 * 8, $result->toFloat('eop'));

            $result = Absolute::from('cm', $value);
            $this->assertEquals($value / 2.54 * 72 * 12700, $result->toFloat('emu'), '', 0.00000001);

            $result = Absolute::from('in', $value);
            $this->assertEquals($value * 1440, $result->toFloat('twip'));

            $result = Absolute::from('in', $value);
            $this->assertEquals($value * 2.54, $result->toFloat('cm'));

            $result = Absolute::from('in', $value);
            $this->assertEquals(round($value * 96), $result->toPixels(new DpiHelper(96)));

            $result = Absolute::from('in', $value);
            $this->assertEquals($value * 72, $result->toFloat('pt'));

            $result = Absolute::from('in', $value);
            $this->assertEquals($value * 1440 / 2.5, $result->toFloat('eop'));

            $result = Absolute::from('in', $value);
            $this->assertEquals($value * 72 * 12700, $result->toFloat('emu'), '', .000000001);

            $result = Absolute::fromPixels(new DpiHelper(96), $value);
            $this->assertEquals($value / 96 * 1440, $result->toFloat('twip'));

            $result = Absolute::fromPixels(new DpiHelper(96), $value);
            $this->assertEquals($value / 96 * 2.54, $result->toFloat('cm'));

            $result = Absolute::fromPixels(new DpiHelper(96), $value);
            $this->assertEquals($value / 96 * 72, $result->toFloat('pt'));

            $result = Absolute::fromPixels(new DpiHelper(96), $value);
            $this->assertEquals($value / 96 * 1440 / 2.5, $result->toFloat('eop'));

            $result = Absolute::fromPixels(new DpiHelper(96), $value);
            $this->assertEquals($value / 96 * 72 * 12700, $result->toFloat('emu'));

            $result = Absolute::from('pt', $value);
            $this->assertEquals($value * 20, $result->toFloat('twip'));

            $result = Absolute::from('pt', $value);
            $this->assertEquals($value * 0.035277778, $result->toFloat('cm'), '', 0.00001);

            $result = Absolute::from('pt', $value);
            $this->assertEquals(round($value / 72 * 96), $result->toPixels(new DpiHelper(96)));

            $result = Absolute::from('pt', $value);
            $this->assertEquals($value * 20 / 2.5, $result->toFloat('eop'));

            $result = Absolute::from('pt', $value);
            $this->assertEquals($value * 12700, $result->toFloat('emu'), '', 0.00000000001);

            $result = Absolute::from('eop', $value);
            $this->assertEquals(round($value * 2.5 / 1440 * 96), $result->toPixels(new DpiHelper(96)));

            $result = Absolute::from('pc', $value);
            $this->assertEquals($value, $result->toFloat('pc'), '', 0.00001);
        }
    }

    public function testTwips()
    {
        $this->assertEquals(
            Absolute::from('twip', 5),
            new Absolute(5),
            'Constructor should use twips'
        );
    }

    public function testFromAbsolute()
    {
        $original = Absolute::from('twip', 5);
        $new = Absolute::fromMixed('twip', $original);
        $this->assertNotSame($original, $new, 'Lengths should be cloned to avoid accidental manipulation');
        $this->assertEquals($original->toInt('twip'), $new->toInt('twip'));
        $this->assertEquals($original->toFloat('twip'), $new->toFloat('twip'));
    }

    public function testFromNumeric()
    {
        $numbers = array(
            '0',
            '5143',
            '5143.03',
        );
        foreach ($numbers as $number) {
            $length = Absolute::fromMixed('twip', $number);
            $this->assertEquals((int) $number, $length->toInt('twip'));
            $this->assertEquals((float) $number, $length->toFloat('twip'));
        }
    }

    public function testFromFloat()
    {
        $numbers = array(
            0,
            5143,
            5143.03,
        );
        foreach ($numbers as $number) {
            $length = Absolute::fromMixed('twip', $number);
            $this->assertEquals((int) $number, $length->toInt('twip'));
            $this->assertEquals((float) $number, $length->toFloat('twip'));
        }
    }

    public function testFromNull()
    {
        $length = Absolute::fromMixed('twip', null);
        $this->assertNull($length->toInt('twip'));
        $this->assertNull($length->toFloat('twip'));
    }

    /**
     * @expectedException \PHPUnit\Framework\Error\Warning
     * @expectedExceptionMessage Border size `not a number` could not be converted to a float
     */
    public function testFromInvalid()
    {
        Absolute::fromMixed('twip', 'not a number');
    }

    public function testValueFromInvalid()
    {
        $length = @Absolute::fromMixed('twip', 'not a number');
        $this->assertNull($length->toInt('twip'), 'Invalid values should be converted to null');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Cannot convert from unit `badunit`
     */
    public function testInvalidUnit()
    {
        Absolute::from('badunit', 5);
    }

    public function testToPixels()
    {
        $dpis = array(
            72,
            96,
            254.3,
            400,
        );
        $floats = array(
            0,
            -2,
            -.5,
            .5,
            2,
            4,
            42,
        );
        foreach ($dpis as $dpi) {
            $dpi = new DpiHelper($dpi);
            foreach ($floats as $float) {
                $length = Absolute::from('in', $float);
                $this->assertEquals(round($float * $dpi->getDpi()), $length->toPixels($dpi));
            }
        }
    }

    public function testNullToPixels()
    {
        $length = Absolute::from('in', null);
        $this->assertNull($length->toPixels(new DpiHelper(96)));
    }
}
