<?php
namespace PhpOffice\PhpWord\Tests\Section;

use PhpOffice\PhpWord\Section\Footnote;

class FootnoteTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $oFootnote = new Footnote();

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Footnote', $oFootnote);
        $this->assertCount(0, $oFootnote->getElements());
        $this->assertEquals($oFootnote->getParagraphStyle(), null);
    }

    public function testConstructString()
    {
        $oFootnote = new Footnote('pStyle');

        $this->assertEquals($oFootnote->getParagraphStyle(), 'pStyle');
    }

    public function testConstructArray()
    {
        $oFootnote = new Footnote(array('spacing' => 100));

        $this->assertInstanceOf(
            'PhpOffice\\PhpWord\\Style\\Paragraph',
            $oFootnote->getParagraphStyle()
        );
    }

    public function testAddText()
    {
        $oFootnote = new Footnote();
        $element = $oFootnote->addText('text');

        $this->assertCount(1, $oFootnote->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Text', $element);
    }

    public function testAddLink()
    {
        $oFootnote = new Footnote();
        $element = $oFootnote->addLink('http://www.google.fr');

        $this->assertCount(1, $oFootnote->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Link', $element);
    }

    public function testReferenceId()
    {
        $oFootnote = new Footnote();

        $iVal = rand(1, 1000);
        $oFootnote->setReferenceId($iVal);
        $this->assertEquals($oFootnote->getReferenceId(), $iVal);
    }

    public function testGetElements()
    {
        $oFootnote = new Footnote();
        $this->assertInternalType('array', $oFootnote->getElements());
    }
}
