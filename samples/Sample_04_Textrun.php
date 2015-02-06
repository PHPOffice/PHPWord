<?php
include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// Ads styles
$phpWord->addParagraphStyle('pStyle', array('spacing' => 100));
$phpWord->addFontStyle('BoldText', array('bold' => true));
$phpWord->addFontStyle('ColoredText', array('color' => 'FF8080', 'bgColor' => 'FFFFCC'));
$phpWord->addLinkStyle(
    'NLink',
    array('color' => '0000FF', 'underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE)
);

// New portrait section
$section = $phpWord->addSection();

// Add text run
$textrun = $section->addTextRun('pStyle');

$textrun->addText(htmlspecialchars('Each textrun can contain native text, link elements or an image.', ENT_COMPAT, 'UTF-8'));
$textrun->addText(htmlspecialchars(' No break is placed after adding an element.', ENT_COMPAT, 'UTF-8'), 'BoldText');
$textrun->addText(htmlspecialchars(' Both ', ENT_COMPAT, 'UTF-8'));
$textrun->addText(htmlspecialchars('superscript', ENT_COMPAT, 'UTF-8'), array('superScript' => true));
$textrun->addText(htmlspecialchars(' and ', ENT_COMPAT, 'UTF-8'));
$textrun->addText(htmlspecialchars('subscript', ENT_COMPAT, 'UTF-8'), array('subScript' => true));
$textrun->addText(htmlspecialchars(' are also available.', ENT_COMPAT, 'UTF-8'));
$textrun->addText(
    htmlspecialchars(' All elements are placed inside a paragraph with the optionally given p-Style.', ENT_COMPAT, 'UTF-8'),
    'ColoredText'
);
$textrun->addText(htmlspecialchars(' Sample Link: ', ENT_COMPAT, 'UTF-8'));
$textrun->addLink('https://github.com/PHPOffice/PHPWord', htmlspecialchars('PHPWord on GitHub', ENT_COMPAT, 'UTF-8'), 'NLink');
$textrun->addText(htmlspecialchars(' Sample Image: ', ENT_COMPAT, 'UTF-8'));
$textrun->addImage('resources/_earth.jpg', array('width' => 18, 'height' => 18));
$textrun->addText(htmlspecialchars(' Sample Object: ', ENT_COMPAT, 'UTF-8'));
$textrun->addObject('resources/_sheet.xls');
$textrun->addText(htmlspecialchars(' Here is some more text. ', ENT_COMPAT, 'UTF-8'));

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
