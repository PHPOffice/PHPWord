<?php
include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s') , ' Create new PhpWord object' , EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

$section = $phpWord->addSection();
$textbox = $section->addTextBox(array('align' => 'left', 'width' => 300, 'borderSize' => 1, 'borderColor' => '#FF0000'));
$textbox->addText('Text box content ');
$textbox->addText('with bold text', array('bold' => true));
$textbox->addText(', ');
$textbox->addLink('http://www.google.com', 'link');
$textbox->addText(', and image ');
$textbox->addImage('resources/_earth.jpg', array('width' => 18, 'height' => 18));
$textbox->addText('.');

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
