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

namespace PhpOffice\PhpWordTests\Shared;

use PhpOffice\PhpWord\Shared\Metafile;
use PHPUnit\Framework\TestCase;

/**
 * Test class for PhpOffice\PhpWord\Shared\Metafile.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Shared\Metafile
 */
class MetafileTest extends TestCase
{
    public function testGetMimeType(): void
    {
        foreach (self::providerMetafiles() as [$source, $mimeType]) {
            self::assertEquals($mimeType, Metafile::getMimeType($this->getContent($source)), $source);
        }
    }

    public function testIsSupported(): void
    {
        // phpoffice/wmf is a dev dependency, and GD is installed for the tests
        self::assertTrue(Metafile::isSupported());
    }

    public function testGetMimeTypeNotMetafile(): void
    {
        self::assertNull(Metafile::getMimeType($this->getContent('earth.jpg')));
        self::assertNull(Metafile::getMimeType(''));
    }

    public function testIsMimeType(): void
    {
        self::assertTrue(Metafile::isMimeType(Metafile::MIME_WMF));
        self::assertTrue(Metafile::isMimeType(Metafile::MIME_EMF));
        self::assertFalse(Metafile::isMimeType('image/png'));
        self::assertFalse(Metafile::isMimeType(null));
    }

    public function testGetExtension(): void
    {
        self::assertEquals('wmf', Metafile::getExtension(Metafile::MIME_WMF));
        self::assertEquals('emf', Metafile::getExtension(Metafile::MIME_EMF));
        self::assertNull(Metafile::getExtension('image/png'));
    }

    public function testGetImageSize(): void
    {
        foreach (self::providerMetafiles() as [$source, $mimeType, $width, $height]) {
            self::assertEquals([$width, $height, $mimeType], Metafile::getImageSize($this->getContent($source)), $source);
        }
    }

    public function testGetImageSizeInvalid(): void
    {
        self::assertNull(Metafile::getImageSize($this->getContent('earth.jpg')));
        self::assertNull(Metafile::getImageSize($this->getInvalidEMF()));
    }

    public function testConvertToPng(): void
    {
        foreach (self::providerMetafiles() as [$source, , $width, $height]) {
            $png = Metafile::convertToPng($this->getContent($source));
            self::assertIsString($png, $source);
            $imageData = getimagesizefromstring($png);
            self::assertIsArray($imageData, $source);
            self::assertEquals([$width, $height, IMAGETYPE_PNG], array_slice($imageData, 0, 3), $source);
        }
    }

    public function testConvertToPngInvalid(): void
    {
        self::assertNull(Metafile::convertToPng($this->getContent('earth.jpg')));
        self::assertNull(Metafile::convertToPng($this->getInvalidEMF()));
    }

    private static function providerMetafiles(): array
    {
        return [
            ['fish.wmf', Metafile::MIME_WMF, 217, 159],
            ['inkscape_shapes.emf', Metafile::MIME_EMF, 200, 151],
            ['inkscape_shapes_emfplus.emf', Metafile::MIME_EMF, 200, 151],
        ];
    }

    /**
     * EMF file with a valid header, followed by an unknown record.
     */
    private function getInvalidEMF(): string
    {
        $content = $this->getContent('inkscape_shapes.emf');
        $headerSize = (int) current((array) unpack('V', substr($content, 4, 4)));

        return substr($content, 0, $headerSize) . pack('VV', 0xFFFF, 8) . pack('VVVVV', 0x0E, 20, 0, 0, 20);
    }

    private function getContent(string $source): string
    {
        return (string) file_get_contents(__DIR__ . '/../_files/images/' . $source);
    }
}
