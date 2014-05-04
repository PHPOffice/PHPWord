<?php
include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s') , ' Create new PhpWord object' , EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// Ads styles
$phpWord->addParagraphStyle('pStyle', array('spacing'=>100));
$phpWord->addFontStyle('BoldText', array('bold'=>true));
$phpWord->addFontStyle('ColoredText', array('color'=>'FF8080', 'bgColor' => 'FFFFCC'));
$phpWord->addLinkStyle('NLink', array('color'=>'0000FF', 'underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE));

// New portrait section
$section = $phpWord->addSection();

// Add text run
$textrun = $section->addTextRun('pStyle');

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
$textrun->addImage('resources/_earth.jpg', array('width' => 18, 'height' => 18));
$textrun->addText(' Sample Object: ');
$textrun->addObject('resources/_sheet.xls');
$textrun->addText(' Here is some more text. ');

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
