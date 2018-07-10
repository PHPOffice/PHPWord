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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Style;

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
        $styleTable = array('vertAnchor' => TablePosition::VANCHOR_PAGE, 'bottomFromText' => 20);

        $object = new TablePosition($styleTable);
        $this->assertEquals(TablePosition::VANCHOR_PAGE, $object->getVertAnchor());
        $this->assertEquals(20, $object->getBottomFromText());
    }

    /**
     * Test setting style with normal value
     */
    public function testSetGetNormal()
    {
        $object = new TablePosition();

        $attributes = array(
            'leftFromText'   => 4,
            'rightFromText'  => 4,
            'topFromText'    => 4,
            'bottomFromText' => 4,
            'vertAnchor'     => TablePosition::VANCHOR_PAGE,
            'horzAnchor'     => TablePosition::HANCHOR_TEXT,
            'tblpXSpec'      => TablePosition::XALIGN_CENTER,
            'tblpX'          => 5,
            'tblpYSpec'      => TablePosition::YALIGN_OUTSIDE,
            'tblpY'          => 6,
        );
        foreach ($attributes as $key => $value) {
            $set = "set{$key}";
            $get = "get{$key}";
            $object->$set($value);
            $this->assertEquals($value, $object->$get());
        }
    }
}
