<?php
namespace PHPWord\Tests;

use PHPUnit_Framework_TestCase;
use PHPWord_Section_Footer_PreserveText;

class PHPWord_Section_Footer_PreserveTextTest extends \PHPUnit_Framework_TestCase {
  public function testConstruct(){
    $oPreserveText = new PHPWord_Section_Footer_PreserveText();

    $this->assertInstanceOf('PHPWord_Section_Footer_PreserveText', $oPreserveText);
    $this->assertEquals($oPreserveText->getText(), null);
    $this->assertEquals($oPreserveText->getFontStyle(), null);
    $this->assertEquals($oPreserveText->getParagraphStyle(), null);
  }

  public function testConstructWithString(){
    $oPreserveText = new PHPWord_Section_Footer_PreserveText('text', 'styleFont', 'styleParagraph');
    $this->assertEquals($oPreserveText->getText(), 'text');
    $this->assertEquals($oPreserveText->getFontStyle(), 'styleFont');
    $this->assertEquals($oPreserveText->getParagraphStyle(), 'styleParagraph');
  }

  public function testConstructWithArray(){
    $oPreserveText = new PHPWord_Section_Footer_PreserveText('text', array('align'=>'center'), array('marginLeft'=>600, 'marginRight'=>600, 'marginTop'=>600, 'marginBottom'=>600));
    $this->assertInstanceOf('PHPWord_Style_Font', $oPreserveText->getFontStyle());
    $this->assertInstanceOf('PHPWord_Style_Paragraph', $oPreserveText->getParagraphStyle());
  }
}
 