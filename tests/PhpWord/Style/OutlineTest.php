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

use PhpOffice\PhpWord\Style\Colors\Hex;
use PhpOffice\PhpWord\Style\Lengths\Absolute;

/**
 * Test class for PhpOffice\PhpWord\Style\Paper
 *
 * @runTestsInSeparateProcesses
 */
class OutlineTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers \PhpOffice\PhpWord\Style\Outline::getWeight
     */
    public function testGetWeight()
    {
        $outline = new Outline();
        $this->assertEquals(new Absolute(null), $outline->getWeight());
    }

    /**
     * covers PhpOffice\PhpWord\Style\Outline::setWeight
     * @depends testGetWeight
     */
    public function testSetWeight()
    {
        $outline = new Outline();
        $this->assertEquals(new Absolute(null), $outline->getWeight());
        $outline->setWeight(Absolute::from('cm', 1));
        $this->assertEquals(Absolute::from('cm', 1), $outline->getWeight());
    }

    /**
     * covers PhpOffice\PhpWord\Style\Outline::getColor
     */
    public function testGetColor()
    {
        $outline = new Outline();
        $this->assertEquals(new Hex(null), $outline->getColor());
    }

    /**
     * covers PhpOffice\PhpWord\Style\Outline::setColor
     * @depends testGetColor
     */
    public function testSetColor()
    {
        $outline = new Outline();
        $this->assertEquals(new Hex(null), $outline->getColor());
        $outline->setColor(new Hex('123456'));
        $this->assertNotEquals(new Hex(null), $outline->getColor());
        $this->assertEquals(new Hex('123456'), $outline->getColor());
    }
}
