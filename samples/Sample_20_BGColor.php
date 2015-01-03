<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$section = $phpWord->addSection();

$section->addText(
    htmlspecialchars('This is some text highlighted using fgColor (limited to 15 colors)     '),
    array('fgColor' => \PhpOffice\PhpWord\Style\Font::FGCOLOR_YELLOW)
);
$section->addText(
    htmlspecialchars('This one uses bgColor and is using hex value (0xfbbb10)'),
    array('bgColor' => 'fbbb10')
);
$section->addText(htmlspecialchars('Compatible with font colors'), array('color' => '0000ff', 'bgColor' => 'fbbb10'));

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
