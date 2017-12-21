<?php
use PhpOffice\PhpWord\Element\TextRun;

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
$section->addField('PAGE', array('format' => 'Arabic'));

$section->addText('Number of pages field:');
$section->addField('NUMPAGES', array('numformat' => '0,00', 'format' => 'Arabic'), array('PreserveFormat'));

$textrun = $section->addTextRun();
$textrun->addText('An index field is ');
$textrun->addField('XE', array(), array('Italic'), 'My first index');
$textrun->addText('here:');

$indexEntryText = new TextRun();
$indexEntryText->addText('My ');
$indexEntryText->addText('bold index', array('bold' => true));
$indexEntryText->addText(' entry');

$textrun = $section->addTextRun();
$textrun->addText('A complex index field is ');
$textrun->addField('XE', array(), array('Bold'), $indexEntryText);
$textrun->addText('here:');

$section->addText('The actual index:');
$section->addField('INDEX', array(), array('\\e "	"'), 'right click to update the index');

$textrun = $section->addTextRun(array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
$textrun->addText('This is the date of lunar calendar ');
$textrun->addField('DATE', array('dateformat' => 'd-M-yyyy H:mm:ss'), array('PreserveFormat', 'LunarCalendar'));
$textrun->addText(' written in a textrun.');

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
