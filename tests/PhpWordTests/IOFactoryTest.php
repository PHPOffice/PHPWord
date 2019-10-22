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

namespace PhpOffice\PhpWordTests;

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Writer\HTML;
use PhpOffice\PhpWord\Writer\ODText;
use PhpOffice\PhpWord\Writer\PDF;
use PhpOffice\PhpWord\Writer\RTF;
use PhpOffice\PhpWord\Writer\Word2007;
use PHPUnit\Framework\TestCase;

/**
 * Test class for PhpOffice\PhpWord\IOFactory.
 *
 * @runTestsInSeparateProcesses
 */
class IOFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        $rendererName = Settings::PDF_RENDERER_DOMPDF;
        $rendererLibraryPath = realpath(PHPWORD_TESTS_BASE_DIR . '/../vendor/dompdf/dompdf');
        Settings::setPdfRenderer($rendererName, $rendererLibraryPath);
    }

    /**
     * Create all possible writers.
     *
     * @dataProvider providerCreateWriter
     */
    public function testCreateWriter(string $name, string $expected): void
    {
        $phpWord = new PhpWord();
        $actual = IOFactory::createWriter($phpWord, $name);
        self::assertInstanceOf($expected, $actual);
    }

    public function providerCreateWriter(): iterable
    {
        return [
            ['ODText', ODText::class],
            ['RTF', RTF::class],
            ['Word2007', Word2007::class],
            ['HTML', HTML::class],
            ['PDF', PDF::class],
        ];
    }

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
        $this->expectException(Exception::class);
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
        $this->expectException(Exception::class);
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
