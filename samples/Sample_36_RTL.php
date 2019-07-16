<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// New section
$section = $phpWord->addSection();

$textrun = $section->addTextRun();
$textrun->addText('This is a Left to Right paragraph.');

$textrun = $section->addTextRun(array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END));
$textrun->addText('سلام این یک پاراگراف راست به چپ است', array('rtl' => true));

$section->addText('Table visually presented as RTL');
$style = array('rtl' => true, 'size' => 12);
$tableStyle = array('borderSize' => 6, 'borderColor' => '000000', 'width' => 5000, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT, 'bidiVisual' => true);

$table = $section->addTable($tableStyle);
$cellHCentered = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER);
$cellHEnd = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END);
$cellVCentered = array('valign' => \PhpOffice\PhpWord\Style\Cell::VALIGN_CENTER);

//Vidually bidirectinal table
$table->addRow();
$cell = $table->addCell(500, $cellVCentered);
$textrun = $cell->addTextRun($cellHCentered);
$textrun->addText('ردیف', $style);

$cell = $table->addCell(11000);
$textrun = $cell->addTextRun($cellHEnd);
$textrun->addText('سوالات', $style);

$cell = $table->addCell(500, $cellVCentered);
$textrun = $cell->addTextRun($cellHCentered);
$textrun->addText('بارم', $style);

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
