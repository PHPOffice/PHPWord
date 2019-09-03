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

use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Lengths\Absolute;

/**
 * Test class for PhpOffice\PhpWord\Style\NumberingLevel
 *
 * @runTestsInSeparateProcesses
 */
class NumberingLevelTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test setting style with normal value
     */
    public function testSetGetNormal()
    {
        $object = new NumberingLevel();

        $attributes = array(
            'level'     => 1,
            'start'     => 1,
            'format'    => 'decimal',
            'restart'   => 1,
            'pStyle'    => 'pStyle',
            'suffix'    => 'space',
            'text'      => '%1.',
            'alignment' => Jc::START,
            'left'      => Absolute::from('twip', 360),
            'hanging'   => Absolute::from('twip', 360),
            'tabPos'    => Absolute::from('twip', 360),
            'font'      => 'Arial',
            'hint'      => 'default',
        );
        foreach ($attributes as $key => $value) {
            $set = "set{$key}";
            $get = "get{$key}";
            $object->$set($value);
            $this->assertEquals($value, $object->$get());
        }
    }

    /**
     * @covers \PhpOffice\PhpWord\Style\NumberingLevel::getLeft
     */
    public function testGetLeft()
    {
        $level = new NumberingLevel();
        $this->assertEquals(new Absolute(null), $level->getLeft());
    }

    /**
     * @covers \PhpOffice\PhpWord\Style\NumberingLevel::setLeft
     * @depends testGetLeft
     */
    public function testSetLeft()
    {
        $level = new NumberingLevel();
        $this->assertEquals(new Absolute(null), $level->getLeft());
        $level->setLeft(Absolute::from('pt', 5));
        $this->assertNotEquals(new Absolute(null), $level->getLeft());
        $this->assertEquals(Absolute::from('pt', 5), $level->getLeft());
    }

    /**
     * @covers \PhpOffice\PhpWord\Style\NumberingLevel::getHanging
     */
    public function testGetHanging()
    {
        $level = new NumberingLevel();
        $this->assertEquals(new Absolute(null), $level->getHanging());
    }

    /**
     * @covers \PhpOffice\PhpWord\Style\NumberingLevel::setHanging
     * @depends testGetHanging
     */
    public function testSetHanging()
    {
        $level = new NumberingLevel();
        $this->assertEquals(new Absolute(null), $level->getHanging());
        $level->setHanging(Absolute::from('pt', 5));
        $this->assertNotEquals(new Absolute(null), $level->getHanging());
        $this->assertEquals(Absolute::from('pt', 5), $level->getHanging());
    }

    /**
     * @covers \PhpOffice\PhpWord\Style\NumberingLevel::getTabPos
     */
    public function testGetTabPos()
    {
        $level = new NumberingLevel();
        $this->assertEquals(new Absolute(null), $level->getTabPos());
    }

    /**
     * @covers \PhpOffice\PhpWord\Style\NumberingLevel::setTabPos
     * @depends testGetTabPos
     */
    public function testSetTabPos()
    {
        $level = new NumberingLevel();
        $this->assertEquals(new Absolute(null), $level->getTabPos());
        $level->setTabPos(Absolute::from('pt', 5));
        $this->assertNotEquals(new Absolute(null), $level->getTabPos());
        $this->assertEquals(Absolute::from('pt', 5), $level->getTabPos());
    }
}
