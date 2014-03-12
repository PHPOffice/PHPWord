<?php
namespace PHPWord\Tests\Section;

use PHPWord_Section_ListItem;
use PHPWord_Style_ListItem;

class ListItemTest extends \PHPUnit_Framework_TestCase
{
    public function testText()
    {
        $oListItem = new PHPWord_Section_ListItem('text');

        $this->assertInstanceOf('PHPWord_Section_Text', $oListItem->getTextObject());
    }

    public function testStyle()
    {
        $oListItem = new PHPWord_Section_ListItem(
            'text',
            1,
            null,
            array('listType' => PHPWord_Style_ListItem::TYPE_NUMBER)
        );

        $this->assertInstanceOf('PHPWord_Style_ListItem', $oListItem->getStyle());
        $this->assertEquals($oListItem->getStyle()->getListType(), PHPWord_Style_ListItem::TYPE_NUMBER);
    }

    public function testDepth()
    {
        $iVal = rand(1, 1000);
        $oListItem = new PHPWord_Section_ListItem('text', $iVal);

        $this->assertEquals($oListItem->getDepth(), $iVal);
    }
}
