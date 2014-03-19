<?php
namespace PHPWord\Tests\Shared;

use PHPWord_Shared_File;

/**
 * Class FileTest
 *
 * @package             PHPWord\Tests
 * @coversDefaultClass  PHPWord_Shared_File
 * @runTestsInSeparateProcesses
 */
class FileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test file_exists()
     */
    public function testFileExists()
    {
        $dir = join(DIRECTORY_SEPARATOR, array(
            PHPWORD_TESTS_DIR_ROOT,
            '_files',
            'templates'
        ));
        chdir($dir);
        $this->assertTrue(PHPWord_Shared_File::file_exists('blank.docx'));
    }
    /**
     * Test file_exists()
     */
    public function testNoFileExists()
    {
        $dir = join(DIRECTORY_SEPARATOR, array(
            PHPWORD_TESTS_DIR_ROOT,
            '_files',
            'templates'
        ));
        chdir($dir);
        $this->assertFalse(PHPWord_Shared_File::file_exists('404.docx'));
    }

    /**
     * Test realpath()
     */
    public function testRealpath()
    {
        $dir = join(DIRECTORY_SEPARATOR, array(
            PHPWORD_TESTS_DIR_ROOT,
            '_files',
            'templates'
        ));
        chdir($dir);
        $file     = 'blank.docx';
        $expected = $dir . DIRECTORY_SEPARATOR . $file;
        $this->assertEquals($expected, PHPWord_Shared_File::realpath($file));
    }

    /**
     * @covers PHPWord_Shared_File::imagetype
     * @covers PHPWord_Shared_File::fallbackImagetype
     */
    public function testImagetype()
    {
        $filename = PHPWORD_TESTS_DIR_ROOT . "/_files/images/mars_noext_jpg";
        $this->assertEquals(PHPWord_Shared_File::IMAGETYPE_JPEG, PHPWord_Shared_File::imagetype($filename, true));
        $this->assertEquals(PHPWord_Shared_File::IMAGETYPE_JPEG, PHPWord_Shared_File::imagetype($filename));

        $filename = PHPWORD_TESTS_DIR_ROOT . "/_files/images/mars.jpg";
        $this->assertEquals(PHPWord_Shared_File::IMAGETYPE_JPEG, PHPWord_Shared_File::imagetype($filename, true));
        $this->assertEquals(PHPWord_Shared_File::IMAGETYPE_JPEG, PHPWord_Shared_File::imagetype($filename));

        $filename = PHPWORD_TESTS_DIR_ROOT . "/_files/images/mario.gif";
        $this->assertEquals(PHPWord_Shared_File::IMAGETYPE_GIF, PHPWord_Shared_File::imagetype($filename, true));
        $this->assertEquals(PHPWord_Shared_File::IMAGETYPE_GIF, PHPWord_Shared_File::imagetype($filename));

        $filename = PHPWORD_TESTS_DIR_ROOT . "/_files/images/firefox.png";
        $this->assertEquals(PHPWord_Shared_File::IMAGETYPE_PNG, PHPWord_Shared_File::imagetype($filename, true));
        $this->assertEquals(PHPWord_Shared_File::IMAGETYPE_PNG, PHPWord_Shared_File::imagetype($filename));

        $filename = PHPWORD_TESTS_DIR_ROOT . "/_files/images/duke_nukem.bmp";
        $this->assertEquals(PHPWord_Shared_File::IMAGETYPE_BMP, PHPWord_Shared_File::imagetype($filename, true));
        $this->assertEquals(PHPWord_Shared_File::IMAGETYPE_BMP, PHPWord_Shared_File::imagetype($filename));

        $filename = PHPWORD_TESTS_DIR_ROOT . "/_files/images/angela_merkel.tif";
        $this->assertEquals(PHPWord_Shared_File::IMAGETYPE_TIFF, PHPWord_Shared_File::imagetype($filename, true));
        $this->assertEquals(PHPWord_Shared_File::IMAGETYPE_TIFF, PHPWord_Shared_File::imagetype($filename));

        $filename = PHPWORD_TESTS_DIR_ROOT . "/_files/images/alexz-johnson.pcx";
        $this->assertFalse(PHPWord_Shared_File::imagetype($filename, true));
        $this->assertFalse(PHPWord_Shared_File::imagetype($filename));
    }
}