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
 * @copyright   2010-2015 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\PDF;

use PhpOffice\PhpWord\Writer\WriterInterface;

/**
 * TCPDF writer
 *
 * @link http://www.tcpdf.org/
 * @since 0.11.0
 */
class TCPDF extends AbstractRenderer implements WriterInterface
{
    /**
     * Name of renderer include file
     *
     * @var string
     */
    protected $includeFile = 'tcpdf.php';

    /**
     * Save PhpWord to file.
     *
     * @param string $filename Name of the file to save as
     * @return vois
     */
    public function save($filename = null)
    {
        $fileHandle = parent::prepareForSave($filename);

        //  PDF settings
        $paperSize = 'A4';
        $orientation = 'P';

        // Create PDF
        $pdf = new \TCPDF($orientation, 'pt', $paperSize);
        $pdf->setFontSubsetting(false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->addPage();
        $pdf->setFont($this->getFont());
        $pdf->writeHTML($this->getContent());

        // Write document properties
        $phpWord = $this->getPhpWord();
        $docProps = $phpWord->getDocInfo();
        $pdf->setTitle($docProps->getTitle());
        $pdf->setAuthor($docProps->getCreator());
        $pdf->setSubject($docProps->getSubject());
        $pdf->setKeywords($docProps->getKeywords());
        $pdf->setCreator($docProps->getCreator());

        //  Write to file
        fwrite($fileHandle, $pdf->output($filename, 'S'));

        parent::restoreStateAfterSave($fileHandle);
    }
}
