<?php
namespace PHPWord\Tests\Section;

use PHPWord_Section_Link;
use PHPWord_Style_Font;

class LinkTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructDefault()
    {
        $oLink = new PHPWord_Section_Link('http://www.google.com');

        $this->assertInstanceOf('PHPWord_Section_Link', $oLink);
        $this->assertEquals($oLink->getLinkSrc(), 'http://www.google.com');
        $this->assertEquals($oLink->getLinkName(), null);
        $this->assertEquals($oLink->getFontStyle(), null);
        $this->assertEquals($oLink->getParagraphStyle(), null);
    }

    public function testConstructWithParamsArray()
    {
        $oLink = new PHPWord_Section_Link(
            'http://www.google.com',
            'Search Engine',
            array('color' => '0000FF', 'underline' => PHPWord_Style_Font::UNDERLINE_SINGLE),
            array('marginLeft' => 600, 'marginRight' => 600, 'marginTop' => 600, 'marginBottom' => 600)
        );

        $this->assertInstanceOf('PHPWord_Section_Link', $oLink);
        $this->assertEquals($oLink->getLinkSrc(), 'http://www.google.com');
        $this->assertEquals($oLink->getLinkName(), 'Search Engine');
        $this->assertInstanceOf('PHPWord_Style_Font', $oLink->getFontStyle());
        $this->assertInstanceOf('PHPWord_Style_Paragraph', $oLink->getParagraphStyle());
    }

    public function testConstructWithParamsString()
    {
        $oLink = new PHPWord_Section_Link('http://www.google.com', null, 'fontStyle', 'paragraphStyle');

        $this->assertEquals($oLink->getFontStyle(), 'fontStyle');
        $this->assertEquals($oLink->getParagraphStyle(), 'paragraphStyle');
    }

    public function testRelationId()
    {
        $oLink = new PHPWord_Section_Link('http://www.google.com');

        $iVal = rand(1, 1000);
        $oLink->setRelationId($iVal);
        $this->assertEquals($oLink->getRelationId(), $iVal);
    }
}
