<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// New section
$section = $phpWord->addSection();

$section->addText('Check box in section');
$section->addCheckBox('chkBox1', 'Checkbox 1');
$section->addText('Check box in table cell');
$table = $section->addTable();
$table->addRow();
$cell = $table->addCell();
$cell->addCheckBox('chkBox2', 'Checkbox 2');

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
