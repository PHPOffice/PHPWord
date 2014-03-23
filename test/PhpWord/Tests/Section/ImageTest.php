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
        $src = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_BASE_DIR, 'data', 'images', 'firefox.png')
        );
        $oImage = new Image($src);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\Image', $oImage);
        $this->assertEquals($oImage->getSource(), $src);
        $this->assertEquals($oImage->getMediaId(), md5($src));
        $this->assertEquals($oImage->getIsWatermark(), false);
        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Image', $oImage->getStyle());
    }

    public function testConstructWithStyle()
    {
        $src = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_BASE_DIR, 'data', 'images', 'firefox.png')
        );
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
        new Image(\PHPWORD_TESTS_BASE_DIR . "/data/images/mars_noext_jpg");
        new Image(\PHPWORD_TESTS_BASE_DIR . "/data/images/mars.jpg");
        new Image(\PHPWORD_TESTS_BASE_DIR . "/data/images/mario.gif");
        new Image(\PHPWORD_TESTS_BASE_DIR . "/data/images/firefox.png");
        new Image(\PHPWORD_TESTS_BASE_DIR . "/data/images/duke_nukem.bmp");
        new Image(\PHPWORD_TESTS_BASE_DIR . "/data/images/angela_merkel.tif");
    }

    /**
     * @expectedException \PhpOffice\PhpWord\Exceptions\InvalidImageException
     * @covers            ::__construct
     */
    public function testImageNotFound()
    {
        new Image(\PHPWORD_TESTS_BASE_DIR . "/data/images/thisisnotarealimage");
    }

    /**
     * @expectedException \PhpOffice\PhpWord\Exceptions\UnsupportedImageTypeException
     * @covers            ::__construct
     */
    public function testInvalidImageTypes()
    {
        new Image(\PHPWORD_TESTS_BASE_DIR . "/data/images/alexz-johnson.pcx");
    }

    public function testStyle()
    {
        $oImage = new Image(\join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_BASE_DIR, 'data', 'images', 'earth.jpg')
        ), array('width' => 210, 'height' => 210, 'align' => 'center'));

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Image', $oImage->getStyle());
    }

    public function testRelationID()
    {
        $oImage = new Image(\join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_BASE_DIR, 'data', 'images', 'earth.jpg')
        ));

        $iVal = rand(1, 1000);
        $oImage->setRelationId($iVal);
        $this->assertEquals($oImage->getRelationId(), $iVal);
    }

    public function testWatermark()
    {
        $oImage = new Image(\join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_BASE_DIR, 'data', 'images', 'earth.jpg')
        ));

        $oImage->setIsWatermark(true);
        $this->assertEquals($oImage->getIsWatermark(), true);
    }
}
