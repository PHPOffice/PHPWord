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
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Tests\Element;

use PhpOffice\PhpWord\Element\Image;

/**
 * Test class for PhpOffice\PhpWord\Element\Image
 *
 * @runTestsInSeparateProcesses
 */
class ImageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * New instance
     */
    public function testConstruct()
    {
        $src = __DIR__ . "/../_files/images/firefox.png";
        $oImage = new Image($src);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $oImage);
        $this->assertEquals($oImage->getSource(), $src);
        $this->assertEquals($oImage->getMediaId(), md5($src));
        $this->assertEquals($oImage->isWatermark(), false);
        $this->assertEquals($oImage->getSourceType(), Image::SOURCE_LOCAL);
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Image', $oImage->getStyle());
    }

    /**
     * New instance with style
     */
    public function testConstructWithStyle()
    {
        $src = __DIR__ . "/../_files/images/firefox.png";
        $oImage = new Image(
            $src,
            array('width' => 210, 'height' => 210, 'align' => 'center',
                'wrappingStyle' => \PhpOffice\PhpWord\Style\Image::WRAPPING_STYLE_BEHIND)
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
            $source = __DIR__ . "/../_files/images/" . $source;
            $image = new Image($source);
            $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $image);
            $this->assertEquals($image->getSource(), $source);
            $this->assertEquals($image->getMediaId(), md5($source));
            $this->assertEquals($image->getImageType(), $type);
            $this->assertEquals($image->getImageExtension(), $extension);
            $this->assertEquals($image->getImageCreateFunction(), $createFunction);
            $this->assertEquals($image->getImageFunction(), $imageFunction);
            $this->assertFalse($image->isMemImage());
        }
    }

    /**
     * Get style
     */
    public function testStyle()
    {
        $oImage = new Image(
            __DIR__ . "/../_files/images/earth.jpg",
            array('height' => 210, 'align' => 'center')
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
        new Image(__DIR__ . "/../_files/images/thisisnotarealimage");
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
        $object = new Image('http://samples.libav.org/image-samples/RACECAR.BMP');
        $object->getSource();
    }

    /**
     * Get relation Id
     */
    public function testRelationID()
    {
        $oImage = new Image(__DIR__ . "/../_files/images/earth.jpg", array('width' => 100));
        $iVal = rand(1, 1000);
        $oImage->setRelationId($iVal);
        $this->assertEquals($oImage->getRelationId(), $iVal);
    }

    /**
     * Test archived image
     */
    public function testArchivedImage()
    {
        $archiveFile = __DIR__ . "/../_files/documents/reader.docx";
        $imageFile = 'word/media/image1.jpeg';
        $image = new Image("zip://{$archiveFile}#{$imageFile}");
        $this->assertEquals('image/jpeg', $image->getImageType());
    }
}
