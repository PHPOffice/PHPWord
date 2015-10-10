<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

$section = $phpWord->addSection();
$textrun = $section->addTextRun();
$textrun->addText(htmlspecialchars('This is a Left to Right paragraph.', ENT_COMPAT, 'UTF-8'));

$textrun = $section->addTextRun(array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END));
$textrun->addText(htmlspecialchars('سلام این یک پاراگراف راست به چپ است', ENT_COMPAT, 'UTF-8'), array('rtl' => true));

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
