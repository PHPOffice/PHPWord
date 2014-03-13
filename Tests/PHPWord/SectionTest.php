<?php
namespace PHPWord\Tests;

use PHPWord_Section;

/**
 * Class TOCTest
 *
 * @package PHPWord\Tests
 * @covers PHPWord_Section
 * @runTestsInSeparateProcesses
 */
class SectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers PHPWord_Section::getSettings
     */
    public function testGetSettings()
    {
        $oSection = new PHPWord_Section(0);
        $this->assertAttributeEquals($oSection->getSettings(), '_settings', new PHPWord_Section(0));
    }

    /**
     * @covers PHPWord_Section::getElements
     */
    public function testGetElements()
    {
        $oSection = new PHPWord_Section(0);
        $this->assertAttributeEquals($oSection->getElements(), '_elementCollection', new PHPWord_Section(0));
    }

    /**
     * @covers PHPWord_Section::getFooter
     */
    public function testGetFooter()
    {
        $oSection = new PHPWord_Section(0);
        $this->assertAttributeEquals($oSection->getFooter(), '_footer', new PHPWord_Section(0));
    }

    /**
     * @covers PHPWord_Section::getHeaders
     */
    public function testGetHeaders()
    {
        $oSection = new PHPWord_Section(0);
        $this->assertAttributeEquals($oSection->getHeaders(), '_headers', new PHPWord_Section(0));
    }

    /**
     * @covers PHPWord_Section::setSettings
     */
    public function testSetSettings()
    {
        $expected = 'landscape';
        $object = new PHPWord_Section(0);
        $object->setSettings(array('orientation' => $expected));
        $this->assertEquals($expected, $object->getSettings()->getOrientation());
    }

    /**
     * @covers PHPWord_Section::addText
     * @covers PHPWord_Section::addLink
     * @covers PHPWord_Section::addTextBreak
     * @covers PHPWord_Section::addPageBreak
     * @covers PHPWord_Section::addTable
     * @covers PHPWord_Section::addListItem
     * @covers PHPWord_Section::addObject
     * @covers PHPWord_Section::addImage
     * @covers PHPWord_Section::addMemoryImage
     * @covers PHPWord_Section::addTOC
     * @covers PHPWord_Section::addTitle
     * @covers PHPWord_Section::createTextRun
     * @covers PHPWord_Section::createFootnote
     */
    public function testAddElements()
    {
        $objectSource = join(
            DIRECTORY_SEPARATOR,
            array(PHPWORD_TESTS_DIR_ROOT, '_files', 'documents', 'sheet.xls')
        );
        $imageSource = join(
            DIRECTORY_SEPARATOR,
            array(PHPWORD_TESTS_DIR_ROOT, '_files', 'images', 'PHPWord.png')
        );
        $imageUrl = 'http://php.net//images/logos/php-med-trans-light.gif';

        $section = new PHPWord_Section(0);
        $section->addText(utf8_decode('ä'));
        $section->addLink(utf8_decode('http://äää.com'), utf8_decode('ä'));
        $section->addTextBreak();
        $section->addPageBreak();
        $section->addTable();
        $section->addListItem(utf8_decode('ä'));
        $section->addObject($objectSource);
        $section->addImage($imageSource);
        $section->addMemoryImage($imageUrl);
        $section->addTOC();
        $section->addTitle(utf8_decode('ä'), 1);
        $section->createTextRun();
        $section->createFootnote();

        $elementCollection = $section->getElements();
        $elementType = 'Link';
        $objectType = "PHPWord_Section_{$elementType}";
        $this->assertInstanceOf($objectType, $elementCollection[1]);
        // $elementTypes = array('Text', 'Link', 'TextBreak', 'PageBreak',
            // 'Table', 'ListItem', 'Object', 'Image', 'MemoryImage', 'TOC',
            // 'Title', 'TextRun');
        // $i = 0;
        // foreach ($elementTypes as $elementType) {
            // $objectType = "PHPWord_Section_{$elementType}";
            // $this->assertInstanceOf($objectType, $elementCollection[$i]);
            // $i++;
        // }
    }

    /**
     * @covers PHPWord_Section::createHeader
     * @covers PHPWord_Section::createFooter
     */
    public function testCreateHeaderFooter()
    {
        $object = new PHPWord_Section(0);
        $elements = array('Header', 'Footer');
        foreach ($elements as $element) {
            $objectType = "PHPWord_Section_{$element}";
            $method = "create{$element}";
            $this->assertInstanceOf($objectType, $object->$method());
        }
    }
}
