<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s') , " Create new PhpWord object" , \EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

$document = $phpWord->loadTemplate('resources/Sample_21_TemplateBlockDelete.docx');

// Everything between ${tag} and ${/tag}, will be deleted/erased.
$document->deleteTemplateBlock('DELETEME');

$name = 'Sample_21_TemplateBlockDelete.docx';
echo date('H:i:s'), " Write to Word2007 format", EOL;
$document->saveAs($name);
rename($name, "results/{$name}");

include_once 'Sample_Footer.php';
