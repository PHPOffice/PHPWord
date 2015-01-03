<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// Begin code
$section = $phpWord->addSection();

// Add hyperlink elements
$section->addLink(
    'http://www.google.com',
    htmlspecialchars('Best search engine'),
    array('color' => '0000FF', 'underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE)
);
$section->addTextBreak(2);

$phpWord->addLinkStyle('myOwnLinkStyle', array('bold' => true, 'color' => '808000'));
$section->addLink('http://www.bing.com', null, 'myOwnLinkStyle');
$section->addLink('http://www.yahoo.com', null, 'myOwnLinkStyle');

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
