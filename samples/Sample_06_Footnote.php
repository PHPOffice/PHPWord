<?php
include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s') , " Create new PhpWord object" , \EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();
\PhpOffice\PhpWord\Settings::setCompatibility(false);

// New portrait section
$section = $phpWord->createSection();

// Add style definitions
$phpWord->addParagraphStyle('pStyle', array('spacing'=>100));
$phpWord->addFontStyle('BoldText', array('bold'=>true));
$phpWord->addFontStyle('ColoredText', array('color'=>'FF8080'));
$phpWord->addLinkStyle('NLink', array('color'=>'0000FF', 'underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE));

// Add text elements
$textrun = $section->createTextRun('pStyle');
$textrun->addText('This is some lead text in a paragraph with a following footnote. ','pStyle');

$footnote = $textrun->createFootnote();
$footnote->addText('Just like a textrun, a footnote can contain native texts. ');
$footnote->addText('No break is placed after adding an element. ', 'BoldText');
$footnote->addText('All elements are placed inside a paragraph. ', 'ColoredText');
$footnote->addTextBreak();
$footnote->addText('But you can insert a manual text break like above, ');
$footnote->addText('links like ');
$footnote->addLink('http://www.google.com', null, 'NLink');
$footnote->addText(', or image like ');
$footnote->addImage('resources/_earth.jpg', array('width' => 18, 'height' => 18));
$footnote->addText('But you can only put footnote in section, not in header or footer.');

$section->addText('You can also create the footnote directly from the section making it wrap in a paragraph like the footnote below this paragraph. But is is best used from within a textrun.');
$footnote = $section->createFootnote();
$footnote->addText('The reference for this is wrapped in its own line');

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
