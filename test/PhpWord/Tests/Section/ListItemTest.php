<?php
namespace PhpOffice\PhpWord\Tests\Section;

use PhpOffice\PhpWord\Section\ListItem;

class ListItemTest extends \PHPUnit_Framework_TestCase
{
    public function testText()
    {
        $oListItem = new ListItem('text');

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Text', $oListItem->getTextObject());
    }

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

    public function testDepth()
    {
        $iVal = rand(1, 1000);
        $oListItem = new ListItem('text', $iVal);

        $this->assertEquals($oListItem->getDepth(), $iVal);
    }
}
