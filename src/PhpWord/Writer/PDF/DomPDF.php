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

use Dompdf\Dompdf as DompdfLib;
use Dompdf\Options;
use PhpOffice\PhpWord\Writer\WriterInterface;

/**
 * DomPDF writer.
 *
 * @see  https://github.com/dompdf/dompdf
 * @since 0.10.0
 */
class DomPDF extends AbstractRenderer implements WriterInterface
{
    /**
     * Name of renderer include file.
     *
     * @var string
     */
    protected $includeFile;

    /**
     * Gets the implementation of external PDF library that should be used.
     *
     * @return Dompdf implementation
     */
    protected function createExternalWriterInstance()
    {
        $options = new Options();
        if ($this->getFont()) {
            $options->set('defaultFont', $this->getFont());
        }

        return new DompdfLib($options);
    }

    /**
     * Save PhpWord to file.
     */
    public function save(string $filename): void
    {
        $fileHandle = parent::prepareForSave($filename);

        //  PDF settings
        $paperSize = 'A4';
        $orientation = 'portrait';

        //  Create PDF
        $restoreHandler = false;
        if (PHP_VERSION_ID >= self::$temporaryVersionCheck) {
            // @codeCoverageIgnoreStart
            $errhandler = [$this, 'specialErrorHandler'];
            set_error_handler($errhandler); // @phpstan-ignore-line
            $restoreHandler = true;
            // @codeCoverageIgnoreEnd
        }
        $pdf = $this->createExternalWriterInstance();
        $pdf->setPaper(strtolower($paperSize), $orientation);
        $pdf->loadHtml(str_replace(PHP_EOL, '', $this->getContent()));
        $pdf->render();

        //  Write to file
        fwrite($fileHandle, $pdf->output());

        if ($restoreHandler) {
            restore_error_handler(); // @codeCoverageIgnore
        }
        parent::restoreStateAfterSave($fileHandle);
    }

    /** @var int */
    protected static $temporaryVersionCheck = 80500;

    /**
     * Temporary handler for Php8.5 waiting for Dompdf release.
     *
     * @codeCoverageIgnore
     */
    public function specialErrorHandler(int $errno, string $errstr, string $filename, int $lineno): bool
    {
        if ($errno === E_DEPRECATED) {
            if (preg_match('/canonical|imagedestroy/', $errstr) === 1) {
                return true;
            }
        }

        return false; // continue error handling
    }
}
