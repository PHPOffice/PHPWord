<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

$section = $phpWord->addSection();
$section->addText(htmlspecialchars('Check box in section', ENT_COMPAT, 'UTF-8'));
$section->addCheckBox('chkBox1', htmlspecialchars('Checkbox 1', ENT_COMPAT, 'UTF-8'));
$section->addText(htmlspecialchars('Check box in table cell', ENT_COMPAT, 'UTF-8'));
$table = $section->addTable();
$table->addRow();
$cell = $table->addCell();
$cell->addCheckBox('chkBox2', htmlspecialchars('Checkbox 2', ENT_COMPAT, 'UTF-8'));

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
