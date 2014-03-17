<?php
include_once 'Sample_Header.php';

// Read contents
$name = basename(__FILE__, '.php');
$source = "resources/{$name}.docx";
echo date('H:i:s'), " Reading contents from `{$source}`", EOL;
$PHPWord = PHPWord_IOFactory::load($source);

// (Re)write contents
$writers = array('Word2007' => 'docx', 'ODText' => 'odt', 'RTF' => 'rtf');
foreach ($writers as $writer => $extension) {
    echo date('H:i:s'), " Write to {$writer} format", EOL;
    $objWriter = PHPWord_IOFactory::createWriter($PHPWord, $writer);
    $objWriter->save("{$name}.{$extension}");
    rename("{$name}.{$extension}", "results/{$name}.{$extension}");
}

include_once 'Sample_Footer.php';
