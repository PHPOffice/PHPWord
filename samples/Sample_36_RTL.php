<?php
declare(strict_types=1);
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Cell;
use PhpOffice\PhpWord\Style\Colors\Hex;
use PhpOffice\PhpWord\Style\Lengths\Absolute;

include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new PhpWord();

// New section
$section = $phpWord->addSection();

$textrun = $section->addTextRun();
$textrun->addText('This is a Left to Right paragraph.');

$textrun = $section->addTextRun(array('alignment' => Jc::END));
$textrun->addText('سلام این یک پاراگراف راست به چپ است', array('rtl' => true));

$section->addText('Table visually presented as RTL');
$style = array('rtl' => true, 'size' => Absolute::from('pt', 12));
$tableStyle = array('borderSize' => Absolute::from('twip', 6), 'borderColor' => new Hex('000000'), 'width' => Absolute::from('twip', 5000), 'bidiVisual' => true);

$table = $section->addTable($tableStyle);
$cellHCentered = array('alignment' => Jc::CENTER);
$cellHEnd = array('alignment' => Jc::END);
$cellVCentered = array('valign' => Cell::VALIGN_CENTER);

//Vidually bidirectinal table
$table->addRow();
$cell = $table->addCell(Absolute::from('twip', 500), $cellVCentered);
$textrun = $cell->addTextRun($cellHCentered);
$textrun->addText('ردیف', $style);

$cell = $table->addCell(Absolute::from('twip', 11000));
$textrun = $cell->addTextRun($cellHEnd);
$textrun->addText('سوالات', $style);

$cell = $table->addCell(Absolute::from('twip', 500), $cellVCentered);
$textrun = $cell->addTextRun($cellHCentered);
$textrun->addText('بارم', $style);

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
