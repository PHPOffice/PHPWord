<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// Define styles
$linkFontStyleName = 'myOwnLinStyle';
$phpWord->addLinkStyle($linkFontStyleName, array('bold' => true, 'color' => '808000'));

// New section
$section = $phpWord->addSection();

// Add hyperlink elements
$section->addLink(
    'https://github.com/PHPOffice/PHPWord',
    'PHPWord on GitHub',
    array('color' => '0000FF', 'underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE)
);
$section->addTextBreak(2);
$section->addLink('http://www.bing.com', null, $linkFontStyleName);
$section->addLink('http://www.yahoo.com', null, $linkFontStyleName);

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
