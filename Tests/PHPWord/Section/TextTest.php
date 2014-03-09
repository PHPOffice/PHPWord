<?php
namespace PHPWord\Tests;

use PHPUnit_Framework_TestCase;
use PHPWord_Section_Text;

class PHPWord_Section_TextTest extends \PHPUnit_Framework_TestCase {
  public function testConstruct() {
    $oText = new PHPWord_Section_Text();

    $this->assertInstanceOf('PHPWord_Section_Text', $oText);
    $this->assertEquals($oText->getText(), null);
    $this->assertEquals($oText->getFontStyle(), null);
    $this->assertEquals($oText->getParagraphStyle(), null);
  }
  public function testText() {
    $oText = new PHPWord_Section_Text('text');

    $this->assertEquals($oText->getText(), 'text');
  }
  public function testFont() {
    $oText = new PHPWord_Section_Text('text', 'fontStyle');
    $this->assertEquals($oText->getFontStyle(), 'fontStyle');

    $oText->setFontStyle(array('bold'=>true, 'italic'=>true, 'size'=>16));
    $this->assertInstanceOf('PHPWord_Style_Font', $oText->getFontStyle());
  }
  public function testParagraph() {
    $oText = new PHPWord_Section_Text('text', 'fontStyle', 'paragraphStyle');
    $this->assertEquals($oText->getParagraphStyle(), 'paragraphStyle');

    $oText->setParagraphStyle(array('align'=>'center', 'spaceAfter'=>100));
    $this->assertInstanceOf('PHPWord_Style_Paragraph', $oText->getParagraphStyle());
  }
}
 