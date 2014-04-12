<?php
include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s') , " Create new PhpWord object" , EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();
\PhpOffice\PhpWord\Settings::setCompatibility(false);

// New portrait section
$section = $phpWord->addSection();

// Add style definitions
$phpWord->addParagraphStyle('pStyle', array('spacing'=>100));
$phpWord->addFontStyle('BoldText', array('bold'=>true));
$phpWord->addFontStyle('ColoredText', array('color'=>'FF8080'));
$phpWord->addLinkStyle('NLink', array('color'=>'0000FF', 'underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE));

// Add text elements
$textrun = $section->addTextRun('pStyle');
$textrun->addText('This is some lead text in a paragraph with a following footnote. ','pStyle');

$footnote = $textrun->addFootnote();
$footnote->addText('Just like a textrun, a footnote can contain native texts. ');
$footnote->addText('No break is placed after adding an element. ', 'BoldText');
$footnote->addText('All elements are placed inside a paragraph. ', 'ColoredText');
$footnote->addTextBreak();
$footnote->addText('But you can insert a manual text break like above, ');
$footnote->addText('links like ');
$footnote->addLink('http://www.google.com', null, 'NLink');
$footnote->addText(', image like ');
$footnote->addImage('resources/_earth.jpg', array('width' => 18, 'height' => 18));
$footnote->addText(', or object like ');
$footnote->addObject('resources/_sheet.xls');
$footnote->addText('But you can only put footnote in section, not in header or footer.');

$section->addText('You can also create the footnote directly from the section making it wrap in a paragraph like the footnote below this paragraph. But is is best used from within a textrun.');
$footnote = $section->addFootnote();
$footnote->addText('The reference for this is wrapped in its own line');

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
