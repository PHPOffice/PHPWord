<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), " Create new PhpWord object", EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// Begin code
$section = $phpWord->addSection();
$section->addText('Local image without any styles:');
$section->addImage('resources/_mars.jpg');
$section->addTextBreak(2);
//
$section->addText('Local image with styles:');
$section->addImage('resources/_earth.jpg', array('width' => 210, 'height' => 210, 'align' => 'center'));
$section->addTextBreak(2);

$source = 'http://php.net/images/logos/php-med-trans-light.gif';
$section->addText("Remote image from: {$source}");
$section->addImage($source);

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
