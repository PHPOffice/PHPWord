<?php
use PhpOffice\PhpWord\Element\TextRun;

include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();
PhpOffice\PhpWord\Style::addTitleStyle(1, array('size' => 14));

// New section
$section = $phpWord->addSection();
$section->addTitle('This page demos fields');

// Add Field elements
// See Element/Field.php for all options
$section->addText('Date field:');
$section->addField('DATE', array('dateformat' => 'dddd d MMMM yyyy H:mm:ss'), array('PreserveFormat'));

$section->addText('Style Ref field:');
$section->addField('STYLEREF', array('StyleIdentifier' => 'Heading 1'));

$section->addText('Page field:');
$section->addField('PAGE', array('format' => 'Arabic'));

$section->addText('Number of pages field:');
$section->addField('NUMPAGES', array('numformat' => '0,00', 'format' => 'Arabic'), array('PreserveFormat'));
$section->addTextBreak();

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
$section->addTextBreak();

$macroText = new TextRun();
$macroText->addText('Double click', array('bold' => true));
$macroText->addText(' to ');
$macroText->addText('zoom to 100%', array('italic' => true));

$section->addText('A macro button with styled text:');
$section->addField('MACROBUTTON', array('macroname' => 'Zoom100'), array(), $macroText);
$section->addTextBreak();

$section->addText('A macro button with simple text:');
$section->addField('MACROBUTTON', array('macroname' => 'Zoom100'), array(), 'double click to zoom');

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
