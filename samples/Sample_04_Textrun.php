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
$textrun->addImage('old/_earth.jpg', array('width'=>18, 'height'=>18));
$textrun->addText(' Here is some more text. ');

// Save File
echo date('H:i:s') , ' Write to Word2007 format' , EOL;
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$objWriter->save(str_replace('.php', '.docx', __FILE__));

echo date('H:i:s') , ' Write to OpenDocumentText format' , EOL;
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'ODText');
$objWriter->save(str_replace('.php', '.odt', __FILE__));

echo date('H:i:s') , ' Write to RTF format' , EOL;
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'RTF');
$objWriter->save(str_replace('.php', '.rtf', __FILE__));

// Echo memory peak usage
echo date('H:i:s') , ' Peak memory usage: ' , (memory_get_peak_usage(true) / 1024 / 1024) , ' MB' , EOL;

// Echo done
echo date('H:i:s') , ' Done writing file' , EOL;