<?php
namespace PhpOffice\PhpWord\Tests\Section;

use PhpOffice\PhpWord\Section\TextRun;

class TextRunTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructNull()
    {
        $oTextRun = new TextRun();

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\TextRun', $oTextRun);
        $this->assertCount(0, $oTextRun->getElements());
        $this->assertEquals($oTextRun->getParagraphStyle(), null);
    }

    public function testConstructString()
    {
        $oTextRun = new TextRun('pStyle');

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\TextRun', $oTextRun);
        $this->assertCount(0, $oTextRun->getElements());
        $this->assertEquals($oTextRun->getParagraphStyle(), 'pStyle');
    }

    public function testConstructArray()
    {
        $oTextRun = new TextRun(array('spacing' => 100));

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\TextRun', $oTextRun);
        $this->assertCount(0, $oTextRun->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Paragraph', $oTextRun->getParagraphStyle());
    }

    public function testAddText()
    {
        $oTextRun = new TextRun();
        $element = $oTextRun->addText('text');

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Text', $element);
        $this->assertCount(1, $oTextRun->getElements());
        $this->assertEquals($element->getText(), 'text');
    }

    public function testAddTextNotUTF8()
    {
        $oTextRun = new TextRun();
        $element = $oTextRun->addText(utf8_decode('ééé'));

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Text', $element);
        $this->assertCount(1, $oTextRun->getElements());
        $this->assertEquals($element->getText(), 'ééé');
    }

    public function testAddLink()
    {
        $oTextRun = new TextRun();
        $element = $oTextRun->addLink('http://www.google.fr');

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Link', $element);
        $this->assertCount(1, $oTextRun->getElements());
        $this->assertEquals($element->getLinkSrc(), 'http://www.google.fr');
    }

    public function testAddLinkWithName()
    {
        $oTextRun = new TextRun();
        $element = $oTextRun->addLink('http://www.google.fr', utf8_decode('ééé'));

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Link', $element);
        $this->assertCount(1, $oTextRun->getElements());
        $this->assertEquals($element->getLinkSrc(), 'http://www.google.fr');
        $this->assertEquals($element->getLinkName(), 'ééé');
    }

    public function testAddImage()
    {
        $src = __DIR__ . "/../_files/images/earth.jpg";

        $oTextRun = new TextRun();
        $element = $oTextRun->addImage($src);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Image', $element);
        $this->assertCount(1, $oTextRun->getElements());
    }

    public function testCreateFootnote()
    {
        $oTextRun = new TextRun();
        $element = $oTextRun->createFootnote();

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Footnote', $element);
        $this->assertCount(1, $oTextRun->getElements());
    }
}
