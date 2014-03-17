<?php
namespace PHPWord\Tests\Section;

use PHPWord_Section_Footer;

class FooterTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $iVal = rand(1, 1000);
        $oFooter = new PHPWord_Section_Footer($iVal);

        $this->assertInstanceOf('PHPWord_Section_Footer', $oFooter);
        $this->assertEquals($oFooter->getFooterCount(), $iVal);
    }

    public function testRelationID()
    {
        $oFooter = new PHPWord_Section_Footer(0);

        $iVal = rand(1, 1000);
        $oFooter->setRelationId($iVal);
        $this->assertEquals($oFooter->getRelationId(), $iVal);
    }

    public function testAddText()
    {
        $oFooter = new PHPWord_Section_Footer(1);
        $element = $oFooter->addText('text');

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PHPWord_Section_Text', $element);

    }

    public function testAddTextNotUTF8()
    {
        $oFooter = new PHPWord_Section_Footer(1);
        $element = $oFooter->addText(utf8_decode('ééé'));

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PHPWord_Section_Text', $element);
        $this->assertEquals($element->getText(), 'ééé');
    }

    public function testAddTextBreak()
    {
        $oFooter = new PHPWord_Section_Footer(1);
        $iVal = rand(1, 1000);
        $oFooter->addTextBreak($iVal);

        $this->assertCount($iVal, $oFooter->getElements());
    }

    public function testCreateTextRun()
    {
        $oFooter = new PHPWord_Section_Footer(1);
        $element = $oFooter->createTextRun();

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PHPWord_Section_TextRun', $element);
    }

    public function testAddTable()
    {
        $oFooter = new PHPWord_Section_Footer(1);
        $element = $oFooter->addTable();

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PHPWord_Section_Table', $element);
    }

    public function testAddImage()
    {
        $src = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'images', 'earth.jpg')
        );
        $oFooter = new PHPWord_Section_Footer(1);
        $element = $oFooter->addImage($src);

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PHPWord_Section_Image', $element);
    }

    public function testAddMemoryImage()
    {
        $oFooter = new PHPWord_Section_Footer(1);
        $element = $oFooter->addMemoryImage(
            'https://assets.mozillalabs.com/Brands-Logos/Thunderbird/logo-only/thunderbird_logo-only_RGB.png'
        );

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PHPWord_Section_MemoryImage', $element);
    }

    public function testAddPreserveText()
    {
        $oFooter = new PHPWord_Section_Footer(1);
        $element = $oFooter->addPreserveText('text');

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PHPWord_Section_Footer_PreserveText', $element);
    }

    public function testAddPreserveTextNotUTF8()
    {
        $oFooter = new PHPWord_Section_Footer(1);
        $element = $oFooter->addPreserveText(utf8_decode('ééé'));

        $this->assertCount(1, $oFooter->getElements());
        $this->assertInstanceOf('PHPWord_Section_Footer_PreserveText', $element);
        $this->assertEquals($element->getText(), array('ééé'));
    }

    public function testGetElements()
    {
        $oFooter = new PHPWord_Section_Footer(1);

        $this->assertInternalType('array', $oFooter->getElements());
    }
}
