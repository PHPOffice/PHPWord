<?php
declare(strict_types=1);
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Colors\Hex;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Lengths\Absolute;

include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new PhpWord();

// Define styles
$paragraphStyleName = 'pStyle';
$phpWord->addParagraphStyle($paragraphStyleName, array('spacing' => Absolute::from('twip', 100)));

$boldFontStyleName = 'BoldText';
$phpWord->addFontStyle($boldFontStyleName, array('bold' => true));

$coloredFontStyleName = 'ColoredText';
$phpWord->addFontStyle($coloredFontStyleName, array('color' => new Hex('FF8080'), 'bgColor' => new Hex('FFFFCC')));

$linkFontStyleName = 'NLink';
$phpWord->addLinkStyle($linkFontStyleName, array('color' => new Hex('0000FF'), 'underline' => Font::UNDERLINE_SINGLE));

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
$textrun->addImage('resources/_earth.jpg', array('width' => Absolute::from('pt', 18), 'height' => Absolute::from('pt', 18)));
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
