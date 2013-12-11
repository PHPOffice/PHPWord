<?php
require_once '../PHPWord.php';

// New Word Document
$PHPWord = new PHPWord();

// New portrait section
$section = $PHPWord->createSection();

// Add style definitions
$PHPWord->addParagraphStyle('pStyle', array('spacing'=>100));
$PHPWord->addFontStyle('BoldText', array('bold'=>true));
$PHPWord->addFontStyle('ColoredText', array('color'=>'FF8080'));
$PHPWord->addLinkStyle('NLink', array('color'=>'0000FF', 'underline'=>PHPWord_Style_Font::UNDERLINE_SINGLE));

// Add text elements
$textrun = $section->createTextRun('pStyle');

$textrun->addText('Each textrun can contain native text or link elements.');
$textrun->addText(' No break is placed after adding an element.', 'BoldText');
$textrun->addText(' All elements are placed inside a paragraph with the optionally given p-Style.', 'ColoredText');
$textrun->addText(' The best search engine: ');
$textrun->addLink('http://www.google.com', null, 'NLink');
$textrun->addText('. Also not bad: ');
$textrun->addLink('http://www.bing.com', null, 'NLink');



// Save File
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$objWriter->save('Textrun.docx');
?>