<?php
require_once '../PHPWord.php';

// New Word Document
$PHPWord = new PHPWord();

// New portrait section
$section = $PHPWord->createSection();

// Add hyperlink elements
$section->addLink('http://www.google.com', 'Best search engine', array('color'=>'0000FF', 'underline'=>PHPWord_Style_Font::UNDERLINE_SINGLE));
$section->addTextBreak(2);

$PHPWord->addLinkStyle('myOwnLinkStyle', array('bold'=>true, 'color'=>'808000'));
$section->addLink('http://www.bing.com', null, 'myOwnLinkStyle');
$section->addLink('http://www.yahoo.com', null, 'myOwnLinkStyle');




// Save File
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$objWriter->save('Link.docx');
?>