<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @see         https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Shared;

use PhpOffice\PhpWord\Settings;

/**
 * Test class for PhpOffice\PhpWord\Shared\ZipArchive
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Shared\ZipArchive
 * @runTestsInSeparateProcesses
 */
class ZipArchiveTest extends \PHPUnit\Framework\TestCase
{
//     /**
//      * Test close method exception: Working in local, not working in Travis
//      *
//      * expectedException \PhpOffice\PhpWord\Exception\Exception
//      * expectedExceptionMessage Could not close zip file
//      * covers ::close
//      */
//     public function testCloseException()
//     {
//         $zipFile = __DIR__ . "/../_files/documents/ziptest.zip";

//         $object = new ZipArchive();
//         $object->open($zipFile, ZipArchive::CREATE);
//         $object->addFromString('content/string.txt', 'Test');

//         // Lock the file
//         $resource = fopen($zipFile, "w");
//         flock($resource, LOCK_EX);

//         // Closing the file should throws an exception
//         $object->close();

//         // Unlock the file
//         flock($resource, LOCK_UN);
//         fclose($resource);

//         @unlink($zipFile);
//     }

    /**
     * Test all methods
     *
     * @param string $zipClass
     * @covers ::<public>
     */
    public function testZipArchive($zipClass = 'ZipArchive')
    {
        // Preparation
        $existingFile = __DIR__ . '/../_files/documents/sheet.xls';
        $zipFile = __DIR__ . '/../_files/documents/ziptest.zip';
        $destination1 = __DIR__ . '/../_files/documents/extract1';
        $destination2 = __DIR__ . '/../_files/documents/extract2';
        @mkdir($destination1);
        @mkdir($destination2);

        Settings::setZipClass($zipClass);

        $object = new ZipArchive();
        $object->open($zipFile, ZipArchive::CREATE);
        $object->addFile($existingFile, 'xls/new.xls');
        $object->addFromString('content/string.txt', 'Test');
        $object->close();
        $object->open($zipFile);

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
        @unlink($zipFile);
    }

    /**
     * Test PclZip
     *
     * @covers ::<public>
     */
    public function testPCLZip()
    {
        $this->testZipArchive('PhpOffice\PhpWord\Shared\ZipArchive');
    }

    /**
     * Delete directory
     *
     * @param string $dir
     */
    private function deleteDir($dir)
    {
        foreach (scandir($dir) as $file) {
            if ('.' === $file || '..' === $file) {
                continue;
            } elseif (is_file($dir . '/' . $file)) {
                unlink($dir . '/' . $file);
            } elseif (is_dir($dir . '/' . $file)) {
                $this->deleteDir($dir . '/' . $file);
            }
        }

        rmdir($dir);
    }
}
