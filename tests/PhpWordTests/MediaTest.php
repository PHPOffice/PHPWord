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
 * @see         https://github.com/PHPOffice/PHPWord
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWordTests;

use Exception;
use PhpOffice\PhpWord\Element\Image;
use PhpOffice\PhpWord\Media;

/**
 * Test class for PhpOffice\PhpWord\Media.
 *
 * @runTestsInSeparateProcesses
 */
class MediaTest extends AbstractWebServerEmbeddedTest
{
    /**
     * Get section media elements.
     */
    public function testGetSectionMediaElementsWithNull(): void
    {
        self::assertEquals([], Media::getElements('section'));
    }

    /**
     * Get header media elements.
     */
    public function testGetHeaderMediaElementsWithNull(): void
    {
        self::assertEquals([], Media::getElements('header'));
    }

    /**
     * Get footer media elements.
     */
    public function testGetFooterMediaElementsWithNull(): void
    {
        self::assertEquals([], Media::getElements('footer'));
    }

    /**
     * Count section media elements.
     */
    public function testCountSectionMediaElementsWithNull(): void
    {
        self::assertEquals(0, Media::countElements('section'));
    }

    /**
     * Add section media element.
     */
    public function testAddSectionMediaElement(): void
    {
        $local = __DIR__ . '/_files/images/mars.jpg';
        $object = __DIR__ . '/_files/documents/sheet.xls';
        $remote = self::getRemoteImageUrl();
        Media::addElement('section', 'image', $local, new Image($local));
        Media::addElement('section', 'image', $local, new Image($local));
        Media::addElement('section', 'image', $remote, new Image($local));
        Media::addElement('section', 'object', $object);
        Media::addElement('section', 'object', $object);

        self::assertCount(3, Media::getElements('section'));
    }

    /**
     * Add section link.
     */
    public function testAddSectionLinkElement(): void
    {
        $expected = Media::countElements('section') + 1;
        $actual = Media::addElement('section', 'link', 'http://test.com');

        self::assertEquals($expected, $actual);
        self::assertCount(1, Media::getElements('section', 'link'));
    }

    /**
     * Add header media element.
     */
    public function testAddHeaderMediaElement(): void
    {
        $local = __DIR__ . '/_files/images/mars.jpg';
        $remote = self::getRemoteImageUrl();
        Media::addElement('header1', 'image', $local, new Image($local));
        Media::addElement('header1', 'image', $local, new Image($local));
        Media::addElement('header1', 'image', $remote, new Image($remote));

        self::assertCount(2, Media::getElements('header1'));
        self::assertEmpty(Media::getElements('header2'));
    }

    /**
     * Add footer media element and reset media.
     */
    public function testAddFooterMediaElement(): void
    {
        $local = __DIR__ . '/_files/images/mars.jpg';
        $remote = self::getRemoteImageUrl();
        Media::addElement('footer1', 'image', $local, new Image($local));
        Media::addElement('footer1', 'image', $local, new Image($local));
        Media::addElement('footer1', 'image', $remote, new Image($remote));

        self::assertCount(2, Media::getElements('footer1'));

        Media::resetElements();
        self::assertCount(0, Media::getElements('footer1'));
    }

    /**
     * Add image element exception.
     */
    public function testAddElementImageException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Image object not assigned.');
        Media::addElement('section', 'image', __DIR__ . '/_files/images/mars.jpg');
    }
}
