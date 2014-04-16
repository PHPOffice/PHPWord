<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PhpWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer\PDF;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Exception\Exception;

/**
 * DomPDF writer
 */
class DomPDF extends AbstractRenderer implements \PhpOffice\PhpWord\Writer\WriterInterface
{
    /**
     * Create new instance
     *
     * @param PhpWord $phpWord PhpWord object
     */
    public function __construct(PhpWord $phpWord)
    {
        parent::__construct($phpWord);
        $configFile = Settings::getPdfRendererPath() . '/dompdf_config.inc.php';
        if (file_exists($configFile)) {
            require_once $configFile;
        } else {
            throw new Exception('Unable to load PDF Rendering library');
        }
    }

    /**
     * Save PhpWord to file
     *
     * @param string $pFilename Name of the file to save as
     * @throws  Exception
     */
    public function save($pFilename = null)
    {
        $fileHandle = parent::prepareForSave($pFilename);

        //  Default PDF paper size
        $paperSize = 'A4';
        $orientation = 'P';
        $printPaperSize = 9;

        if (isset(self::$paperSizes[$printPaperSize])) {
            $paperSize = self::$paperSizes[$printPaperSize];
        }

        $orientation = ($orientation == 'L') ? 'landscape' : 'portrait';

        //  Create PDF
        $pdf = new \DOMPDF();
        $pdf->set_paper(strtolower($paperSize), $orientation);

        $pdf->load_html($this->writeDocument());
        $pdf->render();

        //  Write to file
        fwrite($fileHandle, $pdf->output());

        parent::restoreStateAfterSave($fileHandle);
    }
}
