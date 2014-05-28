<?php
include_once 'Sample_Header.php';

// Read contents
$name = basename(__FILE__, '.php');
$source = "results/Sample_01_SimpleText.rtf";
$source = "resources/rtf.rtf";
$source = "results/Sample_11_ReadWord2007.rtf";

echo date('H:i:s'), " Reading contents from `{$source}`", EOL;
$phpWord = \PhpOffice\PhpWord\IOFactory::load($source, 'RTF');

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
