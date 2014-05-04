<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests\Shared;

use PhpOffice\PhpWord\Shared\ZipArchive;

/**
 * Test class for PhpOffice\PhpWord\Shared\ZipArchive
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Shared\ZipArchive
 * @runTestsInSeparateProcesses
 */
class ZipArchiveTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test all methods
     *
     * @covers ::<public>
     */
    public function testAllMethods()
    {
        // Preparation
        $existingFile = __DIR__ . "/../_files/documents/sheet.xls";
        $zipFile      = __DIR__ . "/../_files/documents/ziptest.zip";
        $destination1 = __DIR__ . "/../_files/extract1";
        $destination2 = __DIR__ . "/../_files/extract2";
        $destination3 = __DIR__ . "/../_files/extract3";
        @mkdir($destination1);
        @mkdir($destination2);
        @mkdir($destination3);

        $object = new ZipArchive();
        $object->open($zipFile);
        $object->addFile($existingFile, 'xls/new.xls');
        $object->addFromString('content/string.txt', 'Test');
        $object->close();

        // Run tests
        $this->assertEquals(0, $object->locateName('xls/new.xls'));
        $this->assertFalse($object->locateName('blablabla'));

        $this->assertEquals('Test', $object->getFromName('content/string.txt'));
        $this->assertEquals('Test', $object->getFromName('/content/string.txt'));

        $this->assertFalse($object->getNameIndex(-1));
        $this->assertEquals('content/string.txt', $object->getNameIndex(1));

        $this->assertFalse($object->extractTo('blablabla'));
        $this->assertTrue($object->extractTo($destination1));
        $this->assertTrue($object->extractTo($destination2, 'xls/new.xls'));
        $this->assertFalse($object->extractTo($destination2, 'blablabla'));

        // Cleanup
        $this->deleteDir($destination1);
        $this->deleteDir($destination2);
        $this->deleteDir($destination3);
        @unlink($zipFile);
    }

    /**
     * Delete directory
     *
     * @param string $dir
     */
    private function deleteDir($dir)
    {
        foreach (scandir($dir) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            } elseif (is_file($dir . "/" . $file)) {
                unlink($dir . "/" . $file);
            } elseif (is_dir($dir . "/" . $file)) {
                $this->deleteDir($dir . "/" . $file);
            }
        }

        rmdir($dir);
    }
}
