<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests\Element;

use PhpOffice\PhpWord\Element\ListItem;

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
        $oListItem = new ListItem(
            'text',
            1,
            null,
            array('listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER)
        );

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\ListItem', $oListItem->getStyle());
        $this->assertEquals(
            $oListItem->getStyle()->getListType(),
            \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER
        );
    }

    /**
     * Get depth
     */
    public function testDepth()
    {
        $iVal = rand(1, 1000);
        $oListItem = new ListItem('text', $iVal);

        $this->assertEquals($oListItem->getDepth(), $iVal);
    }
}
