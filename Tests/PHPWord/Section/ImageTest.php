<?php
namespace PHPWord\Tests\Section;

use PHPWord_Section_Image;
use PHPWord_Style_Image;

class ImageTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $src = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'images', 'firefox.png')
        );
        $oImage = new PHPWord_Section_Image($src);

        $this->assertInstanceOf('PHPWord_Section_Image', $oImage);
        $this->assertEquals($oImage->getSource(), $src);
        $this->assertEquals($oImage->getMediaId(), md5($src));
        $this->assertEquals($oImage->getIsWatermark(), false);
        $this->assertInstanceOf('PHPWord_Style_Image', $oImage->getStyle());
    }

    public function testConstructWithStyle()
    {
        $src = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'images', 'firefox.png')
        );
        $oImage = new PHPWord_Section_Image(
            $src,
            array('width' => 210, 'height' => 210, 'align' => 'center',
                'wrappingStyle' => \PHPWord_Style_Image::WRAPPING_STYLE_BEHIND)
        );

        $this->assertInstanceOf('PHPWord_Style_Image', $oImage->getStyle());
    }

    /**
     * @covers PHPWord_Section_Image::__construct
     */
    public function testValidImageTypes()
    {
        new PHPWord_Section_Image(PHPWORD_TESTS_DIR_ROOT . "/_files/images/mars_noext_jpg");
        new PHPWord_Section_Image(PHPWORD_TESTS_DIR_ROOT . "/_files/images/mars.jpg");
        new PHPWord_Section_Image(PHPWORD_TESTS_DIR_ROOT . "/_files/images/mario.gif");
        new PHPWord_Section_Image(PHPWORD_TESTS_DIR_ROOT . "/_files/images/firefox.png");
        new PHPWord_Section_Image(PHPWORD_TESTS_DIR_ROOT . "/_files/images/duke_nukem.bmp");
        new PHPWord_Section_Image(PHPWORD_TESTS_DIR_ROOT . "/_files/images/angela_merkel.tif");
    }

    /**
     * @expectedException \PhpOffice\PhpWord\Exceptions\InvalidImageException
     * @covers PHPWord_Section_Image::__construct
     */
    public function testImageNotFound()
    {
        new PHPWord_Section_Image(PHPWORD_TESTS_DIR_ROOT . "/_files/images/thisisnotarealimage");
    }

    /**
     * @expectedException \PhpOffice\PhpWord\Exceptions\UnsupportedImageTypeException
     * @covers PHPWord_Section_Image::__construct
     */
    public function testInvalidImageTypes()
    {
        new PHPWord_Section_Image(PHPWORD_TESTS_DIR_ROOT . "/_files/images/alexz-johnson.pcx");
    }

    public function testStyle()
    {
        $oImage = new PHPWord_Section_Image(\join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'images', 'earth.jpg')
        ), array('width' => 210, 'height' => 210, 'align' => 'center'));

        $this->assertInstanceOf('PHPWord_Style_Image', $oImage->getStyle());
    }

    public function testRelationID()
    {
        $oImage = new PHPWord_Section_Image(\join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'images', 'earth.jpg')
        ));

        $iVal = rand(1, 1000);
        $oImage->setRelationId($iVal);
        $this->assertEquals($oImage->getRelationId(), $iVal);
    }

    public function testWatermark()
    {
        $oImage = new PHPWord_Section_Image(\join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'images', 'earth.jpg')
        ));

        $oImage->setIsWatermark(true);
        $this->assertEquals($oImage->getIsWatermark(), true);
    }
}
