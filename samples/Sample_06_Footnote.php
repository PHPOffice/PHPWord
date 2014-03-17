<?php
include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s') , " Create new PHPWord object" , EOL;
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
$textrun->addText('This is some lead text in a paragraph with a following footnote. ','pStyle');

$footnote = $textrun->createFootnote();
$footnote->addText('Just like a textrun a footnote can contain native text and link elements.');
$footnote->addText(' No break is placed after adding an element.', 'BoldText');
$footnote->addText(' All elements are placed inside a paragraph.', 'ColoredText');
$footnote->addText(' The best search engine: ');
$footnote->addLink('http://www.google.com', null, 'NLink');
$footnote->addText('. Also not bad: ');
$footnote->addLink('http://www.bing.com', null, 'NLink');

$textrun->addText('The trailing text in the paragraph.');

$section->addText('You can also create the footnote directly from the section making it wrap in a paragraph like the footnote below this paragraph. But is is best used from within a textrun.');
$footnote = $section->createFootnote();
$footnote->addText('The reference for this is wrapped in its own line');

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
