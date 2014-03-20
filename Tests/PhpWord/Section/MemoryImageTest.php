<?php
namespace PhpWord\Tests\Section;

use PhpOffice\PhpWord\Section\MemoryImage;

class MemoryImageTest extends \PHPUnit_Framework_TestCase
{
    public function testPNG()
    {
        $src = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'images', 'firefox.png')
        );
        $oMemoryImage = new MemoryImage($src);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\MemoryImage', $oMemoryImage);
        $this->assertEquals($oMemoryImage->getSource(), $src);
        $this->assertEquals($oMemoryImage->getMediaId(), md5($src));
        $this->assertEquals($oMemoryImage->getImageCreateFunction(), 'imagecreatefrompng');
        $this->assertEquals($oMemoryImage->getImageFunction(), 'imagepng');
        $this->assertEquals($oMemoryImage->getImageExtension(), 'png');
        $this->assertEquals($oMemoryImage->getImageType(), 'image/png');
    }

    public function testGIF()
    {
        $src = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'images', 'mario.gif')
        );
        $oMemoryImage = new MemoryImage($src);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\MemoryImage', $oMemoryImage);
        $this->assertEquals($oMemoryImage->getSource(), $src);
        $this->assertEquals($oMemoryImage->getMediaId(), md5($src));
        $this->assertEquals($oMemoryImage->getImageCreateFunction(), 'imagecreatefromgif');
        $this->assertEquals($oMemoryImage->getImageFunction(), 'imagegif');
        $this->assertEquals($oMemoryImage->getImageExtension(), 'gif');
        $this->assertEquals($oMemoryImage->getImageType(), 'image/gif');
    }

    public function testJPG()
    {
        $src = \join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'images', 'earth.jpg')
        );
        $oMemoryImage = new MemoryImage($src);

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\MemoryImage', $oMemoryImage);
        $this->assertEquals($oMemoryImage->getSource(), $src);
        $this->assertEquals($oMemoryImage->getMediaId(), md5($src));
        $this->assertEquals($oMemoryImage->getImageCreateFunction(), 'imagecreatefromjpeg');
        $this->assertEquals($oMemoryImage->getImageFunction(), 'imagejpeg');
        $this->assertEquals($oMemoryImage->getImageExtension(), 'jpg');
        $this->assertEquals($oMemoryImage->getImageType(), 'image/jpeg');
    }

    public function testBMP()
    {
        $oMemoryImage = new MemoryImage(\join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'images', 'duke_nukem.bmp')
        ));

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Section\\MemoryImage', $oMemoryImage);
        $this->assertEquals($oMemoryImage->getImageCreateFunction(), null);
        $this->assertEquals($oMemoryImage->getImageFunction(), null);
        $this->assertEquals($oMemoryImage->getImageExtension(), null);
        $this->assertEquals($oMemoryImage->getImageType(), 'image/x-ms-bmp');
    }

    public function testStyle()
    {
        $oMemoryImage = new MemoryImage(\join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'images', 'earth.jpg')
        ), array('width' => 210, 'height' => 210, 'align' => 'center'));

        $this->assertInstanceOf('PhpOffice\\PhpWord\\Style\\Image', $oMemoryImage->getStyle());
    }

    public function testRelationID()
    {
        $oMemoryImage = new MemoryImage(\join(
            \DIRECTORY_SEPARATOR,
            array(\PHPWORD_TESTS_DIR_ROOT, '_files', 'images', 'earth.jpg')
        ));

        $iVal = rand(1, 1000);
        $oMemoryImage->setRelationId($iVal);
        $this->assertEquals($oMemoryImage->getRelationId(), $iVal);
    }
}