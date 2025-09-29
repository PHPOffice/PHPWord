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

namespace PhpOffice\PhpWordTests\Writer;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Writer\PDF;

/**
 * Test class for PhpOffice\PhpWord\Writer\PDF.
 *
 * @runTestsInSeparateProcesses
 */
class PDFTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test normal construct.
     */
    public function testConstructDompdf(): void
    {
        define('DOMPDF_ENABLE_AUTOLOAD', false);
        $file = __DIR__ . '/../_files/temp.pdf';

        $rendererName = Settings::PDF_RENDERER_DOMPDF;
        $rendererLibraryPath = realpath(PHPWORD_TESTS_BASE_DIR . '/../vendor/dompdf/dompdf');
        self::assertNotFalse($rendererLibraryPath);
        Settings::setPdfRenderer($rendererName, $rendererLibraryPath);
        $writer = new PDF(new PhpWord());
        $writer->setFont('xyz');
        $writer->save($file);

        self::assertFileExists($file);

        unlink($file);
    }

    public function testConstructMpdf(): void
    {
        $file = __DIR__ . '/../_files/temp.pdf';

        $rendererName = Settings::PDF_RENDERER_MPDF;
        $rendererLibraryPath = realpath(PHPWORD_TESTS_BASE_DIR . '/../vendor/mpdf/mpdf');
        self::assertNotFalse($rendererLibraryPath);
        Settings::setPdfRenderer($rendererName, $rendererLibraryPath);
        $writer = new PDF(new PhpWord());
        $writer->setFont('xyz');
        $writer->save($file);

        self::assertFileExists($file);

        unlink($file);
    }

    public function testConstructTcpdf(): void
    {
        $file = __DIR__ . '/../_files/temp.pdf';

        $rendererName = Settings::PDF_RENDERER_TCPDF;
        $rendererLibraryPath = realpath(PHPWORD_TESTS_BASE_DIR . '/../vendor/tecnickcom/tcpdf');
        self::assertNotFalse($rendererLibraryPath);
        Settings::setPdfRenderer($rendererName, $rendererLibraryPath);
        $writer = new PDF(new PhpWord());
        $writer->setFont('Helvetica');
        $writer->save($file);

        self::assertFileExists($file);

        unlink($file);
    }

    /**
     * Test construct exception.
     */
    public function testConstructException(): void
    {
        $this->expectException(\PhpOffice\PhpWord\Exception\Exception::class);
        $this->expectExceptionMessage('PDF rendering library or library path has not been defined.');
        $writer = new PDF(new PhpWord());
        $writer->save('unknown.file');
    }
}
