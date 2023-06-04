<?php

include_once 'Sample_Header.php';

use PhpOffice\PhpWord\TemplateProcessor;

// Template processor instance creation
echo date('H:i:s'), ' Creating new TemplateProcessor instance...', EOL;
$filename = 'Sample_42_TemplateSetCheckbox.docx';
$templateProcessor = new TemplateProcessor(__DIR__ . "/resources/{$filename}");

$templateProcessor->setCheckbox('checkbox', true);
$templateProcessor->setCheckbox('checkbox2', false);

echo date('H:i:s'), ' Saving the result document...', EOL;
$templateProcessor->saveAs(__DIR__ . "/results/{$filename}");

echo getEndingNotes(['Word2007' => 'docx'], "results/{$filename}");
if (!CLI) {
    include_once 'Sample_Footer.php';
}
