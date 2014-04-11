<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
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
        $this->assertEquals($oImage->getIsWatermark(), false);
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
    public function testValidImageTypes()
    {
        new Image(__DIR__ . "/../_files/images/mars_noext_jpg");
        new Image(__DIR__ . "/../_files/images/mars.jpg");
        new Image(__DIR__ . "/../_files/images/mario.gif");
        new Image(__DIR__ . "/../_files/images/firefox.png");
        new Image(__DIR__ . "/../_files/images/duke_nukem.bmp");
        new Image(__DIR__ . "/../_files/images/angela_merkel.tif");
    }

    /**
     * Image not found
     *
     * @expectedException \PhpOffice\PhpWord\Exception\InvalidImageException
     */
    public function testImageNotFound()
    {
        new Image(__DIR__ . "/../_files/images/thisisnotarealimage");
    }

    /**
     * Invalid image types
     *
     * @expectedException \PhpOffice\PhpWord\Exception\UnsupportedImageTypeException
     */
    public function testInvalidImageTypes()
    {
        new Image(__DIR__ . "/../_files/images/alexz-johnson.pcx");
    }

    /**
     * Get style
     */
    public function testStyle()
    {
        $oImage = new Image(
            __DIR__ . "/../_files/images/earth.jpg",
            array('width' => 210, 'height' => 210, 'align' => 'center')
        );

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Image', $oImage->getStyle());
    }

    public function testStyleWrappingStyle()
    {

    }

    /**
     * Get relation Id
     */
    public function testRelationID()
    {
        $oImage = new Image(__DIR__ . "/../_files/images/earth.jpg");
        $iVal = rand(1, 1000);
        $oImage->setRelationId($iVal);
        $this->assertEquals($oImage->getRelationId(), $iVal);
    }

    /**
     * Get is watermark
     */
    public function testWatermark()
    {
        $oImage = new Image(__DIR__ . "/../_files/images/earth.jpg");
        $oImage->setIsWatermark(true);
        $this->assertEquals($oImage->getIsWatermark(), true);
    }

    /**
     * Test PNG
     */
    public function testPNG()
    {
        $src = __DIR__ . "/../_files/images/firefox.png";
        $oImage = new Image($src);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $oImage);
        $this->assertEquals($oImage->getSource(), $src);
        $this->assertEquals($oImage->getMediaId(), md5($src));
        $this->assertEquals($oImage->getImageCreateFunction(), 'imagecreatefrompng');
        $this->assertEquals($oImage->getImageFunction(), 'imagepng');
        $this->assertEquals($oImage->getImageExtension(), 'png');
        $this->assertEquals($oImage->getImageType(), 'image/png');
    }

    /**
     * Test GIF
     */
    public function testGIF()
    {
        $src = __DIR__ . "/../_files/images/mario.gif";
        $oImage = new Image($src);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $oImage);
        $this->assertEquals($oImage->getSource(), $src);
        $this->assertEquals($oImage->getMediaId(), md5($src));
        $this->assertEquals($oImage->getImageCreateFunction(), 'imagecreatefromgif');
        $this->assertEquals($oImage->getImageFunction(), 'imagegif');
        $this->assertEquals($oImage->getImageExtension(), 'gif');
        $this->assertEquals($oImage->getImageType(), 'image/gif');
    }

    /**
     * Test JPG
     */
    public function testJPG()
    {
        $src = __DIR__ . "/../_files/images/earth.jpg";
        $oImage = new Image($src);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $oImage);
        $this->assertEquals($oImage->getSource(), $src);
        $this->assertEquals($oImage->getMediaId(), md5($src));
        $this->assertEquals($oImage->getImageCreateFunction(), 'imagecreatefromjpeg');
        $this->assertEquals($oImage->getImageFunction(), 'imagejpeg');
        $this->assertEquals($oImage->getImageExtension(), 'jpg');
        $this->assertEquals($oImage->getImageType(), 'image/jpeg');
    }

    /**
     * Test BMP
     */
    public function testBMP()
    {
        $oImage = new Image(__DIR__ . "/../_files/images/duke_nukem.bmp");

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $oImage);
        $this->assertEquals($oImage->getImageCreateFunction(), null);
        $this->assertEquals($oImage->getImageFunction(), null);
        $this->assertEquals($oImage->getImageExtension(), 'bmp');
        $this->assertEquals($oImage->getImageType(), 'image/bmp');
    }

    /**
     * Test TIFF
     */
    public function testTIFF()
    {
        $oImage = new Image(__DIR__ . "/../_files/images/angela_merkel.tif");

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Element\\Image', $oImage);
        $this->assertEquals($oImage->getImageCreateFunction(), null);
        $this->assertEquals($oImage->getImageFunction(), null);
        $this->assertEquals($oImage->getImageType(), 'image/tiff');
    }

    /**
     * Test PHP Image
     *
     * @expectedException \PhpOffice\PhpWord\Exception\InvalidImageException
     */
    public function testPhpImage()
    {
        $object = new Image('test.php');
    }

    /**
     * Test PCX Image and Memory
     *
     * @expectedException \PhpOffice\PhpWord\Exception\UnsupportedImageTypeException
     */
    public function testPcxImage()
    {
      $object = new Image('http://samples.libav.org/image-samples/RACECAR.BMP');
    }
}
