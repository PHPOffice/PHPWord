<?php
namespace PhpOffice\PhpWord\Tests\Shared;

use PhpOffice\PhpWord\Shared\File;

/**
 * @coversDefaultClass          \PhpOffice\PhpWord\Shared\File
 * @runTestsInSeparateProcesses
 */
class FileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test file_exists()
     */
    public function testFileExists()
    {
        $dir = __DIR__ . "/../_files/templates";
        chdir($dir);
        $this->assertTrue(File::fileExists('blank.docx'));
    }
    /**
     * Test file_exists()
     */
    public function testNoFileExists()
    {
        $dir = __DIR__ . "/../_files/templates";
        chdir($dir);
        $this->assertFalse(File::fileExists('404.docx'));
    }

    /**
     * Test realpath()
     */
    public function testRealpath()
    {
        $dir = realpath(__DIR__ . "/../_files/templates");
        chdir($dir);
        $file     = 'blank.docx';
        $expected = $dir . \DIRECTORY_SEPARATOR . $file;
        $this->assertEquals($expected, File::realpath($file));
    }
}
