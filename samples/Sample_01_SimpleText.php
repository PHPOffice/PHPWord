<?php
include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s') , " Create new PhpWord object" , \EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$phpWord->addFontStyle('rStyle', array('bold' => true, 'italic' => true, 'size' => 16));
$phpWord->addParagraphStyle('pStyle', array('align' => 'center', 'spaceAfter' => 100));
$phpWord->addTitleStyle(1, array('bold' => true), array('spaceAfter' => 240));

// New portrait section
$section = $phpWord->createSection();

// Simple text
$section->addTitle('Welcome to PhpWord', 1);
$section->addText('Hello World!');

// Two text break
$section->addTextBreak(2);

// Defined style
$section->addText('I am styled by a font style definition.', 'rStyle');
$section->addText('I am styled by a paragraph style definition.', null, 'pStyle');
$section->addText('I am styled by both font and paragraph style.', 'rStyle', 'pStyle');
$section->addTextBreak();

// Inline font style
$fontStyle['name'] = 'Times New Roman';
$fontStyle['size'] = 20;
$fontStyle['bold'] = true;
$fontStyle['italic'] = true;
$fontStyle['underline'] = 'dash';
$fontStyle['strikethrough'] = true;
$fontStyle['superScript'] = true;
$fontStyle['color'] = 'FF0000';
$fontStyle['fgColor'] = 'yellow';
$section->addText('I am inline styled.', $fontStyle);
$section->addTextBreak();

// Link
$section->addLink('http://www.google.com', null, 'NLink');
$section->addTextBreak();

// Image
$section->addImage('resources/_earth.jpg', array('width'=>18, 'height'=>18));

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
