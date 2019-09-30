<?php
declare(strict_types=1);
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Colors\Hex;
use PhpOffice\PhpWord\Style\Colors\HighlightColor;

include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new PhpWord();

// New section
$section = $phpWord->addSection();

$section->addText(
    'This is some text highlighted using fgColor (limited to 15 colors)',
    array('fgColor' => new HighlightColor('yellow'))
);
$section->addText('This one uses bgColor and is using hex value (0xfbbb10)', array('bgColor' => new Hex('fbbb10')));
$section->addText('Compatible with font colors', array('color' => new Hex('0000ff'), 'bgColor' => new Hex('fbbb10')));

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
