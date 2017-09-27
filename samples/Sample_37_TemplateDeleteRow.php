<?php
include_once 'Sample_Header.php';

// Template processor instance creation
echo date('H:i:s'), ' Creating new TemplateProcessor instance...', EOL;
$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('resources/Sample_37_TemplateDeleteRow.docx');

// Variables on different parts of document
$templateProcessor->setValue('weekday', date('l'));            // On section/content
$templateProcessor->setValue('time', date('H:i'));             // On footer
$templateProcessor->setValue('serverName', realpath(__DIR__)); // On header

// Simple table
$templateProcessor->deleteRow('deleteThisRow');

echo date('H:i:s'), ' Saving the result document...', EOL;
$templateProcessor->saveAs('results/Sample_37_TemplateDeleteRow.docx');

echo getEndingNotes(array('Word2007' => 'docx'));
if (!CLI) {
    include_once 'Sample_Footer.php';
}
