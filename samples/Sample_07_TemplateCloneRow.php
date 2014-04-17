<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s') , " Create new PhpWord object" , EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

$document = $phpWord->loadTemplate('resources/Sample_07_TemplateCloneRow.docx');

// Variables on different parts of document
$document->setValue('weekday', date('l')); // On section/content
$document->setValue('time', date('H:i')); // On footer
$document->setValue('serverName', realpath(__DIR__)); // On header

// Simple table
$document->cloneRow('rowValue', 10);

$document->setValue('rowValue#1', 'Sun');
$document->setValue('rowValue#2', 'Mercury');
$document->setValue('rowValue#3', 'Venus');
$document->setValue('rowValue#4', 'Earth');
$document->setValue('rowValue#5', 'Mars');
$document->setValue('rowValue#6', 'Jupiter');
$document->setValue('rowValue#7', 'Saturn');
$document->setValue('rowValue#8', 'Uranus');
$document->setValue('rowValue#9', 'Neptun');
$document->setValue('rowValue#10', 'Pluto');

$document->setValue('rowNumber#1', '1');
$document->setValue('rowNumber#2', '2');
$document->setValue('rowNumber#3', '3');
$document->setValue('rowNumber#4', '4');
$document->setValue('rowNumber#5', '5');
$document->setValue('rowNumber#6', '6');
$document->setValue('rowNumber#7', '7');
$document->setValue('rowNumber#8', '8');
$document->setValue('rowNumber#9', '9');
$document->setValue('rowNumber#10', '10');

// Table with a spanned cell
$document->cloneRow('userId', 3);

$document->setValue('userId#1', '1');
$document->setValue('userFirstName#1', 'James');
$document->setValue('userName#1', 'Taylor');
$document->setValue('userPhone#1', '+1 428 889 773');

$document->setValue('userId#2', '2');
$document->setValue('userFirstName#2', 'Robert');
$document->setValue('userName#2', 'Bell');
$document->setValue('userPhone#2', '+1 428 889 774');

$document->setValue('userId#3', '3');
$document->setValue('userFirstName#3', 'Michael');
$document->setValue('userName#3', 'Ray');
$document->setValue('userPhone#3', '+1 428 889 775');

$name = 'Sample_07_TemplateCloneRow.docx';
echo date('H:i:s'), " Write to Word2007 format", EOL;
$document->saveAs($name);
rename($name, "results/{$name}");

echo getEndingNotes(array('Word2007' => 'docx'));
if (!CLI) {
    include_once 'Sample_Footer.php';
}
