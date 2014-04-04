<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s') , " Create new PhpWord object" , \EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

$document = $phpWord->loadTemplate('resources/Sample_20_TemplateBlockClone.docx');

// Will clone everything between ${tag} and ${/tag}, the number of times. By default, 1.
$document->cloneBlock('CLONEME', 3);

$name = 'Sample_20_TemplateBlockClone.docx';
echo date('H:i:s'), " Write to Word2007 format", EOL;
$document->saveAs($name);
rename($name, "results/{$name}");

include_once 'Sample_Footer.php';
