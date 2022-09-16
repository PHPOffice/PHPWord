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

namespace PhpOffice\PhpWord;

/**
 * Test class for PhpOffice\PhpWord\IOFactory.
 *
 * @runTestsInSeparateProcesses
 */
class IOFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Create existing writer.
     */
    public function testExistingWriterCanBeCreated(): void
    {
        self::assertInstanceOf(
            'PhpOffice\\PhpWord\\Writer\\Word2007',
            IOFactory::createWriter(new PhpWord(), 'Word2007')
        );
    }

    /**
     * Create non-existing writer.
     */
    public function testNonexistentWriterCanNotBeCreated(): void
    {
        $this->expectException(\PhpOffice\PhpWord\Exception\Exception::class);
        IOFactory::createWriter(new PhpWord(), 'Word2006');
    }

    /**
     * Create existing reader.
     */
    public function testExistingReaderCanBeCreated(): void
    {
        self::assertInstanceOf(
            'PhpOffice\\PhpWord\\Reader\\Word2007',
            IOFactory::createReader('Word2007')
        );
    }

    /**
     * Create non-existing reader.
     */
    public function testNonexistentReaderCanNotBeCreated(): void
    {
        $this->expectException(\PhpOffice\PhpWord\Exception\Exception::class);
        IOFactory::createReader('Word2006');
    }

    /**
     * Load document.
     */
    public function testLoad(): void
    {
        $file = __DIR__ . '/_files/templates/blank.docx';
        self::assertInstanceOf(
            'PhpOffice\\PhpWord\\PhpWord',
            IOFactory::load($file)
        );
    }
}
