<?php
namespace PHPWord\Tests\Section\Table;

use PHPWord_Section_Table_Cell;

class CellTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $iVal = rand(1, 1000);
        $oCell = new PHPWord_Section_Table_Cell('section', $iVal);

        $this->assertInstanceOf('PHPWord_Section_Table_Cell', $oCell);
        $this->assertEquals($oCell->getWidth(), null);
    }

    public function testConstructWithStyleArray()
    {
        $iVal = rand(1, 1000);
        $oCell = new PHPWord_Section_Table_Cell('section', $iVal, null, array('valign' => 'center'));

        $this->assertInstanceOf('PHPWord_Style_Cell', $oCell->getStyle());
        $this->assertEquals($oCell->getWidth(), null);
    }

    public function testConstructWithStyleString()
    {
        $iVal = rand(1, 1000);
        $oCell = new PHPWord_Section_Table_Cell('section', $iVal, null, 'cellStyle');

        $this->assertEquals($oCell->getStyle(), 'cellStyle');
    }

    public function testAddText()
    {
        $oCell = new PHPWord_Section_Table_Cell('section', 1);
        $element = $oCell->addText('text');

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PHPWord_Section_Text', $element);
    }

    public function testAddTextNotUTF8()
    {
        $oCell = new PHPWord_Section_Table_Cell('section', 1);
        $element = $oCell->addText(utf8_decode('ééé'));

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PHPWord_Section_Text', $element);
        $this->assertEquals($element->getText(), 'ééé');
    }

    public function testAddLink()
    {
        $oCell = new PHPWord_Section_Table_Cell('section', 1);
        $element = $oCell->addLink('http://www.google.fr', 'Nom');

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PHPWord_Section_Link', $element);
    }

    public function testAddTextBreak()
    {
        $oCell = new PHPWord_Section_Table_Cell('section', 1);
        $oCell->addTextBreak();

        $this->assertCount(1, $oCell->getElements());
    }

    public function testAddListItem()
    {
        $oCell = new PHPWord_Section_Table_Cell('section', 1);
        $element = $oCell->addListItem('text');

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PHPWord_Section_ListItem', $element);
        $this->assertEquals($element->getTextObject()->getText(), 'text');
    }

    public function testAddListItemNotUTF8()
    {
        $oCell = new PHPWord_Section_Table_Cell('section', 1);
        $element = $oCell->addListItem(utf8_decode('ééé'));

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PHPWord_Section_ListItem', $element);
        $this->assertEquals($element->getTextObject()->getText(), 'ééé');
    }

    public function testAddImageSection()
    {
        $src = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'images', 'earth.jpg')
        );
        $oCell = new PHPWord_Section_Table_Cell('section', 1);
        $element = $oCell->addImage($src);

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PHPWord_Section_Image', $element);
    }

    public function testAddImageHeader()
    {
        $src = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'images', 'earth.jpg')
        );
        $oCell = new PHPWord_Section_Table_Cell('header', 1);
        $element = $oCell->addImage($src);

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PHPWord_Section_Image', $element);
    }

    public function testAddImageFooter()
    {
        $src = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'images', 'earth.jpg')
        );
        $oCell = new PHPWord_Section_Table_Cell('footer', 1);
        $element = $oCell->addImage($src);

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PHPWord_Section_Image', $element);
    }

    public function testAddMemoryImageSection()
    {
        $oCell = new PHPWord_Section_Table_Cell('section', 1);
        $element = $oCell->addMemoryImage(
            'https://assets.mozillalabs.com/Brands-Logos/Thunderbird/logo-only/thunderbird_logo-only_RGB.png'
        );

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PHPWord_Section_MemoryImage', $element);
    }

    public function testAddMemoryImageHeader()
    {
        $oCell = new PHPWord_Section_Table_Cell('header', 1);
        $element = $oCell->addMemoryImage(
            'https://assets.mozillalabs.com/Brands-Logos/Thunderbird/logo-only/thunderbird_logo-only_RGB.png'
        );

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PHPWord_Section_MemoryImage', $element);
    }

    public function testAddMemoryImageFooter()
    {
        $oCell = new PHPWord_Section_Table_Cell('footer', 1);
        $element = $oCell->addMemoryImage(
            'https://assets.mozillalabs.com/Brands-Logos/Thunderbird/logo-only/thunderbird_logo-only_RGB.png'
        );

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PHPWord_Section_MemoryImage', $element);
    }

    public function testAddObjectXLS()
    {
        $src = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'documents', 'sheet.xls')
        );
        $oCell = new PHPWord_Section_Table_Cell('section', 1);
        $element = $oCell->addObject($src);

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PHPWord_Section_Object', $element);
    }

    public function testAddPreserveText()
    {
        $oCell = new PHPWord_Section_Table_Cell('header', 1);
        $element = $oCell->addPreserveText('text');

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PHPWord_Section_Footer_PreserveText', $element);
    }

    public function testAddPreserveTextNotUTF8()
    {
        $oCell = new PHPWord_Section_Table_Cell('header', 1);
        $element = $oCell->addPreserveText(utf8_decode('ééé'));

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PHPWord_Section_Footer_PreserveText', $element);
        $this->assertEquals($element->getText(), 'ééé');
    }

    public function testCreateTextRun()
    {
        $oCell = new PHPWord_Section_Table_Cell('section', 1);
        $element = $oCell->createTextRun();

        $this->assertCount(1, $oCell->getElements());
        $this->assertInstanceOf('PHPWord_Section_TextRun', $element);
    }

    public function testGetElements()
    {
        $oCell = new PHPWord_Section_Table_Cell('section', 1);

        $this->assertInternalType('array', $oCell->getElements());
    }
}
