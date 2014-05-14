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
 * @link        https://github.com/PHPOffice/PhpWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\PDF;

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;

/**
 * DomPDF writer
 */
class DomPDF extends AbstractRenderer implements \PhpOffice\PhpWord\Writer\WriterInterface
{
    /**
     * Create new instance
     *
     * @param PhpWord $phpWord PhpWord object
     * @throws \PhpOffice\PhpWord\Exception\Exception
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
