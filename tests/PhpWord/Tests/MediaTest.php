<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests;

use PhpOffice\PhpWord\Media;
use PhpOffice\PhpWord\Section;
use PhpOffice\PhpWord\Section\Image;

/**
 * Test class for PhpOffice\PhpWord\Media
 *
 * @runTestsInSeparateProcesses
 */
class MediaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Get section media elements
     */
    public function testGetSectionMediaElementsWithNull()
    {
        $this->assertEquals(Media::getSectionMediaElements(), array());
    }

    /**
     * Count section media elements
     */
    public function testCountSectionMediaElementsWithNull()
    {
        $this->assertEquals(Media::countSectionMediaElements(), 0);
    }

    /**
     * Get header media elements
     */
    public function testGetHeaderMediaElements()
    {
        $this->assertAttributeEquals(
            Media::getHeaderMediaElements(),
            '_headerMedia',
            'PhpOffice\\PhpWord\\Media'
        );
    }

    /**
     * Get footer media elements
     */
    public function testGetFooterMediaElements()
    {
        $this->assertAttributeEquals(
            Media::getFooterMediaElements(),
            '_footerMedia',
            'PhpOffice\\PhpWord\\Media'
        );
    }

    /**
     * Add section media element
     */
    public function testAddSectionMediaElement()
    {
        $local = __DIR__ . "/_files/images/mars.jpg";
        $object = __DIR__ . "/_files/documents/sheet.xls";
        $remote = 'http://php.net/images/logos/php-med-trans-light.gif';
        Media::addSectionMediaElement($local, 'image');
        Media::addSectionMediaElement($local, 'image');
        Media::addSectionMediaElement($remote, 'image', new Image($remote));
        Media::addSectionMediaElement($object, 'oleObject');
        Media::addSectionMediaElement($object, 'oleObject');

        $this->assertEquals(3, Media::countSectionMediaElements());
    }

    /**
     * Add section link
     */
    public function testAddSectionLinkElement()
    {
        $expected = Media::countSectionMediaElements() + 7;
        $actual = Media::addSectionLinkElement('http://test.com');

        $this->assertEquals($expected, $actual);
        $this->assertEquals(1, Media::countSectionMediaElements('links'));
        $this->assertEquals(1, count(Media::getSectionMediaElements('links')));
    }

    /**
     * Add header media element
     */
    public function testAddHeaderMediaElement()
    {
        $local = __DIR__ . "/_files/images/mars.jpg";
        $remote = 'http://php.net/images/logos/php-med-trans-light.gif';
        Media::addHeaderMediaElement(1, $local);
        Media::addHeaderMediaElement(1, $local);
        Media::addHeaderMediaElement(1, $remote, new Image($remote));

        $this->assertEquals(2, Media::countHeaderMediaElements('header1'));
    }

    /**
     * Add footer media element
     */
    public function testAddFooterMediaElement()
    {
        $local = __DIR__ . "/_files/images/mars.jpg";
        $remote = 'http://php.net/images/logos/php-med-trans-light.gif';
        Media::addFooterMediaElement(1, $local);
        Media::addFooterMediaElement(1, $local);
        Media::addFooterMediaElement(1, $remote, new Image($remote));

        $this->assertEquals(2, Media::countFooterMediaElements('footer1'));
    }
}
