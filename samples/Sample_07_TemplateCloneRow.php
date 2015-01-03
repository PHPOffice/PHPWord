<?php
include_once 'Sample_Header.php';

// Template processor instance creation
echo date('H:i:s'), ' Creating new TemplateProcessor instance...', EOL;
$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('resources/Sample_07_TemplateCloneRow.docx');

// Variables on different parts of document
$templateProcessor->setValue('weekday', htmlspecialchars(date('l'))); // On section/content
$templateProcessor->setValue('time', htmlspecialchars(date('H:i'))); // On footer
$templateProcessor->setValue('serverName', htmlspecialchars(realpath(__DIR__))); // On header

// Simple table
$templateProcessor->cloneRow('rowValue', 10);

$templateProcessor->setValue('rowValue#1', htmlspecialchars('Sun'));
$templateProcessor->setValue('rowValue#2', htmlspecialchars('Mercury'));
$templateProcessor->setValue('rowValue#3', htmlspecialchars('Venus'));
$templateProcessor->setValue('rowValue#4', htmlspecialchars('Earth'));
$templateProcessor->setValue('rowValue#5', htmlspecialchars('Mars'));
$templateProcessor->setValue('rowValue#6', htmlspecialchars('Jupiter'));
$templateProcessor->setValue('rowValue#7', htmlspecialchars('Saturn'));
$templateProcessor->setValue('rowValue#8', htmlspecialchars('Uranus'));
$templateProcessor->setValue('rowValue#9', htmlspecialchars('Neptun'));
$templateProcessor->setValue('rowValue#10', htmlspecialchars('Pluto'));

$templateProcessor->setValue('rowNumber#1', htmlspecialchars('1'));
$templateProcessor->setValue('rowNumber#2', htmlspecialchars('2'));
$templateProcessor->setValue('rowNumber#3', htmlspecialchars('3'));
$templateProcessor->setValue('rowNumber#4', htmlspecialchars('4'));
$templateProcessor->setValue('rowNumber#5', htmlspecialchars('5'));
$templateProcessor->setValue('rowNumber#6', htmlspecialchars('6'));
$templateProcessor->setValue('rowNumber#7', htmlspecialchars('7'));
$templateProcessor->setValue('rowNumber#8', htmlspecialchars('8'));
$templateProcessor->setValue('rowNumber#9', htmlspecialchars('9'));
$templateProcessor->setValue('rowNumber#10', htmlspecialchars('10'));

// Table with a spanned cell
$templateProcessor->cloneRow('userId', 3);

$templateProcessor->setValue('userId#1', htmlspecialchars('1'));
$templateProcessor->setValue('userFirstName#1', htmlspecialchars('James'));
$templateProcessor->setValue('userName#1', htmlspecialchars('Taylor'));
$templateProcessor->setValue('userPhone#1', htmlspecialchars('+1 428 889 773'));

$templateProcessor->setValue('userId#2', htmlspecialchars('2'));
$templateProcessor->setValue('userFirstName#2', htmlspecialchars('Robert'));
$templateProcessor->setValue('userName#2', htmlspecialchars('Bell'));
$templateProcessor->setValue('userPhone#2', htmlspecialchars('+1 428 889 774'));

$templateProcessor->setValue('userId#3', htmlspecialchars('3'));
$templateProcessor->setValue('userFirstName#3', htmlspecialchars('Michael'));
$templateProcessor->setValue('userName#3', htmlspecialchars('Ray'));
$templateProcessor->setValue('userPhone#3', htmlspecialchars('+1 428 889 775'));

echo date('H:i:s'), ' Saving the result document...', EOL;
$templateProcessor->saveAs('results/Sample_07_TemplateCloneRow.docx');

echo getEndingNotes(array('Word2007' => 'docx'));
if (!CLI) {
    include_once 'Sample_Footer.php';
}
