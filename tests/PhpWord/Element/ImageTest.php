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
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\AbstractWebServerEmbeddedTest;
use PhpOffice\PhpWord\SimpleType\Jc;

/**
 * Test class for PhpOffice\PhpWord\Element\Image
 *
 * @runTestsInSeparateProcesses
 */
class ImageTest extends AbstractWebServerEmbeddedTest
{
    /**
     * New instance
     */
    public function testConstruct()
    {
        $src = __DIR__ . '/../_files/images/firefox.png';
        $oImage = new Image($src);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $oImage);
        $this->assertEquals($src, $oImage->getSource());
        $this->assertEquals(md5($src), $oImage->getMediaId());
        $this->assertFalse($oImage->isWatermark());
        $this->assertEquals(Image::SOURCE_LOCAL, $oImage->getSourceType());
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Image', $oImage->getStyle());
    }

    /**
     * New instance with style
     */
    public function testConstructWithStyle()
    {
        $src = __DIR__ . '/../_files/images/firefox.png';
        $oImage = new Image(
            $src,
            array(
                'width'         => 210,
                'height'        => 210,
                'alignment'     => Jc::CENTER,
                'wrappingStyle' => \PhpOffice\PhpWord\Style\Image::WRAPPING_STYLE_BEHIND,
            )
        );

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Image', $oImage->getStyle());
    }

    /**
     * Valid image types
     */
    public function testImages()
    {
        $images = array(
            array('mars.jpg', 'image/jpeg', 'jpg', 'imagecreatefromjpeg', 'imagejpeg'),
            array('mario.gif', 'image/gif', 'gif', 'imagecreatefromgif', 'imagegif'),
            array('firefox.png', 'image/png', 'png', 'imagecreatefrompng', 'imagepng'),
            array('duke_nukem.bmp', 'image/bmp', 'bmp', null, null),
            array('angela_merkel.tif', 'image/tiff', 'tif', null, null),
        );

        foreach ($images as $imageData) {
            list($source, $type, $extension, $createFunction, $imageFunction) = $imageData;
            $nam = ucfirst(strtok($source, '.'));
            $source = __DIR__ . "/../_files/images/{$source}";
            $image = new Image($source, null, null, $nam);
            $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $image);
            $this->assertEquals($source, $image->getSource());
            $this->assertEquals($nam, $image->getName());
            $this->assertEquals(md5($source), $image->getMediaId());
            $this->assertEquals($type, $image->getImageType());
            $this->assertEquals($extension, $image->getImageExtension());
            $this->assertEquals($createFunction, $image->getImageCreateFunction());
            $this->assertEquals($imageFunction, $image->getImageFunction());
            $this->assertFalse($image->isMemImage());
            $this->assertNotNull($image->getImageStringData());
        }
    }

    /**
     * Get style
     */
    public function testStyle()
    {
        $oImage = new Image(
            __DIR__ . '/../_files/images/earth.jpg',
            array('height' => 210, 'alignment' => Jc::CENTER)
        );

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Image', $oImage->getStyle());
    }

    /**
     * Test invalid local image
     *
     * @expectedException \PhpOffice\PhpWord\Exception\InvalidImageException
     */
    public function testInvalidImageLocal()
    {
        new Image(__DIR__ . '/../_files/images/thisisnotarealimage');
    }

    /**
     * Test invalid PHP Image
     *
     * @expectedException \PhpOffice\PhpWord\Exception\InvalidImageException
     */
    public function testInvalidImagePhp()
    {
        $object = new Image('test.php');
        $object->getSource();
    }

    /**
     * Test unsupported image
     *
     * @expectedException \PhpOffice\PhpWord\Exception\UnsupportedImageTypeException
     */
    public function testUnsupportedImage()
    {
        //disable ssl verification, never do this in real application, you should pass the certificiate instead!!!
        $arrContextOptions = array(
            'ssl' => array(
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ),
        );
        stream_context_set_default($arrContextOptions);
        $object = new Image(self::getRemoteBmpImageUrl());
        $object->getSource();
    }

    /**
     * Get relation Id
     */
    public function testRelationID()
    {
        $oImage = new Image(__DIR__ . '/../_files/images/earth.jpg', array('width' => 100));
        $iVal = rand(1, 1000);
        $oImage->setRelationId($iVal);
        $this->assertEquals($iVal, $oImage->getRelationId());
    }

    /**
     * Test archived image
     */
    public function testArchivedImage()
    {
        $archiveFile = __DIR__ . '/../_files/documents/reader.docx';
        $imageFile = 'word/media/image1.jpeg';
        $image = new Image("zip://{$archiveFile}#{$imageFile}");
        $this->assertEquals('image/jpeg', $image->getImageType());
    }

    /**
     * Test getting image as string
     */
    public function testImageAsStringFromFile()
    {
        $image = new Image(__DIR__ . '/../_files/images/earth.jpg');

        $this->assertNotNull($image->getImageStringData());
        $this->assertNotNull($image->getImageStringData(true));
    }

    /**
     * Test getting image from zip as string
     */
    public function testImageAsStringFromZip()
    {
        $archiveFile = __DIR__ . '/../_files/documents/reader.docx';
        $imageFile = 'word/media/image1.jpeg';
        $image = new Image("zip://{$archiveFile}#{$imageFile}");

        $this->assertNotNull($image->getImageStringData());
        $this->assertNotNull($image->getImageStringData(true));
    }

    /**
     * Test construct from string
     */
    public function testConstructFromString()
    {
        $source = file_get_contents(__DIR__ . '/../_files/images/earth.jpg');

        $image = new Image($source);
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $image);
        $this->assertEquals($source, $image->getSource());
        $this->assertEquals(md5($source), $image->getMediaId());
        $this->assertEquals('image/jpeg', $image->getImageType());
        $this->assertEquals('jpg', $image->getImageExtension());
        $this->assertEquals('imagecreatefromstring', $image->getImageCreateFunction());
        $this->assertEquals('imagejpeg', $image->getImageFunction());
        $this->assertTrue($image->isMemImage());

        $this->assertNotNull($image->getImageStringData());
        $this->assertNotNull($image->getImageStringData(true));
    }

    /**
     * Test construct from GD
     */
    public function testConstructFromGd()
    {
        $source = self::getRemoteImageUrl();

        $image = new Image($source);
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $image);
        $this->assertEquals($source, $image->getSource());
        $this->assertEquals(md5($source), $image->getMediaId());
        $this->assertEquals('image/png', $image->getImageType());
        $this->assertEquals('png', $image->getImageExtension());
        $this->assertEquals('imagecreatefrompng', $image->getImageCreateFunction());
        $this->assertEquals('imagepng', $image->getImageFunction());
        $this->assertTrue($image->isMemImage());

        $this->assertNotNull($image->getImageStringData());
        $this->assertNotNull($image->getImageStringData(true));
    }

    /**
     * Test invalid string image
     *
     * @expectedException \PhpOffice\PhpWord\Exception\InvalidImageException
     */
    public function testInvalidImageString()
    {
        $object = new Image('this_is-a_non_valid_image');
        $object->getSource();
    }
}
