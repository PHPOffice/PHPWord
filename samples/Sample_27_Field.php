<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// Begin code
$section = $phpWord->addSection();

// Add Field elements
// See Element/Field.php for all options
$section->addText(htmlspecialchars('Date field:', ENT_COMPAT, 'UTF-8'));
$section->addField('DATE', array('dateformat' => 'dddd d MMMM yyyy H:mm:ss'), array('PreserveFormat'));

$section->addText(htmlspecialchars('Page field:', ENT_COMPAT, 'UTF-8'));
$section->addField('PAGE', array('format' => 'ArabicDash'));

$section->addText(htmlspecialchars('Number of pages field:', ENT_COMPAT, 'UTF-8'));
$section->addField('NUMPAGES', array('format' => 'Arabic', 'numformat' => '0,00'), array('PreserveFormat'));

$textrun = $section->addTextRun(array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
$textrun->addText(htmlspecialchars('This is the date of lunar calendar ', ENT_COMPAT, 'UTF-8'));
$textrun->addField('DATE', array('dateformat' => 'd-M-yyyy H:mm:ss'), array('PreserveFormat', 'LunarCalendar'));
$textrun->addText(htmlspecialchars(' written in a textrun.', ENT_COMPAT, 'UTF-8'));

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
