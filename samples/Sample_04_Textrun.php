<?php
include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// Define styles
$paragraphStyleName = 'pStyle';
$phpWord->addParagraphStyle($paragraphStyleName, array('spacing' => 100));

$boldFontStyleName = 'BoldText';
$phpWord->addFontStyle($boldFontStyleName, array('bold' => true));

$coloredFontStyleName = 'ColoredText';
$phpWord->addFontStyle($coloredFontStyleName, array('color' => 'FF8080', 'bgColor' => 'FFFFCC'));

$linkFontStyleName = 'NLink';
$phpWord->addLinkStyle($linkFontStyleName, array('color' => '0000FF', 'underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE));

// New portrait section
$section = $phpWord->addSection();

// Add text run
$textrun = $section->addTextRun($paragraphStyleName);
$textrun->addText('Each textrun can contain native text, link elements or an image.');
$textrun->addText(' No break is placed after adding an element.', $boldFontStyleName);
$textrun->addText(' Both ');
$textrun->addText('superscript', array('superScript' => true));
$textrun->addText(' and ');
$textrun->addText('subscript', array('subScript' => true));
$textrun->addText(' are also available.');
$textrun->addText(' All elements are placed inside a paragraph with the optionally given paragraph style.', $coloredFontStyleName);
$textrun->addText(' Sample Link: ');
$textrun->addLink('https://github.com/PHPOffice/PHPWord', 'PHPWord on GitHub', $linkFontStyleName);
$textrun->addText(' Sample Image: ');
$textrun->addImage('resources/_earth.jpg', array('width' => 18, 'height' => 18));
$textrun->addText(' Sample Object: ');
$textrun->addObject('resources/_sheet.xls');
$textrun->addText(' Here is some more text. ');

$textrun = $section->addTextRun();
$textrun->addText('This text is not visible.', array('hidden' => true));

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
