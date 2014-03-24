<?php
// Init
error_reporting(E_ALL);
define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
require_once '../Classes/PHPWord.php';

// New Word document
echo date('H:i:s') , " Create new PHPWord object" , EOL;
$PHPWord = new PHPWord();

$document = $PHPWord->loadTemplate('resources/Sample_21_TemplateBlockDelete.docx');

// Everything between ${tag} and ${/tag}, will be deleted/erased.
$document->deleteTemplateBlock('DELETEME');

$name = 'Sample_21_TemplateBlockDelete.docx';
echo date('H:i:s'), " Write to Word2007 format", EOL;
$document->saveAs($name);
rename($name, "results/{$name}");

// Done
echo date('H:i:s'), " Done writing file(s)", EOL;
echo date('H:i:s'), " Peak memory usage: ", (memory_get_peak_usage(true) / 1024 / 1024), " MB", EOL;
