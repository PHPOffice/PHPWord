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
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWordTests\Shared;

use Exception;
use InvalidArgumentException;
use PhpOffice\PhpWord\Shared\XMLReader;

/**
 * Test class for XMLReader.
 *
 * @coversDefaultClass \PhpOffice\PhpWord\Shared\XMLReader
 */
class XMLReaderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test reading XML from string.
     */
    public function testDomFromString(): void
    {
        $reader = new XMLReader();
        $reader->getDomFromString('<element attr="test"><child attr="subtest">AAA</child></element>');

        self::assertTrue($reader->elementExists('/element/child'));
        self::assertEquals('AAA', $reader->getElement('/element/child')->textContent);
        self::assertEquals('AAA', $reader->getValue('/element/child'));
        self::assertEquals('test', $reader->getAttribute('attr', $reader->getElement('/element')));
        self::assertEquals('subtest', $reader->getAttribute('attr', $reader->getElement('/element'), 'child'));
    }

    /**
     * Test reading XML from zip.
     */
    public function testDomFromZip(): void
    {
        $archiveFile = __DIR__ . '/../_files/xml/reader.zip';

        $reader = new XMLReader();
        $reader->getDomFromZip($archiveFile, 'test.xml');

        self::assertTrue($reader->elementExists('/element/child'));

        self::assertFalse($reader->getDomFromZip($archiveFile, 'non_existing_xml_file.xml'));
    }

    /**
     * Office 365 add some slash before the path of XML file.
     */
    public function testDomFromZipOffice365(): void
    {
        $archiveFile = __DIR__ . '/../_files/xml/reader.zip';

        $reader = new XMLReader();
        $reader->getDomFromZip($archiveFile, '/test.xml');

        self::assertTrue($reader->elementExists('/element/child'));

        self::assertFalse($reader->getDomFromZip($archiveFile, 'non_existing_xml_file.xml'));
    }

    /**
     * Test that read from non existing archive throws exception.
     */
    public function testThrowsExceptionOnNonExistingArchive(): void
    {
        $this->expectException(Exception::class);
        $archiveFile = __DIR__ . '/../_files/xml/readers.zip';

        $reader = new XMLReader();
        $reader->getDomFromZip($archiveFile, 'test.xml');
    }

    /**
     * Test that read from invalid archive throws exception.
     */
    public function testThrowsExceptionOnZipArchiveOpenErrors(): void
    {
        /**
         * @var string
         */
        $tempPath = tempnam(sys_get_temp_dir(), 'PhpWord');

        // Simulate a corrupt archive
        file_put_contents($tempPath, mt_rand());

        $exceptionMessage = null;

        try {
            $reader = new XMLReader();
            $reader->getDomFromZip($tempPath, 'test.xml');
        } catch (Exception $e) {
            $exceptionMessage = $e->getMessage();
        }

        self::assertNotNull($exceptionMessage);

        unlink($tempPath);
    }

    /**
     * Test elements count.
     */
    public function testCountElements(): void
    {
        $reader = new XMLReader();
        $reader->getDomFromString('<element attr="test"><child>AAA</child><child>BBB</child></element>');

        self::assertEquals(2, $reader->countElements('/element/child'));
    }

    /**
     * Test read non existing elements.
     */
    public function testReturnNullOnNonExistingNode(): void
    {
        $reader = new XMLReader();
        self::assertSame(0, $reader->getElements('/element/children')->length);
        $reader->getDomFromString('<element><child>AAA</child></element>');

        self::assertNull($reader->getElement('/element/children'));
        self::assertNull($reader->getValue('/element/children'));
    }

    /**
     * Test that xpath fails if custom namespace is not registered.
     */
    public function testShouldThrowExceptionIfNamespaceIsNotKnown(): void
    {
        try {
            $reader = new XMLReader();
            $reader->getDomFromString('<element><test:child xmlns:test="http://phpword.com/my/custom/namespace">AAA</test:child></element>');

            self::assertTrue($reader->elementExists('/element/test:child'));
            self::assertEquals('AAA', $reader->getElement('/element/test:child')->textContent);
            self::fail();
        } catch (Exception $e) {
            self::assertTrue(true);
        }
    }

    /**
     * Test reading XML with manually registered namespace.
     */
    public function testShouldParseXmlWithCustomNamespace(): void
    {
        $reader = new XMLReader();
        $reader->getDomFromString('<element><test:child xmlns:test="http://phpword.com/my/custom/namespace">AAA</test:child></element>');
        $reader->registerNamespace('test', 'http://phpword.com/my/custom/namespace');

        self::assertTrue($reader->elementExists('/element/test:child'));
        self::assertEquals('AAA', $reader->getElement('/element/test:child')->textContent);
    }

    /**
     * Test that xpath fails if custom namespace is not registered.
     */
    public function testShouldThowExceptionIfTryingToRegisterNamespaceBeforeReadingDoc(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $reader = new XMLReader();
        $reader->registerNamespace('test', 'http://phpword.com/my/custom/namespace');
    }
}
