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
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
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
     * Test get DOMDocument from ZipArchive exception
     *
     * @expectedException \PhpOffice\PhpWord\Exception\Exception
     * @expectedExceptionMessage Cannot find archive file.
     */
    public function testGetDomFromZipException()
    {
        $filename = __DIR__ . "/../_files/documents/foo.zip";
        $object = new XMLReader();
        $object->getDomFromZip($filename, 'yadayadaya');
    }

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
