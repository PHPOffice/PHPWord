<?php
namespace PHPWord\Tests\Shared;

use PHPUnit_Framework_TestCase;
use PHPWord_Shared_File;

/**
 * Class FileTest
 *
 * @package PHPWord\Tests
 * @runTestsInSeparateProcesses
 */
class FileTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test file_exists()
     */
    public function testFile_exists()
    {
        $dir = join(DIRECTORY_SEPARATOR,
            array(PHPWORD_TESTS_DIR_ROOT, '_files', 'templates')
        );
        chdir($dir);
        $this->assertTrue(PHPWord_Shared_File::file_exists('blank.docx'));
    }

    /**
     * Test realpath()
     */
    public function testRealpath()
    {
        $dir = join(DIRECTORY_SEPARATOR,
            array(PHPWORD_TESTS_DIR_ROOT, '_files', 'templates'));
        chdir($dir);
        $file = 'blank.docx';
        $expected = $dir . DIRECTORY_SEPARATOR . $file;
        $this->assertEquals($expected, PHPWord_Shared_File::realpath($file));
    }

}
