<?php
declare(strict_types=1);
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Lengths\Absolute;

include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new PhpWord();

// Define styles
$fontStyle24 = array('size' => Absolute::from('pt', 24));

$paragraphStyle24 = array('spacing' => Absolute::from('twip', 240), 'size' => Absolute::from('pt', 24));

$fontStyleName = 'fontStyle';
$phpWord->addFontStyle($fontStyleName, array('size' => Absolute::from('pt', 9)));

$paragraphStyleName = 'paragraphStyle';
$phpWord->addParagraphStyle($paragraphStyleName, array('spacing' => Absolute::from('twip', 480)));

// New section
$section = $phpWord->addSection();

$section->addText('Text break with no style:');
$section->addTextBreak();
$section->addText('Text break with defined font style:');
$section->addTextBreak(1, $fontStyleName);
$section->addText('Text break with defined paragraph style:');
$section->addTextBreak(1, null, $paragraphStyleName);
$section->addText('Text break with inline font style:');
$section->addTextBreak(1, $fontStyle24);
$section->addText('Text break with inline paragraph style:');
$section->addTextBreak(1, null, $paragraphStyle24);
$section->addText('Done.');

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
