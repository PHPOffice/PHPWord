<?php
namespace PHPWord\Tests\Shared;

use PhpOffice\PhpWord\Shared\File;

/**
 * @package                     PHPWord\Tests
 * @coversDefaultClass          PhpOffice\PhpWord\Shared\File
 * @runTestsInSeparateProcesses
 */
class FileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test file_exists()
     */
    public function testFileExists()
    {
        $dir = join(DIRECTORY_SEPARATOR, array(PHPWORD_TESTS_DIR_ROOT, '_files', 'templates'));
        chdir($dir);
        $this->assertTrue(File::file_exists('blank.docx'));
    }
    /**
     * Test file_exists()
     */
    public function testNoFileExists()
    {
        $dir = join(DIRECTORY_SEPARATOR, array(PHPWORD_TESTS_DIR_ROOT, '_files', 'templates'));
        chdir($dir);
        $this->assertFalse(File::file_exists('404.docx'));
    }

    /**
     * Test realpath()
     */
    public function testRealpath()
    {
        $dir = join(DIRECTORY_SEPARATOR, array(PHPWORD_TESTS_DIR_ROOT, '_files', 'templates'));
        chdir($dir);
        $file     = 'blank.docx';
        $expected = $dir . DIRECTORY_SEPARATOR . $file;
        $this->assertEquals($expected, File::realpath($file));
    }
}