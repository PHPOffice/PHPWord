<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$phpWord->getProtection()->setEditing('forms');

$section = $phpWord->addSection();

$textrun = $section->addTextRun();
$textrun->addText(htmlspecialchars('Form fields can be added in a text run and can be in form of textinput '));
$textrun->addFormField('textinput')->setName('MyTextBox');
$textrun->addText(htmlspecialchars(', checkbox '));
$textrun->addFormField('checkbox')->setDefault(true);
$textrun->addText(htmlspecialchars(', or dropdown '));
$textrun->addFormField('dropdown')->setEntries(array('Choice 1', 'Choice 2', 'Choice 3'));
$textrun->addText(htmlspecialchars('. You have to set document protection to "forms" to enable dropdown.'));

$section->addText(htmlspecialchars('They can also be added as a stand alone paragraph.'));
$section->addFormField('textinput')->setValue('Your name');

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
