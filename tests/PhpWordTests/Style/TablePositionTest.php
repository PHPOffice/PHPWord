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
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWordTests\Style;

use PhpOffice\PhpWord\Style\TablePosition;

/**
 * Test class for PhpOffice\PhpWord\Style\Table.
 *
 * @runTestsInSeparateProcesses
 */
class TablePositionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test class construction.
     */
    public function testConstruct(): void
    {
        $styleTable = ['vertAnchor' => TablePosition::VANCHOR_PAGE, 'bottomFromText' => 20];

        $object = new TablePosition($styleTable);
        self::assertEquals(TablePosition::VANCHOR_PAGE, $object->getVertAnchor());
        self::assertEquals(20, $object->getBottomFromText());
    }

    /**
     * Test setting style with normal value.
     */
    public function testSetGetNormalInt(): void
    {
        $object = new TablePosition();

        foreach ([
            'leftFromText' => 4,
            'rightFromText' => 4,
            'topFromText' => 4,
            'bottomFromText' => 4,
            'tblpX' => 5,
            'tblpY' => 6,
        ] as $key => $value) {
            $set = "set{$key}";
            $get = "get{$key}";
            $object->$set($value);
            self::assertEquals($value, $object->$get());
        }
    }

    /**
     * Test setting style with normal value.
     */
    public function testSetGetNormalString(): void
    {
        $object = new TablePosition();

        foreach ([
            'vertAnchor' => TablePosition::VANCHOR_PAGE,
            'horzAnchor' => TablePosition::HANCHOR_TEXT,
            'tblpXSpec' => TablePosition::XALIGN_CENTER,
            'tblpYSpec' => TablePosition::YALIGN_OUTSIDE,
        ] as $key => $value) {
            $set = "set{$key}";
            $get = "get{$key}";
            $object->$set($value);
            self::assertEquals($value, $object->$get());
        }
    }
}
