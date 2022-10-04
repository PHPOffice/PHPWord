<?php

include_once 'Sample_Header.php';

// Read contents
$name = basename(__FILE__, '.php');
$source = __DIR__ . "/resources/{$name}.docx";

echo date('H:i:s'), " Reading contents from `{$source}`", EOL;
$phpWord = \PhpOffice\PhpWord\IOFactory::load($source);

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
