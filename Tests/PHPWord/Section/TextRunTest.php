<?php
namespace PHPWord\Tests\Section;

use PHPWord_Section_TextRun;

class TextRunTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructNull()
    {
        $oTextRun = new PHPWord_Section_TextRun();

        $this->assertInstanceOf('PHPWord_Section_TextRun', $oTextRun);
        $this->assertCount(0, $oTextRun->getElements());
        $this->assertEquals($oTextRun->getParagraphStyle(), null);
    }

    public function testConstructString()
    {
        $oTextRun = new PHPWord_Section_TextRun('pStyle');

        $this->assertInstanceOf('PHPWord_Section_TextRun', $oTextRun);
        $this->assertCount(0, $oTextRun->getElements());
        $this->assertEquals($oTextRun->getParagraphStyle(), 'pStyle');
    }

    public function testConstructArray()
    {
        $oTextRun = new PHPWord_Section_TextRun(array('spacing' => 100));

        $this->assertInstanceOf('PHPWord_Section_TextRun', $oTextRun);
        $this->assertCount(0, $oTextRun->getElements());
        $this->assertInstanceOf('PHPWord_Style_Paragraph', $oTextRun->getParagraphStyle());
    }

    public function testAddText()
    {
        $oTextRun = new PHPWord_Section_TextRun();
        $element = $oTextRun->addText('text');

        $this->assertInstanceOf('PHPWord_Section_Text', $element);
        $this->assertCount(1, $oTextRun->getElements());
        $this->assertEquals($element->getText(), 'text');
    }

    public function testAddTextNotUTF8()
    {
        $oTextRun = new PHPWord_Section_TextRun();
        $element = $oTextRun->addText(utf8_decode('ééé'));

        $this->assertInstanceOf('PHPWord_Section_Text', $element);
        $this->assertCount(1, $oTextRun->getElements());
        $this->assertEquals($element->getText(), 'ééé');
    }

    public function testAddLink()
    {
        $oTextRun = new PHPWord_Section_TextRun();
        $element = $oTextRun->addLink('http://www.google.fr');

        $this->assertInstanceOf('PHPWord_Section_Link', $element);
        $this->assertCount(1, $oTextRun->getElements());
        $this->assertEquals($element->getLinkSrc(), 'http://www.google.fr');
    }

    public function testAddLinkWithName()
    {
        $oTextRun = new PHPWord_Section_TextRun();
        $element = $oTextRun->addLink('http://www.google.fr', utf8_decode('ééé'));

        $this->assertInstanceOf('PHPWord_Section_Link', $element);
        $this->assertCount(1, $oTextRun->getElements());
        $this->assertEquals($element->getLinkSrc(), 'http://www.google.fr');
        $this->assertEquals($element->getLinkName(), 'ééé');
    }

    public function testAddImage()
    {
        $src = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'images', 'earth.jpg')
        );
        $oTextRun = new PHPWord_Section_TextRun();
        $element = $oTextRun->addImage($src);

        $this->assertInstanceOf('PHPWord_Section_Image', $element);
        $this->assertCount(1, $oTextRun->getElements());
    }

    public function testCreateFootnote()
    {
        $oTextRun = new PHPWord_Section_TextRun();
        $element = $oTextRun->createFootnote();

        $this->assertInstanceOf('PHPWord_Section_Footnote', $element);
        $this->assertCount(1, $oTextRun->getElements());
    }
}
