<?php
// Init
error_reporting(E_ALL);
define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
require_once '../Classes/PHPWord.php';

// New Word Document
echo date('H:i:s') , " Create new PHPWord object" , EOL;
$PHPWord = new PHPWord();
$filler = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. ' .
    'Nulla fermentum, tortor id adipiscing adipiscing, tortor turpis commodo. ' .
    'Donec vulputate iaculis metus, vel luctus dolor hendrerit ac. ' .
    'Suspendisse congue congue leo sed pellentesque.';

// Normal
$section = $PHPWord->createSection();
$section->addText('Normal paragraph. ' . $filler);

// Two columns
$section = $PHPWord->createSection(array(
    'colsNum' => 2,
    'colsSpace' => 1440,
    'breakType' => 'continuous'));
$section->addText('Three columns, one inch (1440 twips) spacing. ' . $filler);

// Normal
$section = $PHPWord->createSection(array('breakType' => 'continuous'));
$section->addText('Normal paragraph again. ' . $filler);

// Three columns
$section = $PHPWord->createSection(array(
    'colsNum' => 3,
    'colsSpace' => 720,
    'breakType' => 'continuous'));
$section->addText('Three columns, half inch (720 twips) spacing. ' . $filler);

// Normal
$section = $PHPWord->createSection(array('breakType' => 'continuous'));
$section->addText('Normal paragraph again.');

// Save file
$name = basename(__FILE__, '.php');
$writers = array('Word2007' => 'docx', 'ODText' => 'odt', 'RTF' => 'rtf');
foreach ($writers as $writer => $extension) {
    echo date('H:i:s'), " Write to {$writer} format", EOL;
    $objWriter = PHPWord_IOFactory::createWriter($PHPWord, $writer);
    $objWriter->save("{$name}.{$extension}");
    rename("{$name}.{$extension}", "results/{$name}.{$extension}");
}

// Done
echo date('H:i:s'), " Done writing file(s)", EOL;
echo date('H:i:s'), " Peak memory usage: ", (memory_get_peak_usage(true) / 1024 / 1024), " MB", EOL;
