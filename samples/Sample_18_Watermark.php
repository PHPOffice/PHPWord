<?php
/**
 * Watermark
 */
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), " Create new PHPWord object", EOL;
$PHPWord = new PHPWord();

// Begin code

$section = $PHPWord->createSection();
$header = $section->createHeader();
$header->addWatermark('resources/_earth.jpg', array('marginTop' => 200, 'marginLeft' => 55));
$section->addText('The header reference to the current section includes a watermark image.');

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

include_once 'Sample_Footer.php';
