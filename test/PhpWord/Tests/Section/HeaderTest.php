<?php
namespace PhpOffice\PhpWord\Tests\Section;

use PhpOffice\PhpWord\Section\Header;

class HeaderTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructDefault()
    {
        $iVal = rand(1, 1000);
        $oHeader = new Header($iVal);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Header', $oHeader);
        $this->assertEquals($oHeader->getHeaderCount(), $iVal);
        $this->assertEquals($oHeader->getType(), Header::AUTO);
    }

    public function testAddText()
    {
        $oHeader = new Header(1);
        $element = $oHeader->addText('text');

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Text', $element);
        $this->assertCount(1, $oHeader->getElements());
        $this->assertEquals($element->getText(), 'text');
    }

    public function testAddTextNotUTF8()
    {
        $oHeader = new Header(1);
        $element = $oHeader->addText(utf8_decode('ééé'));

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Text', $element);
        $this->assertCount(1, $oHeader->getElements());
        $this->assertEquals($element->getText(), 'ééé');
    }

    public function testAddTextBreak()
    {
        $oHeader = new Header(1);
        $oHeader->addTextBreak();
        $this->assertCount(1, $oHeader->getElements());
    }

    public function testAddTextBreakWithParams()
    {
        $oHeader = new Header(1);
        $iVal = rand(1, 1000);
        $oHeader->addTextBreak($iVal);
        $this->assertCount($iVal, $oHeader->getElements());
    }

    public function testCreateTextRun()
    {
        $oHeader = new Header(1);
        $element = $oHeader->createTextRun();
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\TextRun', $element);
        $this->assertCount(1, $oHeader->getElements());
    }

    public function testAddTable()
    {
        $oHeader = new Header(1);
        $element = $oHeader->addTable();
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Table', $element);
        $this->assertCount(1, $oHeader->getElements());
    }

    public function testAddImage()
    {
        $src = __DIR__ . "/../_files/images/earth.jpg";
        $oHeader = new Header(1);
        $element = $oHeader->addImage($src);

        $this->assertCount(1, $oHeader->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Image', $element);
    }

    public function testAddImageByUrl()
    {
        $oHeader = new Header(1);
        $element = $oHeader->addImage(
            'https://assets.mozillalabs.com/Brands-Logos/Thunderbird/logo-only/thunderbird_logo-only_RGB.png'
        );

        $this->assertCount(1, $oHeader->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Image', $element);
    }

    public function testAddPreserveText()
    {
        $oHeader = new Header(1);
        $element = $oHeader->addPreserveText('text');

        $this->assertCount(1, $oHeader->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Footer\\PreserveText', $element);
    }

    public function testAddPreserveTextNotUTF8()
    {
        $oHeader = new Header(1);
        $element = $oHeader->addPreserveText(utf8_decode('ééé'));

        $this->assertCount(1, $oHeader->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Footer\\PreserveText', $element);
        $this->assertEquals($element->getText(), array('ééé'));
    }

    public function testAddWatermark()
    {
        $src = __DIR__ . "/../_files/images/earth.jpg";
        $oHeader = new Header(1);
        $element = $oHeader->addWatermark($src);

        $this->assertCount(1, $oHeader->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Image', $element);
    }

    public function testGetElements()
    {
        $oHeader = new Header(1);

        $this->assertInternalType('array', $oHeader->getElements());
    }

    public function testRelationId()
    {
        $oHeader = new Header(1);

        $iVal = rand(1, 1000);
        $oHeader->setRelationId($iVal);
        $this->assertEquals($oHeader->getRelationId(), $iVal);
    }

    public function testResetType()
    {
        $oHeader = new Header(1);
        $oHeader->firstPage();
        $oHeader->resetType();

        $this->assertEquals($oHeader->getType(), Header::AUTO);
    }

    public function testFirstPage()
    {
        $oHeader = new Header(1);
        $oHeader->firstPage();

        $this->assertEquals($oHeader->getType(), Header::FIRST);
    }

    public function testEvenPage()
    {
        $oHeader = new Header(1);
        $oHeader->evenPage();

        $this->assertEquals($oHeader->getType(), Header::EVEN);
    }
}
