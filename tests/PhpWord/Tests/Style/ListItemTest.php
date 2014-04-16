<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests\Style;

use PhpOffice\PhpWord\Style\ListItem;

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
