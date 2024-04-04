<?php

include_once 'Sample_Header.php';

use PhpOffice\PhpWord\Settings;

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$phpWord->setDefaultFontName('DejaVu Sans'); // for good rendition of PDF
$rendererName = Settings::PDF_RENDERER_MPDF;
$rendererLibraryPath = $vendorDirPath . '/mpdf/mpdf';
Settings::setPdfRenderer($rendererName, $rendererLibraryPath);

// New section
$section = $phpWord->addSection();

$textrun = $section->addTextRun();
$textrun->addText('This is a Left to Right paragraph.');

$textrun = $section->addTextRun(['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END]);
$textrun->addText('سلام این یک پاراگراف راست به چپ است', ['rtl' => true]);

$section->addText('Table visually presented as RTL');
$style = ['rtl' => true, 'size' => 12];
$tableStyle = ['borderSize' => 6, 'borderColor' => '000000', 'width' => 5000, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT, 'bidiVisual' => true];

$table = $section->addTable($tableStyle);
$cellHCentered = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER];
$cellHEnd = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END];
$cellVCentered = ['valign' => \PhpOffice\PhpWord\SimpleType\VerticalJc::CENTER];

//Vidually bidirectinal table
$table->addRow();
$cell = $table->addCell(1500, $cellVCentered);
$textrun = $cell->addTextRun($cellHCentered);
$textrun->addText('ردیف', $style);

$cell = $table->addCell(2000);
$textrun = $cell->addTextRun($cellHEnd);
$textrun->addText('سوالات', $style);

$cell = $table->addCell(1000, $cellVCentered);
$textrun = $cell->addTextRun($cellHCentered);
$textrun->addText('بارم', $style);

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
