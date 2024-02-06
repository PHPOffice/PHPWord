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

// Define styles for headers
$phpWord->addTitleStyle(1, ['bold' => true, 'name' => 'Arial', 'size' => 16], []);
//var_dump($x);
$phpWord->addTitleStyle(2, ['bold' => true, 'name' => 'Arial', 'size' => 14], []);
$phpWord->addTitleStyle(3, ['bold' => true, 'name' => 'Arial', 'size' => 12], []);
$phpWord->addTitleStyle(4, ['bold' => true, 'name' => 'Arial', 'size' => 10], []);

// New section
$section = $phpWord->addSection();
$htmlContent = '<h1>مرحبا 1</h1><h2>تجربة 2</h2><h3>تجربة تجربة</h3><h4 dir="rtl">هناك hello هنا 4</h4><p>مرحبا here كلمة انجليزي.</p>';
SharedHtml::addHtml($section, $htmlContent, false, false);

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
Settings::setDefaultRtl(false);
