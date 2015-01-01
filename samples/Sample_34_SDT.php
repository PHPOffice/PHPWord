<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

$section = $phpWord->addSection();

$textrun = $section->addTextRun();
$textrun->addText(htmlspecialchars('Combobox: '));
$textrun->addSDT('comboBox')->setListItems(array('1' => 'Choice 1', '2' => 'Choice 2'));

$textrun = $section->addTextRun();
$textrun->addText(htmlspecialchars('Date: '));
$textrun->addSDT('date');

$textrun = $section->addTextRun();
$textrun->addText(htmlspecialchars('Drop down list: '));
$textrun->addSDT('dropDownList')->setListItems(array('1' => 'Choice 1', '2' => 'Choice 2'));

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
