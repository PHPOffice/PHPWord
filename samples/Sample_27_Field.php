<?php

use PhpOffice\PhpWord\Element\TextRun;

include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();
PhpOffice\PhpWord\Style::addTitleStyle(1, ['size' => 14]);

// New section
$section = $phpWord->addSection();
$section->addTitle('This page demos fields');

// Add Field elements
// See Element/Field.php for all options
$section->addText('Date field:');
$section->addField('DATE', ['dateformat' => 'dddd d MMMM yyyy H:mm:ss'], ['PreserveFormat']);

$section->addText('Style Ref field:');
$section->addField('STYLEREF', ['StyleIdentifier' => 'Heading 1']);

$section->addText('Page field:');
$section->addField('PAGE', ['format' => 'Arabic']);

$section->addText('Number of pages field:');
$section->addField('NUMPAGES', ['numformat' => '0,00', 'format' => 'Arabic'], ['PreserveFormat']);

$section->addText('Filename field:');
$section->addField('FILENAME', ['format' => 'Upper'], ['Path', 'PreserveFormat']);
$section->addTextBreak();

$textrun = $section->addTextRun();
$textrun->addText('An index field is ');
$textrun->addField('XE', [], ['Italic'], 'My first index');
$textrun->addText('here:');

$indexEntryText = new TextRun();
$indexEntryText->addText('My ');
$indexEntryText->addText('bold index', ['bold' => true]);
$indexEntryText->addText(' entry');

$textrun = $section->addTextRun();
$textrun->addText('A complex index field is ');
$textrun->addField('XE', [], ['Bold'], $indexEntryText);
$textrun->addText('here:');

$section->addText('The actual index:');
$section->addField('INDEX', [], ['\\e "	"'], 'right click to update the index');

$textrun = $section->addTextRun(['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
$textrun->addText('This is the date of lunar calendar ');
$textrun->addField('DATE', ['dateformat' => 'd-M-yyyy H:mm:ss'], ['PreserveFormat', 'LunarCalendar']);
$textrun->addText(' written in a textrun.');
$section->addTextBreak();

$macroText = new TextRun();
$macroText->addText('Double click', ['bold' => true]);
$macroText->addText(' to ');
$macroText->addText('zoom to 100%', ['italic' => true]);

$section->addText('A macro button with styled text:');
$section->addField('MACROBUTTON', ['macroname' => 'Zoom100'], [], $macroText);
$section->addTextBreak();

$section->addText('A macro button with simple text:');
$section->addField('MACROBUTTON', ['macroname' => 'Zoom100'], [], 'double click to zoom');

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
