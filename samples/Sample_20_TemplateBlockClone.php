<?php
// Init
error_reporting(E_ALL);
define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
require_once '../Classes/PHPWord.php';

// New Word document
echo date('H:i:s') , " Create new PHPWord object" , EOL;
$PHPWord = new PHPWord();

$document = $PHPWord->loadTemplate('resources/Sample_20_TemplateBlockClone.docx');

// Will clone everything between ${tag} and ${/tag}, the number of times. By default, 1.
$document->cloneBlock('CLONEME', 3);

$name = 'Sample_20_TemplateBlockClone.docx';
echo date('H:i:s'), " Write to Word2007 format", EOL;
$document->saveAs($name);
rename($name, "results/{$name}");

// Done
echo date('H:i:s'), " Done writing file(s)", EOL;
echo date('H:i:s'), " Peak memory usage: ", (memory_get_peak_usage(true) / 1024 / 1024), " MB", EOL;
