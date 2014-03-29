<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), " Create new PhpWord object", \EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$section = $phpWord->createSection();

$section->addText("This is some text highlighted using fgColor (limited to 15 colors)     ", array("fgColor" => \PhpOffice\PhpWord\Style\Font::FGCOLOR_YELLOW));
$section->addText("This one uses bgColor and is using hex value (0xfbbb10)", array("bgColor" => "fbbb10"));
$section->addText("Compatible with font colors", array("color"=>"0000ff", "bgColor" => "fbbb10"));

// Save file
$name = basename(__FILE__, '.php');
$writers = array('Word2007' => 'docx', 'ODText' => 'odt', 'RTF' => 'rtf');
foreach ($writers as $writer => $extension) {
    echo date('H:i:s'), " Write to {$writer} format", \EOL;
    $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, $writer);
    $xmlWriter->save("{$name}.{$extension}");
    rename("{$name}.{$extension}", "results/{$name}.{$extension}");
}

include_once 'Sample_Footer.php';
