<?php
include_once 'Sample_Header.php';

// Template processor instance creation
echo date('H:i:s') , ' Creating new TemplateProcessor instance from file contents...' , EOL;

// This file could be retrieved from HTTP, FTP, AWS S3 Storage, or other...
$fileContents = file_get_contents('resources/Sample_23_TemplateBlock.docx');
$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($fileContents);

// Will clone everything between ${tag} and ${/tag}, the number of times. By default, 1.
$templateProcessor->cloneBlock('CLONEME', 3);

// Everything between ${tag} and ${/tag}, will be deleted/erased.
$templateProcessor->deleteBlock('DELETEME');

echo date('H:i:s'), ' Saving the result document...', EOL;
$templateProcessor->saveAs('results/Sample_23_TemplateBlock.docx');

echo getEndingNotes(array('Word2007' => 'docx'), 'Sample_23_TemplateBlock');
if (!CLI) {
    include_once 'Sample_Footer.php';
}
