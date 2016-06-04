<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// New section
$section = $phpWord->addSection();

// Add Field elements
// See Element/Field.php for all options
$section->addText('Date field:');
$section->addField('DATE', array('dateformat' => 'dddd d MMMM yyyy H:mm:ss'), array('PreserveFormat'));

$section->addText('Page field:');
$section->addField('PAGE', array('format' => 'ArabicDash'));

$section->addText('Number of pages field:');
$section->addField('NUMPAGES', array('format' => 'Arabic', 'numformat' => '0,00'), array('PreserveFormat'));

$textrun = $section->addTextRun(array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
$textrun->addText('This is the date of lunar calendar ');
$textrun->addField('DATE', array('dateformat' => 'd-M-yyyy H:mm:ss'), array('PreserveFormat', 'LunarCalendar'));
$textrun->addText(' written in a textrun.');

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
