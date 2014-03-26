<?php
namespace PhpOffice\PhpWord\Tests;

use PhpOffice\PhpWord\Media;
use PhpOffice\PhpWord\Section;

class MediaTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSectionMediaElementsWithNull()
    {
        $this->assertEquals(Media::getSectionMediaElements(), array());
    }

    public function testCountSectionMediaElementsWithNull()
    {
        $this->assertEquals(Media::countSectionMediaElements(), 0);
    }

    public function testGetHeaderMediaElements()
    {
        $this->assertAttributeEquals(
            Media::getHeaderMediaElements(),
            '_headerMedia',
            'PhpOffice\\PhpWord\\Media'
        );
    }

    public function testGetFooterMediaElements()
    {
        $this->assertAttributeEquals(
            Media::getFooterMediaElements(),
            '_footerMedia',
            'PhpOffice\\PhpWord\\Media'
        );
    }

    /**
     * Todo: add memory image to this test
     *
     * @covers \PhpOffice\PhpWord\Media::addSectionMediaElement
     */
    public function testAddSectionMediaElement()
    {
        $section = new Section(0);
        $section->addImage(__DIR__ . "/_files/images/mars_noext_jpg");
        $section->addImage(__DIR__ . "/_files/images/mars.jpg");
        $section->addImage(__DIR__ . "/_files/images/mario.gif");
        $section->addImage(__DIR__ . "/_files/images/firefox.png");
        $section->addImage(__DIR__ . "/_files/images/duke_nukem.bmp");
        $section->addImage(__DIR__ . "/_files/images/angela_merkel.tif");

        $elements = $section->getElements();
        $this->assertEquals(6, count($elements));
        foreach ($elements as $element) {
            $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Image', $element);
        }
    }
}
