<?php
include_once 'Sample_Header.php';

// Template processor instance creation
echo date('H:i:s'), ' Creating new TemplateProcessor instance...', EOL;
$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('resources/Sample_07_TemplateCloneRow.docx');

// Variables on different parts of document
$templateProcessor->setValue('weekday', date('l'));            // On section/content
$templateProcessor->setValue('time', date('H:i'));             // On footer
$templateProcessor->setValue('serverName', realpath(__DIR__)); // On header

// Simple table
$templateProcessor->cloneRow('rowValue', 10);

$templateProcessor->setValue('rowValue#1', 'Sun');
$templateProcessor->setValue('rowValue#2', 'Mercury');
$templateProcessor->setValue('rowValue#3', 'Venus');
$templateProcessor->setValue('rowValue#4', 'Earth');
$templateProcessor->setValue('rowValue#5', 'Mars');
$templateProcessor->setValue('rowValue#6', 'Jupiter');
$templateProcessor->setValue('rowValue#7', 'Saturn');
$templateProcessor->setValue('rowValue#8', 'Uranus');
$templateProcessor->setValue('rowValue#9', 'Neptun');
$templateProcessor->setValue('rowValue#10', 'Pluto');

$templateProcessor->setValue('rowNumber#1', '1');
$templateProcessor->setValue('rowNumber#2', '2');
$templateProcessor->setValue('rowNumber#3', '3');
$templateProcessor->setValue('rowNumber#4', '4');
$templateProcessor->setValue('rowNumber#5', '5');
$templateProcessor->setValue('rowNumber#6', '6');
$templateProcessor->setValue('rowNumber#7', '7');
$templateProcessor->setValue('rowNumber#8', '8');
$templateProcessor->setValue('rowNumber#9', '9');
$templateProcessor->setValue('rowNumber#10', '10');

// Table with a spanned cell
$templateProcessor->cloneRow('userId', 3);

$templateProcessor->setValue('userId#1', '1');
$templateProcessor->setValue('userFirstName#1', 'James');
$templateProcessor->setValue('userName#1', 'Taylor');
$templateProcessor->setValue('userPhone#1', '+1 428 889 773');

$templateProcessor->setValue('userId#2', '2');
$templateProcessor->setValue('userFirstName#2', 'Robert');
$templateProcessor->setValue('userName#2', 'Bell');
$templateProcessor->setValue('userPhone#2', '+1 428 889 774');

$templateProcessor->setValue('userId#3', '3');
$templateProcessor->setValue('userFirstName#3', 'Michael');
$templateProcessor->setValue('userName#3', 'Ray');
$templateProcessor->setValue('userPhone#3', '+1 428 889 775');

echo date('H:i:s'), ' Saving the result document...', EOL;
$templateProcessor->saveAs('results/Sample_07_TemplateCloneRow.docx');

echo getEndingNotes(array('Word2007' => 'docx'));
if (!CLI) {
    include_once 'Sample_Footer.php';
}
