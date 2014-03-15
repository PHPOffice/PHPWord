<?php
include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s') , ' Create new PHPWord object' , EOL;
$PHPWord = new PHPWord();


// Ads styles
$PHPWord->addParagraphStyle('pStyle', array('spacing'=>100));
$PHPWord->addFontStyle('BoldText', array('bold'=>true));
$PHPWord->addFontStyle('ColoredText', array('color'=>'FF8080'));
$PHPWord->addLinkStyle('NLink', array('color'=>'0000FF', 'underline'=>PHPWord_Style_Font::UNDERLINE_SINGLE));

// New portrait section
$section = $PHPWord->createSection();

// Add text run
$textrun = $section->createTextRun('pStyle');

$textrun->addText('Each textrun can contain native text, link elements or an image.');
$textrun->addText(' No break is placed after adding an element.', 'BoldText');
$textrun->addText(' Both ');
$textrun->addText('superscript', array('superScript' => true));
$textrun->addText(' and ');
$textrun->addText('subscript', array('subScript' => true));
$textrun->addText(' are also available.');
$textrun->addText(' All elements are placed inside a paragraph with the optionally given p-Style.', 'ColoredText');
$textrun->addText(' Sample Link: ');
$textrun->addLink('http://www.google.com', null, 'NLink');
$textrun->addText(' Sample Image: ');
$textrun->addImage('resources/_earth.jpg', array('width'=>18, 'height'=>18));
$textrun->addText(' Here is some more text. ');

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
