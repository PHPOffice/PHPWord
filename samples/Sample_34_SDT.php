<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), " Create new PhpWord object", EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$phpWord->getProtection()->setEditing('forms');

$section = $phpWord->addSection();

$section->addSDT('comboBox')->setListItems(array('1' => 'Choice 1', '2' => 'Choice 2'));

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
