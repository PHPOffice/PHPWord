<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

$section = $phpWord->addSection();
$section->addTitle('This is page 1', 1);
$linkIsInternal = true;
$section->addLink('MyBookmark', 'Take me to page 3', null, null, $linkIsInternal);
$section->addPageBreak();
$section->addTitle('This is page 2', 1);
$section->addPageBreak();
$section->addTitle('This is page 3', 1);
$section->addBookmark('MyBookmark');

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
