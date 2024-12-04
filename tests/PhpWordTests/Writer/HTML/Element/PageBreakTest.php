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

namespace PhpOffice\PhpWordTests\Writer\HTML\Element;

use PhpOffice\PhpWord\Element\PageBreak as BasePageBreak;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Writer\HTML;
use PhpOffice\PhpWord\Writer\HTML\Element\PageBreak;
use PhpOffice\PhpWord\Writer\PDF;
use PHPUnit\Framework\TestCase;

class PageBreakTest extends TestCase
{
    public function testHTML(): void
    {
        $writer = new HTML();
        $object = new PageBreak($writer, new BasePageBreak());

        self::assertEquals('<div style="page-break-before: always; height: 0; margin: 0; padding: 0; overflow: hidden;">&#160;</div>' . PHP_EOL, $object->write());
    }

    public function testMPDF(): void
    {
        $rendererName = Settings::PDF_RENDERER_MPDF;
        $rendererLibraryPath = realpath(PHPWORD_TESTS_BASE_DIR . '/../vendor/mpdf/mpdf');
        Settings::setPdfRenderer($rendererName, $rendererLibraryPath);
        $writer = new PDF(new PhpWord());

        $object = new PageBreak($writer->getRenderer(), new BasePageBreak());

        self::assertEquals('<pagebreak style="page-break-before: always;" pagebreak="true"></pagebreak>', $object->write());
    }

    public function testDOMPDF(): void
    {
        $rendererName = Settings::PDF_RENDERER_DOMPDF;
        $rendererLibraryPath = realpath(PHPWORD_TESTS_BASE_DIR . '/../vendor/dompdf/dompdf');
        Settings::setPdfRenderer($rendererName, $rendererLibraryPath);
        $writer = new PDF(new PhpWord());

        $object = new PageBreak($writer->getRenderer(), new BasePageBreak());

        self::assertEquals('<pagebreak style="page-break-before: always;" pagebreak="true"></pagebreak>', $object->write());
    }

    public function testTCPDF(): void
    {
        $rendererName = Settings::PDF_RENDERER_TCPDF;
        $rendererLibraryPath = realpath(PHPWORD_TESTS_BASE_DIR . '/../vendor/tecnickcom/tcpdf');
        Settings::setPdfRenderer($rendererName, $rendererLibraryPath);
        $writer = new PDF(new PhpWord());

        $object = new PageBreak($writer->getRenderer(), new BasePageBreak());

        self::assertEquals('<br pagebreak="true"/>', $object->write());
    }
}
