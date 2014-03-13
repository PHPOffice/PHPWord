<?php
namespace PHPWord\Tests;

use PHPWord_Media;
use PHPWord_Section;

class MediaTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSectionMediaElementsWithNull()
    {
        $this->assertEquals(PHPWord_Media::getSectionMediaElements(), array());
    }

    public function testCountSectionMediaElementsWithNull()
    {
        $this->assertEquals(PHPWord_Media::countSectionMediaElements(), 0);
    }

    public function testGetHeaderMediaElements()
    {
        $this->assertAttributeEquals(PHPWord_Media::getHeaderMediaElements(), '_headerMedia', 'PHPWord_Media');
    }

    public function testGetFooterMediaElements()
    {
        $this->assertAttributeEquals(PHPWord_Media::getFooterMediaElements(), '_footerMedia', 'PHPWord_Media');
    }

    /**
     * Todo: add memory image to this test
     *
     * @covers PHPWord_Media::addSectionMediaElement
     */
    public function testAddSectionMediaElement()
    {
        $section = new PHPWord_Section(0);
        $section->addImage(PHPWORD_TESTS_DIR_ROOT . "/_files/images/mars_noext_jpg");
        $section->addImage(PHPWORD_TESTS_DIR_ROOT . "/_files/images/mars.jpg");
        $section->addImage(PHPWORD_TESTS_DIR_ROOT . "/_files/images/mario.gif");
        $section->addImage(PHPWORD_TESTS_DIR_ROOT . "/_files/images/firefox.png");
        $section->addImage(PHPWORD_TESTS_DIR_ROOT . "/_files/images/duke_nukem.bmp");
        $section->addImage(PHPWORD_TESTS_DIR_ROOT . "/_files/images/angela_merkel.tif");

        $elements = $section->getElements();
        $this->assertEquals(6, count($elements));
        foreach ($elements as $element) {
            $this->assertInstanceOf('PHPWord_Section_Image', $element);
        }
    }
}
