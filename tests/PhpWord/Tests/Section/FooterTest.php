<?php
namespace PhpOffice\PhpWord\Tests\Section;

use PhpOffice\PhpWord\Section\Footer;

class FooterTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $iVal = rand(1, 1000);
        $oFooter = new Footer($iVal);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Footer', $oFooter);
        $this->assertEquals($oFooter->getFooterCount(), $iVal);
    }

    public function testRelationID()
    {
        $oFooter = new Footer(0);

        $iVal = rand(1, 1000);
        $oFooter->setRelationId($iVal);
        $this->assertEquals($oFooter->getRelationId(), $iVal);
    }

    public function testAddText()
    {
        $oFooter = new Footer(1);
        $element = $oFooter->addText('text');

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Text', $element);
    }

    public function testAddTextNotUTF8()
    {
        $oFooter = new Footer(1);
        $element = $oFooter->addText(utf8_decode('ééé'));

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Text', $element);
        $this->assertEquals($element->getText(), 'ééé');
    }

    public function testAddTextBreak()
    {
        $oFooter = new Footer(1);
        $iVal = rand(1, 1000);
        $oFooter->addTextBreak($iVal);

        $this->assertCount($iVal, $oFooter->getElements());
    }

    public function testCreateTextRun()
    {
        $oFooter = new Footer(1);
        $element = $oFooter->createTextRun();

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\TextRun', $element);
    }

    public function testAddTable()
    {
        $oFooter = new Footer(1);
        $element = $oFooter->addTable();

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Table', $element);
    }

    public function testAddImage()
    {
        $src = __DIR__ . "/../_files/images/earth.jpg";
        $oFooter = new Footer(1);
        $element = $oFooter->addImage($src);

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Image', $element);
    }

    public function testAddImageByUrl()
    {
        $oFooter = new Footer(1);
        $element = $oFooter->addImage(
            'https://assets.mozillalabs.com/Brands-Logos/Thunderbird/logo-only/thunderbird_logo-only_RGB.png'
        );

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Image', $element);
    }

    public function testAddPreserveText()
    {
        $oFooter = new Footer(1);
        $element = $oFooter->addPreserveText('text');

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Footer\\PreserveText', $element);
    }

    public function testAddPreserveTextNotUTF8()
    {
        $oFooter = new Footer(1);
        $element = $oFooter->addPreserveText(utf8_decode('ééé'));

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Footer\\PreserveText', $element);
        $this->assertEquals($element->getText(), array('ééé'));
    }

    public function testGetElements()
    {
        $oFooter = new Footer(1);

        $this->assertInternalType('array', $oFooter->getElements());
    }
}
