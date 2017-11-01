<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2016 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord;

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
        $this->assertEquals(array(), Media::getElements('section'));
    }

    /**
     * Count section media elements
     */
    public function testCountSectionMediaElementsWithNull()
    {
        $this->assertEquals(0, Media::countElements('section'));
    }

    /**
     * Add section media element
     */
    public function testAddSectionMediaElement()
    {
        $local = __DIR__ . '/_files/images/mars.jpg';
        $object = __DIR__ . '/_files/documents/sheet.xls';
        $remote = 'http://php.net/images/logos/php-med-trans-light.gif';
        Media::addElement('section', 'image', $local, new Image($local));
        Media::addElement('section', 'image', $local, new Image($local));
        Media::addElement('section', 'image', $remote, new Image($local));
        Media::addElement('section', 'object', $object);
        Media::addElement('section', 'object', $object);

        $this->assertCount(3, Media::getElements('section'));
    }

    /**
     * Add section link
     */
    public function testAddSectionLinkElement()
    {
        $expected = Media::countElements('section') + 1;
        $actual = Media::addElement('section', 'link', 'http://test.com');

        $this->assertEquals($expected, $actual);
        $this->assertCount(1, Media::getElements('section', 'link'));
    }

    /**
     * Add header media element
     */
    public function testAddHeaderMediaElement()
    {
        $local = __DIR__ . '/_files/images/mars.jpg';
        $remote = 'http://php.net/images/logos/php-med-trans-light.gif';
        Media::addElement('header1', 'image', $local, new Image($local));
        Media::addElement('header1', 'image', $local, new Image($local));
        Media::addElement('header1', 'image', $remote, new Image($remote));

        $this->assertCount(2, Media::getElements('header1'));
        $this->assertEmpty(Media::getElements('header2'));
    }

    /**
     * Add footer media element and reset media
     */
    public function testAddFooterMediaElement()
    {
        $local = __DIR__ . '/_files/images/mars.jpg';
        $remote = 'http://php.net/images/logos/php-med-trans-light.gif';
        Media::addElement('footer1', 'image', $local, new Image($local));
        Media::addElement('footer1', 'image', $local, new Image($local));
        Media::addElement('footer1', 'image', $remote, new Image($remote));

        $this->assertCount(2, Media::getElements('footer1'));

        Media::resetElements();
        $this->assertCount(0, Media::getElements('footer1'));
    }

    /**
     * Add image element exception
     *
     * @expectedException Exception
     * @expectedExceptionMessage Image object not assigned.
     */
    public function testAddElementImageException()
    {
        Media::addElement('section', 'image', __DIR__ . '/_files/images/mars.jpg');
    }
}
