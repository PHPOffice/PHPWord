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

namespace PhpOffice\PhpWord\Element;

/**
 * Test class for PhpOffice\PhpWord\Element\ListItem
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Element\ListItem
 * @runTestsInSeparateProcesses
 */
class ListItemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Get text object
     */
    public function testText()
    {
        $oListItem = new ListItem('text');

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Text', $oListItem->getTextObject());
    }

    /**
     * Get style
     */
    public function testStyle()
    {
        $oListItem = new ListItem('text', 1, null, array('listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER));

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\ListItem', $oListItem->getStyle());
        $this->assertEquals(\PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER, $oListItem->getStyle()->getListType());
    }

    /**
     * Get depth
     */
    public function testDepth()
    {
        $iVal = rand(1, 1000);
        $oListItem = new ListItem('text', $iVal);

        $this->assertEquals($iVal, $oListItem->getDepth());
    }
}
