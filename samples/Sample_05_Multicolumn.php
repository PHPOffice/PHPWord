<?php

error_reporting(E_ALL);

if(php_sapi_name() == 'cli' && empty($_SERVER['REMOTE_ADDR'])) {
	define('EOL', PHP_EOL);
}
else {
	define('EOL', '<br />');
}

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

// Save File
echo date('H:i:s') , " Write to Word2007 format" , EOL;
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$objWriter->save(str_replace('.php', '.docx', __FILE__));

// echo date('H:i:s') , " Write to OpenDocumentText format" , EOL;
// $objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'ODText');
// $objWriter->save(str_replace('.php', '.odt', __FILE__));

// echo date('H:i:s') , " Write to RTF format" , EOL;
// $objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'RTF');
// $objWriter->save(str_replace('.php', '.rtf', __FILE__));


// Echo memory peak usage
echo date('H:i:s') , " Peak memory usage: " , (memory_get_peak_usage(true) / 1024 / 1024) , " MB" , EOL;

// Echo done
echo date('H:i:s') , " Done writing file" , EOL;
