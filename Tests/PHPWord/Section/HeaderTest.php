<?php
namespace PHPWord\Tests\Section;

use PHPWord_Section_Header;

class HeaderTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructDefault()
    {
        $iVal = rand(1, 1000);
        $oHeader = new PHPWord_Section_Header($iVal);

        $this->assertInstanceOf('PHPWord_Section_Header', $oHeader);
        $this->assertEquals($oHeader->getHeaderCount(), $iVal);
        $this->assertEquals($oHeader->getType(), PHPWord_Section_Header::AUTO);
    }

    public function testAddText()
    {
        $oHeader = new PHPWord_Section_Header(1);
        $element = $oHeader->addText('text');

        $this->assertInstanceOf('PHPWord_Section_Text', $element);
        $this->assertCount(1, $oHeader->getElements());
        $this->assertEquals($element->getText(), 'text');
    }

    public function testAddTextNotUTF8()
    {
        $oHeader = new PHPWord_Section_Header(1);
        $element = $oHeader->addText(utf8_decode('ééé'));

        $this->assertInstanceOf('PHPWord_Section_Text', $element);
        $this->assertCount(1, $oHeader->getElements());
        $this->assertEquals($element->getText(), 'ééé');
    }

    public function testAddTextBreak()
    {
        $oHeader = new PHPWord_Section_Header(1);
        $oHeader->addTextBreak();
        $this->assertCount(1, $oHeader->getElements());
    }

    public function testAddTextBreakWithParams()
    {
        $oHeader = new PHPWord_Section_Header(1);
        $iVal = rand(1, 1000);
        $oHeader->addTextBreak($iVal);
        $this->assertCount($iVal, $oHeader->getElements());
    }

    public function testCreateTextRun()
    {
        $oHeader = new PHPWord_Section_Header(1);
        $element = $oHeader->createTextRun();
        $this->assertInstanceOf('PHPWord_Section_TextRun', $element);
        $this->assertCount(1, $oHeader->getElements());
    }

    public function testAddTable()
    {
        $oHeader = new PHPWord_Section_Header(1);
        $element = $oHeader->addTable();
        $this->assertInstanceOf('PHPWord_Section_Table', $element);
        $this->assertCount(1, $oHeader->getElements());
    }

    public function testAddImage()
    {
        $src = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'images', 'earth.jpg')
        );
        $oHeader = new PHPWord_Section_Header(1);
        $element = $oHeader->addImage($src);

        $this->assertCount(1, $oHeader->getElements());
        $this->assertInstanceOf('PHPWord_Section_Image', $element);
    }

    public function testAddMemoryImage()
    {
        $oHeader = new PHPWord_Section_Header(1);
        $element = $oHeader->addMemoryImage(
            'https://assets.mozillalabs.com/Brands-Logos/Thunderbird/logo-only/thunderbird_logo-only_RGB.png'
        );

        $this->assertCount(1, $oHeader->getElements());
        $this->assertInstanceOf('PHPWord_Section_MemoryImage', $element);
    }

    public function testAddPreserveText()
    {
        $oHeader = new PHPWord_Section_Header(1);
        $element = $oHeader->addPreserveText('text');

        $this->assertCount(1, $oHeader->getElements());
        $this->assertInstanceOf('PHPWord_Section_Footer_PreserveText', $element);
    }

    public function testAddPreserveTextNotUTF8()
    {
        $oHeader = new PHPWord_Section_Header(1);
        $element = $oHeader->addPreserveText(utf8_decode('ééé'));

        $this->assertCount(1, $oHeader->getElements());
        $this->assertInstanceOf('PHPWord_Section_Footer_PreserveText', $element);
        $this->assertEquals($element->getText(), 'ééé');
    }

    public function testAddWatermark()
    {
        $src = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'images', 'earth.jpg')
        );
        $oHeader = new PHPWord_Section_Header(1);
        $element = $oHeader->addWatermark($src);

        $this->assertCount(1, $oHeader->getElements());
        $this->assertInstanceOf('PHPWord_Section_Image', $element);
    }

    public function testGetElements()
    {
        $oHeader = new PHPWord_Section_Header(1);

        $this->assertInternalType('array', $oHeader->getElements());
    }

    public function testRelationId()
    {
        $oHeader = new PHPWord_Section_Header(1);

        $iVal = rand(1, 1000);
        $oHeader->setRelationId($iVal);
        $this->assertEquals($oHeader->getRelationId(), $iVal);
    }

    public function testResetType()
    {
        $oHeader = new PHPWord_Section_Header(1);
        $oHeader->firstPage();
        $oHeader->resetType();

        $this->assertEquals($oHeader->getType(), PHPWord_Section_Header::AUTO);
    }

    public function testFirstPage()
    {
        $oHeader = new PHPWord_Section_Header(1);
        $oHeader->firstPage();

        $this->assertEquals($oHeader->getType(), PHPWord_Section_Header::FIRST);
    }

    public function testEvenPage()
    {
        $oHeader = new PHPWord_Section_Header(1);
        $oHeader->evenPage();

        $this->assertEquals($oHeader->getType(), PHPWord_Section_Header::EVEN);
    }
}
