<?php

use PhpOffice\PhpWord\Style\Font;

include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s') , ' Create new PhpWord object' , EOL;

$languageEnGb = new \PhpOffice\PhpWord\Style\Language(\PhpOffice\PhpWord\Style\Language::EN_GB);

$phpWord = new \PhpOffice\PhpWord\PhpWord();
$phpWord->getSettings()->setThemeFontLang($languageEnGb);

$fontStyleName = 'rStyle';
$phpWord->addFontStyle($fontStyleName, ['bold' => true, 'italic' => true, 'size' => 16, 'allCaps' => true, 'doubleStrikethrough' => true]);

$paragraphStyleName = 'pStyle';
$phpWord->addParagraphStyle($paragraphStyleName, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 100]);

$phpWord->addTitleStyle(1, ['bold' => true], ['spaceAfter' => 240]);

// New portrait section
$section = $phpWord->addSection();

// Simple text
$section->addTitle('Welcome to PhpWord', 1);
$section->addText('Hello World!');

// $pStyle = new Font();
// $pStyle->setLang()
$section->addText('Ce texte-ci est en français.', ['lang' => \PhpOffice\PhpWord\Style\Language::FR_BE]);

// Two text break
$section->addTextBreak(2);

// Define styles
$section->addText('I am styled by a font style definition.', $fontStyleName);
$section->addText('I am styled by a paragraph style definition.', null, $paragraphStyleName);
$section->addText('I am styled by both font and paragraph style.', $fontStyleName, $paragraphStyleName);

$section->addTextBreak();

// Inline font style
$fontStyle['name'] = 'Times New Roman';
$fontStyle['size'] = 20;

$textrun = $section->addTextRun();
$textrun->addText('I am inline styled ', $fontStyle);
$textrun->addText('with ');
$textrun->addText('color', ['color' => '996699']);
$textrun->addText(', ');
$textrun->addText('bold', ['bold' => true]);
$textrun->addText(', ');
$textrun->addText('italic', ['italic' => true]);
$textrun->addText(', ');
$textrun->addText('underline', ['underline' => 'dash']);
$textrun->addText(', ');
$textrun->addText('strikethrough', ['strikethrough' => true]);
$textrun->addText(', ');
$textrun->addText('doubleStrikethrough', ['doubleStrikethrough' => true]);
$textrun->addText(', ');
$textrun->addText('superScript', ['superScript' => true]);
$textrun->addText(', ');
$textrun->addText('subScript', ['subScript' => true]);
$textrun->addText(', ');
$textrun->addText('smallCaps', ['smallCaps' => true]);
$textrun->addText(', ');
$textrun->addText('allCaps', ['allCaps' => true]);
$textrun->addText(', ');
$textrun->addText('fgColor', ['fgColor' => 'yellow']);
$textrun->addText(', ');
$textrun->addText('scale', ['scale' => 200]);
$textrun->addText(', ');
$textrun->addText('spacing', ['spacing' => 120]);
$textrun->addText(', ');
$textrun->addText('kerning', ['kerning' => 10]);
$textrun->addText('. ');

// Link
$section->addLink('https://github.com/PHPOffice/PHPWord', 'PHPWord on GitHub');
$section->addTextBreak();

// Image
$section->addImage('resources/_earth.jpg', ['width' => 18, 'height' => 18]);

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
