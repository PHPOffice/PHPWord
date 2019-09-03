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

use PhpOffice\PhpWord\Style\Lengths\Absolute;

/**
 * Test class for PhpOffice\PhpWord\Style\Table
 *
 * @runTestsInSeparateProcesses
 */
class TablePositionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test class construction
     */
    public function testConstruct()
    {
        $styleTable = array('vertAnchor' => TablePosition::VANCHOR_PAGE, 'bottomFromText' => Absolute::from('twip', 20));

        $object = new TablePosition($styleTable);
        $this->assertEquals(TablePosition::VANCHOR_PAGE, $object->getVertAnchor());
        $this->assertEquals(20, $object->getBottomFromText()->toInt('twip'));
    }

    /**
     * Test setting style with normal value
     */
    public function testSetGetNormal()
    {
        $object = new TablePosition();

        $attributes = array(
            'leftFromText'   => Absolute::from('twip', 4),
            'rightFromText'  => Absolute::from('twip', 4),
            'topFromText'    => Absolute::from('twip', 4),
            'bottomFromText' => Absolute::from('twip', 4),
            'vertAnchor'     => TablePosition::VANCHOR_PAGE,
            'horzAnchor'     => TablePosition::HANCHOR_TEXT,
            'tblpXSpec'      => TablePosition::XALIGN_CENTER,
            'tblpX'          => Absolute::from('twip', 5),
            'tblpYSpec'      => TablePosition::YALIGN_OUTSIDE,
            'tblpY'          => Absolute::from('twip', 6),
        );
        foreach ($attributes as $key => $value) {
            $set = "set{$key}";
            $get = "get{$key}";
            $object->$set($value);
            $result = $object->$get();
            if ($value instanceof Absolute) {
                $value = $value->toInt('twip');
                $result = $result->toInt('twip');
            }
            $this->assertEquals($value, $result, "Read value for attribute $key should be the same as the written value");
        }
    }

    /**
     * @covers \PhpOffice\PhpWord\Style\TablePosition
     */
    public function testSetGetAbsolute()
    {
        $attributes = array(
            'TopFromText',
            'BottomFromText',
            'LeftFromText',
            'RightFromText',
            'TblpX',
            'TblpY',
        );

        $level = new TablePosition();
        foreach ($attributes as $attribute) {
            $get = "get$attribute";
            $set = "set$attribute";

            $this->assertEquals(new Absolute(null), $level->$get());
            $level->$set(Absolute::from('pt', 5));
            $this->assertNotEquals(new Absolute(null), $level->$get());
            $this->assertEquals(Absolute::from('pt', 5), $level->$get());
        }
    }
}
