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

use PhpOffice\PhpWord\Style\ListItem;

/**
 * Test class for PhpOffice\PhpWord\Style\ListItem.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Style\ListItem
 *
 * @runTestsInSeparateProcesses
 */
class ListItemTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test construct.
     */
    public function testConstruct(): void
    {
        $object = new ListItem();

        $value = ListItem::TYPE_BULLET_FILLED;
        self::assertEquals($value, $object->getListType());
    }

    /**
     * Test set style value.
     */
    public function testSetStyleValue(): void
    {
        $object = new ListItem();

        $value = ListItem::TYPE_ALPHANUM;
        $object->setStyleValue('listType', $value);
        self::assertEquals($value, $object->getListType());
    }

    /**
     * Test list type.
     */
    public function testListType(): void
    {
        $object = new ListItem();

        $value = ListItem::TYPE_ALPHANUM;
        $object->setListType($value);
        self::assertEquals($value, $object->getListType());
    }

    /**
     * Test set/get numbering style name.
     */
    public function testSetGetNumStyle(): void
    {
        $expected = 'List Name';

        $object = new ListItem();
        $object->setNumStyle($expected);
        self::assertEquals($expected, $object->getNumStyle());
    }
}
