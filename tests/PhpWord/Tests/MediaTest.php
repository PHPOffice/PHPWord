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
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Element\Image;

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
        $this->assertEquals(Media::getElements('section'), array());
    }

    /**
     * Count section media elements
     */
    public function testCountSectionMediaElementsWithNull()
    {
        $this->assertEquals(Media::countElements('section'), 0);
    }

    /**
     * Add section media element
     */
    public function testAddSectionMediaElement()
    {
        $local = __DIR__ . "/_files/images/mars.jpg";
        $object = __DIR__ . "/_files/documents/sheet.xls";
        $remote = 'http://php.net/images/logos/php-med-trans-light.gif';
        Media::addElement('section', 'image', $local, new Image($local));
        Media::addElement('section', 'image', $local, new Image($local));
        Media::addElement('section', 'image', $remote, new Image($local));
        Media::addElement('section', 'object', $object);
        Media::addElement('section', 'object', $object);

        $this->assertEquals(3, Media::countElements('section'));
    }

    /**
     * Add section link
     */
    public function testAddSectionLinkElement()
    {
        $expected = Media::countElements('section') + 1;
        $actual = Media::addElement('section', 'link', 'http://test.com');

        $this->assertEquals($expected, $actual);
        $this->assertEquals(1, Media::countElements('section', 'link'));
        $this->assertEquals(1, count(Media::getElements('section', 'link')));
    }

    /**
     * Add header media element
     */
    public function testAddHeaderMediaElement()
    {
        $local = __DIR__ . "/_files/images/mars.jpg";
        $remote = 'http://php.net/images/logos/php-med-trans-light.gif';
        Media::addElement('header1', 'image', $local, new Image($local));
        Media::addElement('header1', 'image', $local, new Image($local));
        Media::addElement('header1', 'image', $remote, new Image($remote));

        $this->assertEquals(2, Media::countElements('header1'));
        $this->assertEquals(2, count(Media::getElements('header1')));
        $this->assertEmpty(Media::getElements('header2'));
    }

    /**
     * Add footer media element and reset media
     */
    public function testAddFooterMediaElement()
    {
        $local = __DIR__ . "/_files/images/mars.jpg";
        $remote = 'http://php.net/images/logos/php-med-trans-light.gif';
        Media::addElement('footer1', 'image', $local, new Image($local));
        Media::addElement('footer1', 'image', $local, new Image($local));
        Media::addElement('footer1', 'image', $remote, new Image($remote));

        $this->assertEquals(2, Media::countElements('footer1'));

        Media::resetElements();
        $this->assertEquals(0, Media::countElements('footer1'));
    }

    /**
     * Add image element exception
     *
     * @expectedException Exception
     * @expectedExceptionMessage Image object not assigned.
     */
    public function testAddElementImageException()
    {
        Media::addElement('section', 'image', __DIR__ . "/_files/images/mars.jpg");
    }
}
