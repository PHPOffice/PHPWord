<?php
namespace PhpOffice\PhpWord\Tests;

use PhpOffice\PhpWord\Section;

/**
 * @coversDefaultClass          \PhpOffice\PhpWord\Section
 * @runTestsInSeparateProcesses
 */
class SectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::getSettings
     */
    public function testGetSettings()
    {
        $oSection = new Section(0);
        $this->assertAttributeEquals($oSection->getSettings(), '_settings', new Section(0));
    }

    /**
     * @covers ::getElements
     */
    public function testGetElements()
    {
        $oSection = new Section(0);
        $this->assertAttributeEquals($oSection->getElements(), '_elementCollection', new Section(0));
    }

    /**
     * @covers ::getFooter
     */
    public function testGetFooter()
    {
        $oSection = new Section(0);
        $this->assertAttributeEquals($oSection->getFooter(), '_footer', new Section(0));
    }

    /**
     * @covers ::getHeaders
     */
    public function testGetHeaders()
    {
        $oSection = new Section(0);
        $this->assertAttributeEquals($oSection->getHeaders(), '_headers', new Section(0));
    }

    /**
     * @covers ::setSettings
     */
    public function testSetSettings()
    {
        $expected = 'landscape';
        $object = new Section(0);
        $object->setSettings(array('orientation' => $expected));
        $this->assertEquals($expected, $object->getSettings()->getOrientation());
    }

    /**
     * @covers ::addText
     * @covers ::addLink
     * @covers ::addTextBreak
     * @covers ::addPageBreak
     * @covers ::addTable
     * @covers ::addListItem
     * @covers ::addObject
     * @covers ::addImage
     * @covers ::addTOC
     * @covers ::addTitle
     * @covers ::createTextRun
     * @covers ::createFootnote
     */
    public function testAddElements()
    {
        $objectSource = __DIR__ . "/_files/documents/sheet.xls";
        $imageSource = __DIR__ . "/_files/images/PhpWord.png";
        $imageUrl = 'http://php.net//images/logos/php-med-trans-light.gif';

        $section = new Section(0);
        $section->addText(utf8_decode('ä'));
        $section->addLink(utf8_decode('http://äää.com'), utf8_decode('ä'));
        $section->addTextBreak();
        $section->addPageBreak();
        $section->addTable();
        $section->addListItem(utf8_decode('ä'));
        $section->addObject($objectSource);
        $section->addImage($imageSource);
        $section->addImage($imageUrl);
        $section->addTOC();
        $section->addTitle(utf8_decode('ä'), 1);
        $section->createTextRun();
        $section->createFootnote();

        $elementCollection = $section->getElements();
        $elementType = 'Link';
        $this->assertInstanceOf("PhpOffice\\PhpWord\\Section\\{$elementType}", $elementCollection[1]);
        // $elementTypes = array('Text', 'Link', 'TextBreak', 'PageBreak',
            // 'Table', 'ListItem', 'Object', 'Image', 'Image', 'TOC',
            // 'Title', 'TextRun');
        // $i = 0;
        // foreach ($elementTypes as $elementType) {
            // $this->assertInstanceOf("PhpOffice\\PhpWord\\Section\\{$elementType}", $elementCollection[$i]);
            // $i++;
        // }
    }

    /**
     * @covers ::createHeader
     * @covers ::createFooter
     */
    public function testCreateHeaderFooter()
    {
        $object = new Section(0);
        $elements = array('Header', 'Footer');
        foreach ($elements as $element) {
            $method = "create{$element}";
            $this->assertInstanceOf("PhpOffice\\PhpWord\\Section\\{$element}", $object->$method());
        }
    }
}
