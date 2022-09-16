<?php

include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// New section
$section = $phpWord->addSection();

$section->addText(
    'This is some text highlighted using fgColor (limited to 15 colors)',
    ['fgColor' => \PhpOffice\PhpWord\Style\Font::FGCOLOR_YELLOW]
);
$section->addText('This one uses bgColor and is using hex value (0xfbbb10)', ['bgColor' => 'fbbb10']);
$section->addText('Compatible with font colors', ['color' => '0000ff', 'bgColor' => 'fbbb10']);

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
