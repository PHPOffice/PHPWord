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

namespace PhpOffice\PhpWordTests\Element;

use PhpOffice\PhpWord\Element\Image;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWordTests\AbstractWebServerEmbedded;

/**
 * Test class for PhpOffice\PhpWord\Element\Image.
 */
class ImageTest extends AbstractWebServerEmbedded
{
    /**
     * New instance.
     */
    public function testConstruct(): void
    {
        $src = __DIR__ . '/../_files/images/firefox.png';
        $oImage = new Image($src);

        self::assertEquals($src, $oImage->getSource());
        self::assertEquals(md5($src), $oImage->getMediaId());
        self::assertFalse($oImage->isWatermark());
        self::assertEquals(Image::SOURCE_LOCAL, $oImage->getSourceType());
        self::assertNull($oImage->getAltText());
        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Image', $oImage->getStyle());
    }

    /**
     * New instance with style.
     */
    public function testConstructWithStyle(): void
    {
        $src = __DIR__ . '/../_files/images/firefox.png';
        $oImage = new Image(
            $src,
            [
                'width' => 210,
                'height' => 210,
                'alignment' => Jc::CENTER,
                'wrappingStyle' => \PhpOffice\PhpWord\Style\Image::WRAPPING_STYLE_BEHIND,
            ]
        );

        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Image', $oImage->getStyle());
    }

    /**
     * Valid image types.
     *
     * @dataProvider providerImages
     */
    public function testImages($source, $type, $extension, $createFunction, $imageFunction, $imageQuality): void
    {
        $nam = ucfirst((string) strtok($source, '.'));
        $source = __DIR__ . "/../_files/images/{$source}";
        $image = new Image($source, null, null, $nam);
        self::assertEquals($source, $image->getSource());
        self::assertEquals($nam, $image->getName());
        self::assertEquals(md5($source), $image->getMediaId());
        self::assertEquals($type, $image->getImageType());
        self::assertEquals($extension, $image->getImageExtension());
        self::assertEquals($createFunction, $image->getImageCreateFunction());
        if ($imageFunction) {
            self::assertNotNull($image->getImageFunction());
        } else {
            self::assertNull($image->getImageFunction());
        }
        self::assertEquals($imageQuality, $image->getImageQuality());
        self::assertFalse($image->isMemImage());
        self::assertNotNull($image->getImageStringData());
    }

    public static function providerImages(): array
    {
        return [
            ['mars.jpg', 'image/jpeg', 'jpg', 'imagecreatefromjpeg', true, 100],
            ['mario.gif', 'image/gif', 'gif', 'imagecreatefromgif', true, null],
            ['firefox.png', 'image/png', 'png', 'imagecreatefrompng', true, -1],
            ['duke_nukem.bmp', 'image/bmp', 'bmp', null, false, null],
            ['angela_merkel.tif', 'image/tiff', 'tif', null, false, null],
            ['fish.wmf', 'image/x-wmf', 'wmf', null, false, null],
            ['inkscape_shapes.emf', 'image/x-emf', 'emf', null, false, null],
            ['inkscape_shapes_emfplus.emf', 'image/x-emf', 'emf', null, false, null],
        ];
    }

    /**
     * Get style.
     */
    public function testStyle(): void
    {
        $oImage = new Image(
            __DIR__ . '/../_files/images/earth.jpg',
            ['height' => 210, 'alignment' => Jc::CENTER]
        );

        self::assertInstanceOf('PhpOffice\\PhpWord\\Style\\Image', $oImage->getStyle());
    }

    /**
     * Get alt text.
     */
    public function testAltText(): void
    {
        $source = __DIR__ . '/../_files/images/earth.jpg';
        $altText = 'Picture of the earth from space';
        $image = new Image($source, null, false, null, $altText);
        self::assertEquals($altText, $image->getAltText());
    }

