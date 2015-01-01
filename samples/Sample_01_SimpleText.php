<?php
include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s') , ' Create new PhpWord object' , EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$phpWord->addFontStyle('rStyle', array('bold' => true, 'italic' => true, 'size' => 16, 'allCaps' => true, 'doubleStrikethrough' => true));
$phpWord->addParagraphStyle('pStyle', array('align' => 'center', 'spaceAfter' => 100));
$phpWord->addTitleStyle(1, array('bold' => true), array('spaceAfter' => 240));

// New portrait section
$section = $phpWord->addSection();

// Simple text
$section->addTitle(htmlspecialchars('Welcome to PhpWord'), 1);
$section->addText(htmlspecialchars('Hello World!'));

// Two text break
$section->addTextBreak(2);

// Defined style
$section->addText(htmlspecialchars('I am styled by a font style definition.'), 'rStyle');
$section->addText(htmlspecialchars('I am styled by a paragraph style definition.'), null, 'pStyle');
$section->addText(htmlspecialchars('I am styled by both font and paragraph style.'), 'rStyle', 'pStyle');

$section->addTextBreak();

// Inline font style
$fontStyle['name'] = 'Times New Roman';
$fontStyle['size'] = 20;

$textrun = $section->addTextRun();
$textrun->addText(htmlspecialchars('I am inline styled '), $fontStyle);
$textrun->addText(htmlspecialchars('with '));
$textrun->addText(htmlspecialchars('color'), array('color' => '996699'));
$textrun->addText(htmlspecialchars(', '));
$textrun->addText(htmlspecialchars('bold'), array('bold' => true));
$textrun->addText(htmlspecialchars(', '));
$textrun->addText(htmlspecialchars('italic'), array('italic' => true));
$textrun->addText(htmlspecialchars(', '));
$textrun->addText(htmlspecialchars('underline'), array('underline' => 'dash'));
$textrun->addText(htmlspecialchars(', '));
$textrun->addText(htmlspecialchars('strikethrough'), array('strikethrough' => true));
$textrun->addText(htmlspecialchars(', '));
$textrun->addText(htmlspecialchars('doubleStrikethrough'), array('doubleStrikethrough' => true));
$textrun->addText(htmlspecialchars(', '));
$textrun->addText(htmlspecialchars('superScript'), array('superScript' => true));
$textrun->addText(htmlspecialchars(', '));
$textrun->addText(htmlspecialchars('subScript'), array('subScript' => true));
$textrun->addText(htmlspecialchars(', '));
$textrun->addText(htmlspecialchars('smallCaps'), array('smallCaps' => true));
$textrun->addText(htmlspecialchars(', '));
$textrun->addText(htmlspecialchars('allCaps'), array('allCaps' => true));
$textrun->addText(htmlspecialchars(', '));
$textrun->addText(htmlspecialchars('fgColor'), array('fgColor' => 'yellow'));
$textrun->addText(htmlspecialchars(', '));
$textrun->addText(htmlspecialchars('scale'), array('scale' => 200));
$textrun->addText(htmlspecialchars(', '));
$textrun->addText(htmlspecialchars('spacing'), array('spacing' => 120));
$textrun->addText(htmlspecialchars(', '));
$textrun->addText(htmlspecialchars('kerning'), array('kerning' => 10));
$textrun->addText(htmlspecialchars('. '));

// Link
$section->addLink('http://www.google.com', htmlspecialchars('Google'));
$section->addTextBreak();

// Image
$section->addImage('resources/_earth.jpg', array('width'=>18, 'height'=>18));

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
