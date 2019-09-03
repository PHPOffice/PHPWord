<?php
declare(strict_types=1);
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Colors\Hex;
use PhpOffice\PhpWord\Style\Font;

include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new PhpWord();

// Define styles
$linkFontStyleName = 'myOwnLinStyle';
$phpWord->addLinkStyle($linkFontStyleName, array('bold' => true, 'color' => new Hex('808000')));

// New section
$section = $phpWord->addSection();

// Add hyperlink elements
$section->addLink(
    'https://github.com/PHPOffice/PHPWord',
    'PHPWord on GitHub',
    array('color' => new Hex('0000FF'), 'underline' => Font::UNDERLINE_SINGLE)
);
$section->addTextBreak(2);
$section->addLink('http://www.bing.com', null, $linkFontStyleName);
$section->addLink('http://www.yahoo.com', null, $linkFontStyleName);

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
