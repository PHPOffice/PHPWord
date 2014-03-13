<?php
namespace PHPWord\Tests\Section;

use PHPWord_Section_Footnote;

class FootnoteTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $oFootnote = new PHPWord_Section_Footnote();

        $this->assertInstanceOf('PHPWord_Section_Footnote', $oFootnote);
        $this->assertCount(0, $oFootnote->getElements());
        $this->assertEquals($oFootnote->getParagraphStyle(), null);
    }

    public function testConstructString()
    {
        $oFootnote = new PHPWord_Section_Footnote('pStyle');

        $this->assertEquals($oFootnote->getParagraphStyle(), 'pStyle');
    }

    public function testConstructArray()
    {
        $oFootnote = new PHPWord_Section_Footnote(array('spacing' => 100));

        $this->assertInstanceOf('PHPWord_Style_Paragraph', $oFootnote->getParagraphStyle());
    }

    public function testAddText()
    {
        $oFootnote = new PHPWord_Section_Footnote();
        $element = $oFootnote->addText('text');

        $this->assertCount(1, $oFootnote->getElements());
        $this->assertInstanceOf('PHPWord_Section_Text', $element);
    }

    public function testAddLink()
    {
        $oFootnote = new PHPWord_Section_Footnote();
        $element = $oFootnote->addLink('http://www.google.fr');

        $this->assertCount(1, $oFootnote->getElements());
        $this->assertInstanceOf('PHPWord_Section_Link', $element);
    }

    public function testReferenceId()
    {
        $oFootnote = new PHPWord_Section_Footnote();

        $iVal = rand(1, 1000);
        $oFootnote->setReferenceId($iVal);
        $this->assertEquals($oFootnote->getReferenceId(), $iVal);
    }

    public function testGetElements()
    {
        $oFootnote = new PHPWord_Section_Footnote();
        $this->assertInternalType('array', $oFootnote->getElements());
    }
}
