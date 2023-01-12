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

namespace PhpOffice\PhpWordTests\Element;

use PhpOffice\PhpWord\Element\ListItem;

/**
 * Test class for PhpOffice\PhpWord\Element\ListItem.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Element\ListItem
 *
 * @runTestsInSeparateProcesses
 */
class ListItemTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Get text object.
     */
    public function testText(): void
    {
        $oListItem = new ListItem('text');

        self::assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $oListItem->getTextObject());
    }

    /**
     * Get style.
     */
    public function testStyle(): void
    {
        $oListItem = new ListItem('text', 1, null, ['listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER]);

        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\ListItem', $oListItem->getStyle());
        self::assertEquals(\PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER, $oListItem->getStyle()->getListType());
    }

    /**
     * Get depth.
     */
    public function testDepth(): void
    {
        $iVal = mt_rand(1, 1000);
        $oListItem = new ListItem('text', $iVal);

        self::assertEquals($iVal, $oListItem->getDepth());
    }
}
