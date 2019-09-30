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

use PhpOffice\PhpWord\Style\Lengths\Percent;

/**
 * Test class for PhpOffice\PhpWord\Style\Shape
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Style\Shape
 */
class ShapeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers \PhpOffice\PhpWord\Style\Shape::getRoundness
     */
    public function testGetRoundness()
    {
        $object = new Shape();
        $this->assertEquals(new Percent(0), $object->getRoundness());
    }

    /**
     * @covers \PhpOffice\PhpWord\Style\Shape::setRoundness
     * @depends testGetRoundness
     */
    public function testSetRoundness()
    {
        $shape = new Shape();
        $this->assertEquals(new Percent(0), $shape->getRoundness());
        $shape->setRoundness(new Percent(50));
        $this->assertNotEquals(new Percent(0), $shape->getRoundness());
        $this->assertEquals(new Percent(50), $shape->getRoundness());
    }

    /**
     * @covers \PhpOffice\PhpWord\Style\Shape::setRoundness
     * @depends testSetRoundness
     * @expectedException \PhpOffice\PhpWord\Exception\Exception
     * @expectedExceptionMessage Provided roundness -1.000000% must be no less than 0%
     */
    public function testSetRoundnessNegative()
    {
        $shape = new Shape();
        $shape->setRoundness(new Percent(-1));
    }

    /**
     * @covers \PhpOffice\PhpWord\Style\Shape::setRoundness
     * @depends testSetRoundness
     * @expectedException \PhpOffice\PhpWord\Exception\Exception
     * @expectedExceptionMessage Provided roundness 101.000000% must be no greater than 100%
     */
    public function testSetRoundnessTooLarge()
    {
        $shape = new Shape();
        $shape->setRoundness(new Percent(101));
    }
}
