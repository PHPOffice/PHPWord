<?php
namespace PHPWord\Tests\Style;

use PHPWord_Style_ListItem;

/**
 * Class ListItemTest
 *
 * @package PHPWord\Tests
 * @runTestsInSeparateProcesses
 */
class ListItemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test construct
     */
    public function testConstruct()
    {
        $object = new PHPWord_Style_ListItem();

        $value = PHPWord_Style_ListItem::TYPE_BULLET_FILLED;
        $this->assertEquals($value, $object->getListType());
    }

    /**
     * Test set style value
     */
    public function testSetStyleValue()
    {
        $object = new PHPWord_Style_ListItem();

        $value = PHPWord_Style_ListItem::TYPE_ALPHANUM;
        $object->setStyleValue('_listType', $value);
        $this->assertEquals($value, $object->getListType());
    }

    /**
     * Test list type
     */
    public function testListType()
    {
        $object = new PHPWord_Style_ListItem();

        $value = PHPWord_Style_ListItem::TYPE_ALPHANUM;
        $object->setListType($value);
        $this->assertEquals($value, $object->getListType());
    }
}
