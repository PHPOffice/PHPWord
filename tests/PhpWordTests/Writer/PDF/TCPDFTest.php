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

namespace PhpOffice\PhpWordTests\Writer\PDF;

use Exception;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Writer\PDF;

/**
 * Test class for PhpOffice\PhpWord\Writer\PDF\TCPDF.
 */
class TCPDFTest extends \PHPUnit\Framework\TestCase
{
    protected function tearDown(): void
    {
        Settings::restoreDefaults();
        Style::resetStyles();
    }

    /**
     * Test construct.
     */
    public function testConstruct(): void
    {
        $phpWord = new PhpWord();
        $phpWord->setDefaultParagraphStyle(['spaceBefore' => 0, 'spaceAfter' => 0]);
        $section = $phpWord->addSection();
        $section->addText('Test 1');

        $writer = new PDF\TCPDF($phpWord);
        $writer->setFont('Helvetica');
        ob_start();
        $writer->save('php://output');
        $contents = (string) ob_get_clean();
        self::assertSame('%PDF', substr($contents, 0, 4));
    }

    /**
     * Test set/get abstract renderer options.
     */
    public function testSetGetAbstractRendererOptions(): void
    {
        $rendererName = Settings::PDF_RENDERER_TCPDF;
        $rendererLibraryPath = realpath(PHPWORD_TESTS_BASE_DIR . '/../vendor/tecnickcom/tcpdf');
        self::assertNotFalse($rendererLibraryPath);
        Settings::setPdfRenderer($rendererName, $rendererLibraryPath);
        Settings::setPdfRendererOptions([
            'font' => 'Arial',
        ]);
        $writer = new PDF(new PhpWord());
        self::assertEquals('Arial', $writer->getFont());
    }

    public function testSectionPageBreak(): void
    {
        $rendererName = Settings::PDF_RENDERER_TCPDF;
        $rendererLibraryPath = realpath(PHPWORD_TESTS_BASE_DIR . '/../vendor/tecnickcom/tcpdf');
        self::assertNotFalse($rendererLibraryPath);
        Settings::setPdfRenderer($rendererName, $rendererLibraryPath);
        $phpWord = new PhpWord();
        $section1 = $phpWord->addSection();
        $section1->addText('This is section 1.');
        $section2 = $phpWord->addSection();
        $section2->addText('This is section 2.');
        $writer = new PDF($phpWord);
        $content = $writer->getContent();
        self::assertStringContainsString("<div style='page: page1'>", $content);
        self::assertStringContainsString('<div style="page: page2; page-break-before:always;">', $content);
    }

    /**
     * @runInSeparateProcess
     *
     * @preserveGlobalState disabled
     */
    public function testExceptionRatherThanDie(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Could not include font definition file');
        $phpWord = new PhpWord();
        $section1 = $phpWord->addSection();
        $section1->addText('This is section 1.');
        $section2 = $phpWord->addSection();
        $section2->addText('This is section 2.');
        $writer = new PDF\TcpdfNoDie($phpWord);
        $writer->setFont('xyz');
        $writer->save('php://memory');
    }
}
