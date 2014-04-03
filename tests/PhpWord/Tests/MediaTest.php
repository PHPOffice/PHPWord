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
use PhpOffice\PhpWord\Container\Section;
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
        $this->assertEquals(Media::getMediaElements('section'), array());
    }

    /**
     * Count section media elements
     */
    public function testCountSectionMediaElementsWithNull()
    {
        $this->assertEquals(Media::countMediaElements('section'), 0);
    }

    /**
     * Add section media element
     */
    public function testAddSectionMediaElement()
    {
        $local = __DIR__ . "/_files/images/mars.jpg";
        $object = __DIR__ . "/_files/documents/sheet.xls";
        $remote = 'http://php.net/images/logos/php-med-trans-light.gif';
        Media::addMediaElement('section', 'image', $local, new Image($local));
        Media::addMediaElement('section', 'image', $local, new Image($local));
        Media::addMediaElement('section', 'image', $remote, new Image($local));
        Media::addMediaElement('section', 'object', $object);
        Media::addMediaElement('section', 'object', $object);

        $this->assertEquals(3, Media::countMediaElements('section'));
    }

    /**
     * Add section link
     */
    public function testAddSectionLinkElement()
    {
        $expected = Media::countMediaElements('section') + 1;
        $actual = Media::addMediaElement('section', 'link', 'http://test.com');

        $this->assertEquals($expected, $actual);
        $this->assertEquals(1, Media::countMediaElements('section', 'link'));
        $this->assertEquals(1, count(Media::getMediaElements('section', 'link')));
    }

    /**
     * Add header media element
     */
    public function testAddHeaderMediaElement()
    {
        $local = __DIR__ . "/_files/images/mars.jpg";
        $remote = 'http://php.net/images/logos/php-med-trans-light.gif';
        Media::addMediaElement('header1', 'image', $local, new Image($local));
        Media::addMediaElement('header1', 'image', $local, new Image($local));
        Media::addMediaElement('header1', 'image', $remote, new Image($remote));

        $this->assertEquals(2, Media::countMediaElements('header1'));
        $this->assertEquals(2, count(Media::getMediaElements('header1')));
        $this->assertEmpty(Media::getMediaElements('header2'));
    }

    /**
     * Add footer media element
     */
    public function testAddFooterMediaElement()
    {
        $local = __DIR__ . "/_files/images/mars.jpg";
        $remote = 'http://php.net/images/logos/php-med-trans-light.gif';
        Media::addMediaElement('footer1', 'image', $local, new Image($local));
        Media::addMediaElement('footer1', 'image', $local, new Image($local));
        Media::addMediaElement('footer1', 'image', $remote, new Image($remote));

        $this->assertEquals(2, Media::countMediaElements('footer1'));
    }
}
