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
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2016 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Style;

/**
 * Test class for PhpOffice\PhpWord\Style\ListItem
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Style\ListItem
 * @runTestsInSeparateProcesses
 */
class ListItemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test construct
     */
    public function testConstruct()
    {
        $object = new ListItem();

        $value = ListItem::TYPE_BULLET_FILLED;
        $this->assertEquals($value, $object->getListType());
    }

    /**
     * Test set style value
     */
    public function testSetStyleValue()
    {
        $object = new ListItem();

        $value = ListItem::TYPE_ALPHANUM;
        $object->setStyleValue('listType', $value);
        $this->assertEquals($value, $object->getListType());
    }

    /**
     * Test list type
     */
    public function testListType()
    {
        $object = new ListItem();

        $value = ListItem::TYPE_ALPHANUM;
        $object->setListType($value);
        $this->assertEquals($value, $object->getListType());
    }

    /**
     * Test set/get numbering style name
     */
    public function testSetGetNumStyle()
    {
        $expected = 'List Name';

        $object = new ListItem();
        $object->setNumStyle($expected);
        $this->assertEquals($expected, $object->getNumStyle());
    }
}
