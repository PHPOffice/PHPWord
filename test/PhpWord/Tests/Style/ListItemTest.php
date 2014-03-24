<?php
namespace PhpOffice\PhpWord\Tests\Style;

use PhpOffice\PhpWord\Style\ListItem;

/**
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
        $object->setStyleValue('_listType', $value);
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
}
