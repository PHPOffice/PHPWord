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
 * @copyright   2010-2017 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\PDF;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Writer\PDF;

/**
 * Test class for PhpOffice\PhpWord\Writer\PDF\DomPDF
 *
 * @runTestsInSeparateProcesses
 */
class DomPDFTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test construct
     */
    public function testConstruct()
    {
        define('DOMPDF_ENABLE_AUTOLOAD', false);
        $file = __DIR__ . '/../../_files/dompdf.pdf';

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Test 1');

        $rendererName = Settings::PDF_RENDERER_DOMPDF;
        $rendererLibraryPath = realpath(PHPWORD_TESTS_BASE_DIR . '/../vendor/dompdf/dompdf');
        Settings::setPdfRenderer($rendererName, $rendererLibraryPath);
        $writer = new PDF($phpWord);
        $writer->save($file);

        $this->assertFileExists($file);

        unlink($file);
    }

    /**
     * Test set/get abstract renderer properties
     */
    public function testSetGetAbstractRendererProperties()
    {
        define('DOMPDF_ENABLE_AUTOLOAD', false);

        $rendererName = Settings::PDF_RENDERER_DOMPDF;
        $rendererLibraryPath = realpath(PHPWORD_TESTS_BASE_DIR . '/../vendor/dompdf/dompdf');
        Settings::setPdfRenderer($rendererName, $rendererLibraryPath);
        $writer = new PDF(new PhpWord());

        $writer->setFont('arial');
        $this->assertEquals('arial', $writer->getFont());

        $writer->setPaperSize();
        $this->assertEquals(9, $writer->getPaperSize());

        $writer->setOrientation();
        $this->assertEquals('default', $writer->getOrientation());

        $writer->setTempDir(Settings::getTempDir());
        $this->assertEquals(Settings::getTempDir(), $writer->getTempDir());
    }
}