    /**
     * Test invalid local image.
     */
    public function testInvalidImageLocal(): void
    {
        $this->expectException(\PhpOffice\PhpWord\Exception\InvalidImageException::class);
        $this->expectExceptionMessage('Invalid image');
        new Image(__DIR__ . '/../_files/images/thisisnotarealimage');
    }

    /**
     * Test invalid PHP Image.
     */
    public function testInvalidImagePhp(): void
    {
        $this->expectException(\PhpOffice\PhpWord\Exception\InvalidImageException::class);
        $this->expectExceptionMessage('Invalid image');
        $object = new Image('test.php');
        $source = $object->getSource();
    }

    /**
     * Test unsupported image.
     */
    public function testUnsupportedImage(): void
    {
        $this->expectException(\PhpOffice\PhpWord\Exception\UnsupportedImageTypeException::class);
        $this->expectExceptionMessage('Unsupported image type ' . IMAGETYPE_BMP);
        //disable ssl verification, never do this in real application, you should pass the certificiate instead!!!
        $arrContextOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ];
        stream_context_set_default($arrContextOptions);
        $object = new Image(self::getRemoteBmpImageUrl());
        $source = $object->getSource();
    }

    /**
     * Get relation Id.
     */
    public function testRelationID(): void
    {
        $oImage = new Image(__DIR__ . '/../_files/images/earth.jpg', ['width' => 100]);
        $iVal = mt_rand(1, 1000);
        $oImage->setRelationId($iVal);
        self::assertEquals($iVal, $oImage->getRelationId());
    }

    /**
     * Test archived image.
     */
    public function testArchivedImage(): void
    {
        $archiveFile = __DIR__ . '/../_files/documents/reader.docx';
        $imageFile = 'word/media/image1.jpeg';
        $image = new Image("zip://{$archiveFile}#{$imageFile}");
        self::assertEquals('image/jpeg', $image->getImageType());
    }

    /**
     * Test getting image as string.
     */
    public function testImageAsStringFromFile(): void
    {
        $image = new Image(__DIR__ . '/../_files/images/earth.jpg');

        self::assertNotNull($image->getImageStringData());
        self::assertNotNull($image->getImageStringData(true));
    }

    /**
     * Test getting image from zip as string.
     */
    public function testImageAsStringFromZip(): void
    {
        $archiveFile = __DIR__ . '/../_files/documents/reader.docx';
        $imageFile = 'word/media/image1.jpeg';
        $image = new Image("zip://{$archiveFile}#{$imageFile}");

        self::assertNotNull($image->getImageStringData());
        self::assertNotNull($image->getImageStringData(true));
    }

    /**
     * Test construct from string.
     */
    public function testConstructFromString(): void
    {
        $source = file_get_contents(__DIR__ . '/../_files/images/earth.jpg');

        $image = new Image($source);
        self::assertEquals($source, $image->getSource());
        self::assertEquals(md5((string) $source), $image->getMediaId());
        self::assertEquals('image/jpeg', $image->getImageType());
        self::assertEquals('jpg', $image->getImageExtension());
        self::assertEquals('imagecreatefromstring', $image->getImageCreateFunction());
        self::assertNotNull($image->getImageFunction());
        self::assertEquals(100, $image->getImageQuality());
        self::assertTrue($image->isMemImage());

        self::assertNotNull($image->getImageStringData());
        self::assertNotNull($image->getImageStringData(true));
    }

    /**
     * Test construct from GD.
     */
    public function testConstructFromGd(): void
    {
        $source = self::getRemoteImageUrl();

        $image = new Image($source);
        self::assertEquals($source, $image->getSource());
        self::assertEquals(md5($source), $image->getMediaId());
        self::assertEquals('image/png', $image->getImageType());
        self::assertEquals('png', $image->getImageExtension());
        self::assertEquals('imagecreatefrompng', $image->getImageCreateFunction());
        self::assertNotNull($image->getImageFunction());
        self::assertEquals(-1, $image->getImageQuality());
        self::assertTrue($image->isMemImage());

        self::assertNotNull($image->getImageStringData());
        self::assertNotNull($image->getImageStringData(true));
    }

    /**
     * Test invalid string image.
     */
    public function testInvalidImageString(): void
    {
        $this->expectException(\PhpOffice\PhpWord\Exception\InvalidImageException::class);
        $this->expectExceptionMessage('Invalid image');
        $object = new Image('this_is-a_non_valid_image');
        $source = $object->getSource();
    }

    /**
     * Test Windows metafiles (WMF, EMF, EMF+).
     */
    public function testMetafile(): void
    {
        $metafiles = [
            ['fish.wmf', 'image/x-wmf', 'wmf', 217, 159],
            ['inkscape_shapes.emf', 'image/x-emf', 'emf', 200, 151],
            ['inkscape_shapes_emfplus.emf', 'image/x-emf', 'emf', 200, 151],
        ];
        foreach ($metafiles as [$source, $type, $extension, $width, $height]) {
            $image = new Image(__DIR__ . "/../_files/images/{$source}");

            self::assertTrue($image->isMetafile(), $source);
            self::assertEquals($type, $image->getImageType(), $source);
            self::assertEquals($extension, $image->getImageExtension(), $source);
            self::assertEquals($width, $image->getStyle()->getWidth(), $source);
            self::assertEquals($height, $image->getStyle()->getHeight(), $source);
            self::assertEquals('image/png', $image->getImageTypeForRendering(), $source);
            // The original image is kept, and converted to PNG for rendering
            self::assertStringEqualsFile(__DIR__ . "/../_files/images/{$source}", (string) $image->getImageString(), $source);
            $imageData = getimagesizefromstring((string) $image->getImageStringForRendering());
            self::assertIsArray($imageData, $source);
            self::assertEquals([$width, $height, IMAGETYPE_PNG], array_slice($imageData, 0, 3), $source);
        }
    }

    /**
     * Test Windows metafile from string.
     */
    public function testMetafileFromString(): void
    {
        $source = (string) file_get_contents(__DIR__ . '/../_files/images/fish.wmf');
        $image = new Image($source, ['width' => 434]);

        self::assertEquals(Image::SOURCE_STRING, $image->getSourceType());
        self::assertTrue($image->isMemImage());
        self::assertTrue($image->isMetafile());
        self::assertEquals('image/x-wmf', $image->getImageType());
        self::assertEquals('wmf', $image->getImageExtension());
        self::assertEquals(434, $image->getStyle()->getWidth());
        self::assertEquals(318, $image->getStyle()->getHeight());
        self::assertEquals($source, $image->getImageString());
    }

    /**
     * Test that the rendering of other images is not modified.
     */
    public function testNotMetafile(): void
    {
        $image = new Image(__DIR__ . '/../_files/images/earth.jpg');

        self::assertFalse($image->isMetafile());
        self::assertEquals('image/jpeg', $image->getImageTypeForRendering());
        self::assertEquals($image->getImageString(), $image->getImageStringForRendering());
    }

    /**
     * Test empty image file.
     */
    public function testEmptyImageFile(): void
    {
        $filename = (string) tempnam(Settings::getTempDir(), 'PHPWordImage');

        try {
            $this->expectException(\PhpOffice\PhpWord\Exception\InvalidImageException::class);
            new Image($filename);
        } finally {
            unlink($filename);
        }
    }

    /**
     * Test image whose file is emptied after its creation.
     */
    public function testImageStringOfEmptiedFile(): void
    {
        $filename = (string) tempnam(Settings::getTempDir(), 'PHPWordImage');
        copy(__DIR__ . '/../_files/images/earth.jpg', $filename);
        $image = new Image($filename);
        file_put_contents($filename, '');
        clearstatcache();

        self::assertNull($image->getImageStringData());
        self::assertNull($image->getImageStringForRendering());
        unlink($filename);
    }
}
