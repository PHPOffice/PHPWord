<?php
namespace PHPWord\Tests\Section;

use PHPWord081_PHPWord_Section_CheckBox;

class CheckBoxTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $oCheckBox = new PHPWord081_PHPWord_Section_CheckBox();

        $this->assertInstanceOf('PHPWord_Section_CheckBox', $oCheckBox);
        $this->assertEquals(null, $oCheckBox->getText());
        $this->assertInstanceOf('PHPWord_Style_Font', $oCheckBox->getFontStyle());
        $this->assertInstanceOf('PHPWord_Style_Paragraph', $oCheckBox->getParagraphStyle());
    }

    public function testCheckBox()
    {
        $oCheckBox = new PHPWord_Section_CheckBox('CheckBox');

        $this->assertEquals($oCheckBox->getText(), 'CheckBox');
    }

    public function testFont()
    {
        $oCheckBox = new PHPWord_Section_CheckBox('CheckBox', 'fontStyle');
        $this->assertEquals($oCheckBox->getFontStyle(), 'fontStyle');

        $oCheckBox->setFontStyle(array('bold' => true, 'italic' => true, 'size' => 16));
        $this->assertInstanceOf('PHPWord_Style_Font', $oCheckBox->getFontStyle());
    }

    public function testParagraph()
    {
        $oCheckBox = new PHPWord_Section_CheckBox('CheckBox', 'fontStyle', 'paragraphStyle');
        $this->assertEquals($oCheckBox->getParagraphStyle(), 'paragraphStyle');

        $oCheckBox->setParagraphStyle(array('align' => 'center', 'spaceAfter' => 100));
        $this->assertInstanceOf('PHPWord_Style_Paragraph', $oCheckBox->getParagraphStyle());
    }
}
