<?php
declare(strict_types=1);
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Colors\Hex;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Language;
use PhpOffice\PhpWord\Style\Lengths\Absolute;

include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s') , ' Create new PhpWord object' , EOL;

$languageEnGb = new Language(Language::EN_GB);

$phpWord = new PhpWord();
$phpWord->getSettings()->setThemeFontLang($languageEnGb);

$fontStyleName = 'rStyle';
$phpWord->addFontStyle($fontStyleName, array('bold' => true, 'italic' => true, 'size' => Absolute::from('pt', 16), 'allCaps' => true, 'doubleStrikethrough' => true));

$paragraphStyleName = 'pStyle';
$phpWord->addParagraphStyle($paragraphStyleName, array('alignment' => Jc::CENTER, 'spaceAfter' => Absolute::from('pt', 100)));

$phpWord->addTitleStyle(1, array('bold' => true), array('spaceAfter' => Absolute::from('pt', 240)));

// New portrait section
$section = $phpWord->addSection();

// Simple text
$section->addTitle('Welcome to PhpWord', 1);
$section->addText('Hello World!');

// $pStyle = new Font();
// $pStyle->setLang()
$section->addText('Ce texte-ci est en français.', array('lang' => Language::FR_BE));

// Two text break
$section->addTextBreak(2);

// Define styles
$section->addText('I am styled by a font style definition.', $fontStyleName);
$section->addText('I am styled by a paragraph style definition.', null, $paragraphStyleName);
$section->addText('I am styled by both font and paragraph style.', $fontStyleName, $paragraphStyleName);

$section->addTextBreak();

// Inline font style
$fontStyle['name'] = 'Times New Roman';
$fontStyle['size'] = Absolute::from('pt', 20);

$textrun = $section->addTextRun();
$textrun->addText('I am inline styled ', $fontStyle);
$textrun->addText('with ');
$textrun->addText('color', array('color' => new Hex('996699')));
$textrun->addText(', ');
$textrun->addText('bold', array('bold' => true));
$textrun->addText(', ');
$textrun->addText('italic', array('italic' => true));
$textrun->addText(', ');
$textrun->addText('underline', array('underline' => 'dash'));
$textrun->addText(', ');
$textrun->addText('strikethrough', array('strikethrough' => true));
$textrun->addText(', ');
$textrun->addText('doubleStrikethrough', array('doubleStrikethrough' => true));
$textrun->addText(', ');
$textrun->addText('superScript', array('superScript' => true));
$textrun->addText(', ');
$textrun->addText('subScript', array('subScript' => true));
$textrun->addText(', ');
$textrun->addText('smallCaps', array('smallCaps' => true));
$textrun->addText(', ');
$textrun->addText('allCaps', array('allCaps' => true));
$textrun->addText(', ');
$textrun->addText('fgColor', array('fgColor' => 'yellow'));
$textrun->addText(', ');
$textrun->addText('scale', array('scale' => Absolute::from('twip', 200)));
$textrun->addText(', ');
$textrun->addText('spacing', array('spacing' => Absolute::from('twip', 120)));
$textrun->addText(', ');
$textrun->addText('kerning', array('kerning' => Absolute::from('twip', 10)));
$textrun->addText('. ');

// Link
$section->addLink('https://github.com/PHPOffice/PHPWord', 'PHPWord on GitHub');
$section->addTextBreak();

// Image
$section->addImage('resources/_earth.jpg', array('width'=>Absolute::from('pt', 18), 'height'=>Absolute::from('pt', 18)));

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
