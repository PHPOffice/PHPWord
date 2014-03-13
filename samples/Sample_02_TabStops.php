<?php
// Init
error_reporting(E_ALL);
define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
require_once '../Classes/PHPWord.php';

// New Word Document
echo date('H:i:s') , ' Create new PHPWord object' , EOL;
$PHPWord = new PHPWord();

// Ads styles
$PHPWord->addParagraphStyle('multipleTab', array(
  'tabs' => array(
    new PHPWord_Style_Tab('left', 1550),
    new PHPWord_Style_Tab('center', 3200),
    new PHPWord_Style_Tab('right', 5300)
  )
));
$PHPWord->addParagraphStyle('rightTab', array(
  'tabs' => array(
    new PHPWord_Style_Tab('right', 9090)
  )
));
$PHPWord->addParagraphStyle('centerTab', array(
  'tabs' => array(
    new PHPWord_Style_Tab('center', 4680)
  )
));

// New portrait section
$section = $PHPWord->createSection();

// Add listitem elements
$section->addText("Multiple Tabs:\tOne\tTwo\tThree", NULL, 'multipleTab');
$section->addText("Left Aligned\tRight Aligned", NULL, 'rightTab');
$section->addText("\tCenter Aligned",            NULL, 'centerTab');

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
