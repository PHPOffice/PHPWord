<?php
namespace PHPWord\Tests\Section;

use PHPWord_Section_Title;

class TitleTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $oTitle = new PHPWord_Section_Title('text');

        $this->assertInstanceOf('PHPWord_Section_Title', $oTitle);
        $this->assertEquals($oTitle->getText(), 'text');
    }

    public function testStyleNull()
    {
        $oTitle = new PHPWord_Section_Title('text');

        $this->assertEquals($oTitle->getStyle(), null);
    }

    public function testStyleNotNull()
    {
        $oTitle = new PHPWord_Section_Title('text', 1, 'style');

        $this->assertEquals($oTitle->getStyle(), 'style');
    }

    public function testAnchor()
    {
        $oTitle = new PHPWord_Section_Title('text');

        $iVal = rand(1, 1000);
        $oTitle->setAnchor($iVal);
        $this->assertEquals($oTitle->getAnchor(), $iVal);
    }

    public function testBookmarkID()
    {
        $oTitle = new PHPWord_Section_Title('text');

        $iVal = rand(1, 1000);
        $oTitle->setBookmarkId($iVal);
        $this->assertEquals($oTitle->getBookmarkId(), $iVal);
    }
}
