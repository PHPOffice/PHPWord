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
 * @see         https://github.com/PHPOffice/PhpWord
 * @copyright   2010-2017 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\PDF;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Writer\WriterInterface;

/**
 * MPDF writer
 *
 * @see  http://www.mpdf1.com/
 * @since 0.11.0
 */
class MPDF extends AbstractRenderer implements WriterInterface
{
    public function __construct(PhpWord $phpWord)
    {
        if (file_exists(Settings::getPdfRendererPath() . '/mpdf.php')) {
            // MPDF version 5.* needs this file to be included, later versions not
            $this->includeFile = 'mpdf.php';
        }
        parent::__construct($phpWord);
    }

    /**
     * Save PhpWord to file.
     *
     * @param string $filename Name of the file to save as
     */
    public function save($filename = null)
    {
        $fileHandle = parent::prepareForSave($filename);

        //  PDF settings
        $paperSize = strtoupper('A4');
        $orientation = strtoupper('portrait');

        //  Create PDF
        if ($this->includeFile != null) {
            // MPDF version 5.*
            $pdf = new \mpdf();
        } else {
            // MPDF version > 6.*
            $pdf = new \Mpdf\Mpdf();
        }
        $pdf->_setPageSize($paperSize, $orientation);
        $pdf->addPage($orientation);

        // Write document properties
        $phpWord = $this->getPhpWord();
        $docProps = $phpWord->getDocInfo();
        $pdf->setTitle($docProps->getTitle());
        $pdf->setAuthor($docProps->getCreator());
        $pdf->setSubject($docProps->getSubject());
        $pdf->setKeywords($docProps->getKeywords());
        $pdf->setCreator($docProps->getCreator());

        $pdf->writeHTML($this->getContent());

        //  Write to file
        fwrite($fileHandle, $pdf->output($filename, 'S'));

        parent::restoreStateAfterSave($fileHandle);
    }
}
