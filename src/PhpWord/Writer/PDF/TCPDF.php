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
 *
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Writer\PDF;

use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Style;
use PhpOffice\PhpWord\Writer\WriterInterface;

/**
 * TCPDF writer.
 *
 * @deprecated 0.13.0 Use `DomPDF` or `MPDF` instead.
 * @see  http://www.tcpdf.org/
 * @since 0.11.0
 */
class TCPDF extends AbstractRenderer implements WriterInterface
{
    /**
     * Name of renderer include file.
     *
     * @var string
     */
    protected $includeFile = 'tcpdf.php';

    /**
     * Gets the implementation of external PDF library that should be used.
     *
     * @param string $orientation Page orientation
     * @param string $unit Unit measure
     * @param string $paperSize Paper size
     *
     * @return \TCPDF implementation
     */
    protected function createExternalWriterInstance($orientation, $unit, $paperSize)
    {
        return new \TCPDF($orientation, $unit, $paperSize);
    }

    /**
     * Overwriteable function to allow user to extend TCPDF.
     * There should always be an AddPage call, preceded or followed
     *   by code to customize TCPDF configuration.
     * The customization below sets vertical spacing
     *   between paragaraphs when the user has
     *   explicitly set those values to numeric in default style.
     */
    protected function prepareToWrite(\TCPDF $pdf): void
    {
        $pdf->AddPage();
        $customStyles = Style::getStyles();
        $normal = $customStyles['Normal'] ?? null;
        if ($normal instanceof Style\Paragraph) {
            $before = $normal->getSpaceBefore();
            $after = $normal->getSpaceAfter();
            $height = $normal->getLineHeight() ?? '';
            if (is_numeric($before) && is_numeric($after)) {
                $tagvs = [
                    'p' => [['n' => $before, 'h' => $height], ['n' => $after, 'h' => $height]],
                ];
                $pdf->setHtmlVSpace($tagvs);
            }
        }
    }

    /**
     * Save PhpWord to file.
     *
     * @param string $filename Name of the file to save as
     */
    public function save($filename = null): void
    {
        $fileHandle = parent::prepareForSave($filename);

        //  PDF settings
        $paperSize = strtoupper(Settings::getDefaultPaper());
        $orientation = 'P';

        // Create PDF
        $this->isTcpdf = true;
        $pdf = $this->createExternalWriterInstance($orientation, 'pt', $paperSize);
        $pdf->setFontSubsetting(false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetFont($this->getFont());
        $this->prepareToWrite($pdf);
        $pdf->writeHTML($this->getContent());

        // Write document properties
        $phpWord = $this->getPhpWord();
        $docProps = $phpWord->getDocInfo();
        $pdf->SetTitle($docProps->getTitle());
        $pdf->SetAuthor($docProps->getCreator());
        $pdf->SetSubject($docProps->getSubject());
        $pdf->SetKeywords($docProps->getKeywords());
        $pdf->SetCreator($docProps->getCreator());

        //  Write to file
        fwrite($fileHandle, $pdf->Output($filename, 'S'));

        parent::restoreStateAfterSave($fileHandle);
    }
}
