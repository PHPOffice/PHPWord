<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Tests\Shared;

use PhpOffice\PhpWord\Shared\XMLReader;

/**
 * Test class for PhpOffice\PhpWord\Shared\XMLReader
 *
 * @runTestsInSeparateProcesses
 * @since 0.10.0
 */
class XMLReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test get DOMDocument from ZipArchive returns false
     */
    public function testGetDomFromZipReturnsFalse()
    {
        $filename = __DIR__ . "/../_files/documents/reader.docx.zip";
        $object = new XMLReader();
        $this->assertFalse($object->getDomFromZip($filename, 'yadayadaya'));
    }

    /**
     * Test get elements returns empty
     */
    public function testGetElementsReturnsEmpty()
    {
        $object = new XMLReader();
        $this->assertEquals(array(), $object->getElements('w:document'));
    }

    /**
     * Test get element returns null
     */
    public function testGetElementReturnsNull()
    {
        $filename = __DIR__ . "/../_files/documents/reader.docx.zip";

        $object = new XMLReader();
        $object->getDomFromZip($filename, '[Content_Types].xml');
        $element = $object->getElements('*')->item(0);

        $this->assertNull($object->getElement('yadayadaya', $element));
    }
}
