<?php
namespace PhpOffice\PhpWord\Tests\Section;

use PhpOffice\PhpWord\Section\Image;

/**
 * @coversDefaultClass \PhpOffice\PhpWord\Section\Image
 */
class ImageTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $src = __DIR__ . "/../_files/images/firefox.png";
        $oImage = new Image($src);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Image', $oImage);
        $this->assertEquals($oImage->getSource(), $src);
        $this->assertEquals($oImage->getMediaId(), md5($src));
        $this->assertEquals($oImage->getIsWatermark(), false);
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Image', $oImage->getStyle());
    }

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
     * @covers ::__construct
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
     * @expectedException \PhpOffice\PhpWord\Exceptions\InvalidImageException
     * @covers            ::__construct
     */
    public function testImageNotFound()
    {
        new Image(__DIR__ . "/../_files/images/thisisnotarealimage");
    }

    /**
     * @expectedException \PhpOffice\PhpWord\Exceptions\UnsupportedImageTypeException
     * @covers            ::__construct
     */
    public function testInvalidImageTypes()
    {
        new Image(__DIR__ . "/../_files/images/alexz-johnson.pcx");
    }

    public function testStyle()
    {
        $oImage = new Image(
            __DIR__ . "/../_files/images/earth.jpg",
            array('width' => 210, 'height' => 210, 'align' => 'center')
        );

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Image', $oImage->getStyle());
    }

    public function testRelationID()
    {
        $oImage = new Image(__DIR__ . "/../_files/images/earth.jpg");
        $iVal = rand(1, 1000);
        $oImage->setRelationId($iVal);
        $this->assertEquals($oImage->getRelationId(), $iVal);
    }

    public function testWatermark()
    {
        $oImage = new Image(__DIR__ . "/../_files/images/earth.jpg");
        $oImage->setIsWatermark(true);
        $this->assertEquals($oImage->getIsWatermark(), true);
    }
    public function testPNG()
    {
        $src = __DIR__ . "/../_files/images/firefox.png";
        $oImage = new Image($src);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Image', $oImage);
        $this->assertEquals($oImage->getSource(), $src);
        $this->assertEquals($oImage->getMediaId(), md5($src));
        $this->assertEquals($oImage->getImageCreateFunction(), 'imagecreatefrompng');
        $this->assertEquals($oImage->getImageFunction(), 'imagepng');
        $this->assertEquals($oImage->getImageExtension(), 'png');
        $this->assertEquals($oImage->getImageType(), 'image/png');
    }

    public function testGIF()
    {
        $src = __DIR__ . "/../_files/images/mario.gif";
        $oImage = new Image($src);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Image', $oImage);
        $this->assertEquals($oImage->getSource(), $src);
        $this->assertEquals($oImage->getMediaId(), md5($src));
        $this->assertEquals($oImage->getImageCreateFunction(), 'imagecreatefromgif');
        $this->assertEquals($oImage->getImageFunction(), 'imagegif');
        $this->assertEquals($oImage->getImageExtension(), 'gif');
        $this->assertEquals($oImage->getImageType(), 'image/gif');
    }

    public function testJPG()
    {
        $src = __DIR__ . "/../_files/images/earth.jpg";
        $oImage = new Image($src);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Image', $oImage);
        $this->assertEquals($oImage->getSource(), $src);
        $this->assertEquals($oImage->getMediaId(), md5($src));
        $this->assertEquals($oImage->getImageCreateFunction(), 'imagecreatefromjpeg');
        $this->assertEquals($oImage->getImageFunction(), 'imagejpeg');
        $this->assertEquals($oImage->getImageExtension(), 'jpg');
        $this->assertEquals($oImage->getImageType(), 'image/jpeg');
    }

    public function testBMP()
    {
        $oImage = new Image(__DIR__ . "/../_files/images/duke_nukem.bmp");

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Image', $oImage);
        $this->assertEquals($oImage->getImageCreateFunction(), null);
        $this->assertEquals($oImage->getImageFunction(), null);
        $this->assertEquals($oImage->getImageExtension(), 'bmp');
        $this->assertEquals($oImage->getImageType(), 'image/bmp');
    }
}
