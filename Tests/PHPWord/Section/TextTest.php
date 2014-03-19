<?php
namespace PHPWord\Tests\Section;

use PHPWord_Section_Text;

class TextTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $oText = new PHPWord_Section_Text();

        $this->assertInstanceOf('PHPWord_Section_Text', $oText);
        $this->assertEquals(null, $oText->getText());
        $this->assertInstanceOf('PHPWord_Style_Font', $oText->getFontStyle());
        $this->assertInstanceOf('PHPWord_Style_Paragraph', $oText->getParagraphStyle());
    }

    public function testText()
    {
        $oText = new PHPWord_Section_Text('text');

        $this->assertEquals($oText->getText(), 'text');
    }

    public function testFont()
    {
        $oText = new PHPWord_Section_Text('text', 'fontStyle');
        $this->assertEquals($oText->getFontStyle(), 'fontStyle');

        $oText->setFontStyle(array('bold' => true, 'italic' => true, 'size' => 16));
        $this->assertInstanceOf('PHPWord_Style_Font', $oText->getFontStyle());
    }

    public function testParagraph()
    {
        $oText = new PHPWord_Section_Text('text', 'fontStyle', 'paragraphStyle');
        $this->assertEquals($oText->getParagraphStyle(), 'paragraphStyle');

        $oText->setParagraphStyle(array('align' => 'center', 'spaceAfter' => 100));
        $this->assertInstanceOf('PHPWord_Style_Paragraph', $oText->getParagraphStyle());
    }
}
