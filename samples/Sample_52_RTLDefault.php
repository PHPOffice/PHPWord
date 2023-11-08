<?php

include_once 'Sample_Header.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Shared\Html as SharedHtml;

// Suggested by issue 2427.
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new PhpWord();
Settings::setDefaultRtl(true);
$phpWord->setDefaultFontName('DejaVu Sans'); // for good rendition of PDF
$rendererName = Settings::PDF_RENDERER_MPDF;
$rendererLibraryPath = $vendorDirPath . '/mpdf/mpdf';
Settings::setPdfRenderer($rendererName, $rendererLibraryPath);

// New section
$section = $phpWord->addSection();
$arabic = '<p>  الألم الذي ربما تنجم عنه بعض ا.</p>';
$english = '<p style="text-align: left; direction: ltr; font-family: DejaVu Sans, sans-serif;">LTR in RTL document.</p>';
SharedHtml::addHtml($section, $arabic, false, false);
SharedHtml::addHtml($section, $english, false, false);
SharedHtml::addHtml($section, $english, false, false);
SharedHtml::addHtml($section, $arabic, false, false);
SharedHtml::addHtml($section, $arabic, false, false);

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
Settings::setDefaultRtl(false);
