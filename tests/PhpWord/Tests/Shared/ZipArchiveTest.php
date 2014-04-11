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
 * @runTestsInSeparateProcesses
 */
class ZipArchiveTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test add from file and string
     */
    public function testAdd()
    {
        $existingFile = __DIR__ . "/../_files/documents/sheet.xls";
        $zipFile = __DIR__ . "/../_files/documents/ziptest.zip";
        $object = new ZipArchive();
        $object->open($zipFile);
        $object->addFile($existingFile, 'xls/new.xls');
        $object->addFromString('content/string.txt', 'Test');

        $this->assertTrue($object->locateName('xls/new.xls'));
        $this->assertEquals('Test', $object->getFromName('content/string.txt'));
        $this->assertEquals('Test', $object->getFromName('/content/string.txt'));

        unlink($zipFile);
    }

    public function testLocate()
    {
      $existingFile = __DIR__ . "/../_files/documents/sheet.xls";
      $zipFile = __DIR__ . "/../_files/documents/ziptest.zip";
      $object = new ZipArchive();
      $object->open($zipFile);
      $object->addFile($existingFile, 'xls/new.xls');
      $object->addFromString('content/string.txt', 'Test');

      $this->assertEquals(1, $object->locateName('content/string.txt'));
      $this->assertFalse($object->locateName('blablabla'));

      unlink($zipFile);
    }

    public function testNameIndex()
    {
      $existingFile = __DIR__ . "/../_files/documents/sheet.xls";
      $zipFile = __DIR__ . "/../_files/documents/ziptest.zip";
      $object = new ZipArchive();
      $object->open($zipFile);
      $object->addFile($existingFile, 'xls/new.xls');
      $object->addFromString('content/string.txt', 'Test');

      $this->assertFalse($object->getNameIndex(-1));
      $this->assertEquals('content/string.txt', $object->getNameIndex(1));

      unlink($zipFile);
    }
}
