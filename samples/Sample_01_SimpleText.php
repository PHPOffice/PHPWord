<?php
include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s') , ' Create new PhpWord object' , EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$phpWord->addFontStyle('rStyle', array('bold' => true, 'italic' => true, 'size' => 16, 'allCaps' => true, 'doubleStrikethrough' => true));
$phpWord->addParagraphStyle('pStyle', array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 100));
$phpWord->addTitleStyle(1, array('bold' => true), array('spaceAfter' => 240));

// New portrait section
$section = $phpWord->addSection();

// Simple text
$section->addTitle(htmlspecialchars('Welcome to PhpWord', ENT_COMPAT, 'UTF-8'), 1);
$section->addText(htmlspecialchars('Hello World!', ENT_COMPAT, 'UTF-8'));

// Two text break
$section->addTextBreak(2);

// Defined style
$section->addText(htmlspecialchars('I am styled by a font style definition.', ENT_COMPAT, 'UTF-8'), 'rStyle');
$section->addText(htmlspecialchars('I am styled by a paragraph style definition.', ENT_COMPAT, 'UTF-8'), null, 'pStyle');
$section->addText(htmlspecialchars('I am styled by both font and paragraph style.', ENT_COMPAT, 'UTF-8'), 'rStyle', 'pStyle');

$section->addTextBreak();

// Inline font style
$fontStyle['name'] = 'Times New Roman';
$fontStyle['size'] = 20;

$textrun = $section->addTextRun();
$textrun->addText(htmlspecialchars('I am inline styled ', ENT_COMPAT, 'UTF-8'), $fontStyle);
$textrun->addText(htmlspecialchars('with ', ENT_COMPAT, 'UTF-8'));
$textrun->addText(htmlspecialchars('color', ENT_COMPAT, 'UTF-8'), array('color' => '996699'));
$textrun->addText(htmlspecialchars(', ', ENT_COMPAT, 'UTF-8'));
$textrun->addText(htmlspecialchars('bold', ENT_COMPAT, 'UTF-8'), array('bold' => true));
$textrun->addText(htmlspecialchars(', ', ENT_COMPAT, 'UTF-8'));
$textrun->addText(htmlspecialchars('italic', ENT_COMPAT, 'UTF-8'), array('italic' => true));
$textrun->addText(htmlspecialchars(', ', ENT_COMPAT, 'UTF-8'));
$textrun->addText(htmlspecialchars('underline', ENT_COMPAT, 'UTF-8'), array('underline' => 'dash'));
$textrun->addText(htmlspecialchars(', ', ENT_COMPAT, 'UTF-8'));
$textrun->addText(htmlspecialchars('strikethrough', ENT_COMPAT, 'UTF-8'), array('strikethrough' => true));
$textrun->addText(htmlspecialchars(', ', ENT_COMPAT, 'UTF-8'));
$textrun->addText(htmlspecialchars('doubleStrikethrough', ENT_COMPAT, 'UTF-8'), array('doubleStrikethrough' => true));
$textrun->addText(htmlspecialchars(', ', ENT_COMPAT, 'UTF-8'));
$textrun->addText(htmlspecialchars('superScript', ENT_COMPAT, 'UTF-8'), array('superScript' => true));
$textrun->addText(htmlspecialchars(', ', ENT_COMPAT, 'UTF-8'));
$textrun->addText(htmlspecialchars('subScript', ENT_COMPAT, 'UTF-8'), array('subScript' => true));
$textrun->addText(htmlspecialchars(', ', ENT_COMPAT, 'UTF-8'));
$textrun->addText(htmlspecialchars('smallCaps', ENT_COMPAT, 'UTF-8'), array('smallCaps' => true));
$textrun->addText(htmlspecialchars(', ', ENT_COMPAT, 'UTF-8'));
$textrun->addText(htmlspecialchars('allCaps', ENT_COMPAT, 'UTF-8'), array('allCaps' => true));
$textrun->addText(htmlspecialchars(', ', ENT_COMPAT, 'UTF-8'));
$textrun->addText(htmlspecialchars('fgColor', ENT_COMPAT, 'UTF-8'), array('fgColor' => 'yellow'));
$textrun->addText(htmlspecialchars(', ', ENT_COMPAT, 'UTF-8'));
$textrun->addText(htmlspecialchars('scale', ENT_COMPAT, 'UTF-8'), array('scale' => 200));
$textrun->addText(htmlspecialchars(', ', ENT_COMPAT, 'UTF-8'));
$textrun->addText(htmlspecialchars('spacing', ENT_COMPAT, 'UTF-8'), array('spacing' => 120));
$textrun->addText(htmlspecialchars(', ', ENT_COMPAT, 'UTF-8'));
$textrun->addText(htmlspecialchars('kerning', ENT_COMPAT, 'UTF-8'), array('kerning' => 10));
$textrun->addText(htmlspecialchars('. ', ENT_COMPAT, 'UTF-8'));

// Link
$section->addLink('https://github.com/PHPOffice/PHPWord', htmlspecialchars('PHPWord on GitHub', ENT_COMPAT, 'UTF-8'));
$section->addTextBreak();

// Image
$section->addImage('resources/_earth.jpg', array('width'=>18, 'height'=>18));

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
