<?php
/**
 * Image creation
 */

// Init
error_reporting(E_ALL);
define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
require_once '../Classes/PHPWord.php';

// New Word document
echo date('H:i:s'), " Create new PHPWord object", EOL;
$PHPWord = new PHPWord();

// Begin code
$section = $PHPWord->createSection();
$section->addText('Local image without any styles:');
$section->addImage('resources/_mars.jpg');
$section->addTextBreak(2);
//
$section->addText('Local image with styles:');
$section->addImage('resources/_earth.jpg', array('width' => 210, 'height' => 210, 'align' => 'center'));
$section->addTextBreak(2);

$source = 'http://php.net/images/logos/php-med-trans-light.gif';
$section->addText("Remote image from: {$source}");
$section->addMemoryImage($source);
// End code

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
