<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */
namespace PhpOffice\PhpWord\Tests\Writer\PDF;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Writer\PDF;

/**
 * Test class for PhpOffice\PhpWord\Writer\PDF\DomPDF
 *
 * @runTestsInSeparateProcesses
 */
class DomPDFTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test construct
     */
    public function testConstruct()
    {
        define('DOMPDF_ENABLE_AUTOLOAD', false);
        $file = __DIR__ . "/../../_files/temp.pdf";

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Test 1');

        $rendererName = Settings::PDF_RENDERER_DOMPDF;
        $rendererLibraryPath = realpath(PHPWORD_TESTS_BASE_DIR . '/../vendor/dompdf/dompdf');
        Settings::setPdfRenderer($rendererName, $rendererLibraryPath);
        $writer = new PDF($phpWord);
        $writer->save($file);

        $this->assertTrue(file_exists($file));

        unlink($file);
    }

    /**
     * Test set/get abstract renderer properties
     */
    public function testSetGetAbstractRendererProperties()
    {
        define('DOMPDF_ENABLE_AUTOLOAD', false);
        $file = __DIR__ . "/../../_files/temp.pdf";

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

        $writer->setTempDir(sys_get_temp_dir());
        $this->assertEquals(sys_get_temp_dir(), $writer->getTempDir());
    }
}
