<?php
/**
 * Link sample
 */
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), " Create new PHPWord object", EOL;
$PHPWord = new PHPWord();

// Begin code
$section = $PHPWord->createSection();

// Add hyperlink elements
$section->addLink('http://www.google.com', 'Best search engine', array('color'=>'0000FF', 'underline'=>PHPWord_Style_Font::UNDERLINE_SINGLE));
$section->addTextBreak(2);

$PHPWord->addLinkStyle('myOwnLinkStyle', array('bold'=>true, 'color'=>'808000'));
$section->addLink('http://www.bing.com', null, 'myOwnLinkStyle');
$section->addLink('http://www.yahoo.com', null, 'myOwnLinkStyle');

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
