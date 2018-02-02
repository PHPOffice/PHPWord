<?php
include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s') , ' Create new PhpWord object' , EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// New portrait section
$section = $phpWord->addSection();

$text = $section->addText('Hello World!');

$text = $section->addText('Hello World!');
$text->setChanged(\PhpOffice\PhpWord\Element\ChangedElement::TYPE_INSERTED, 'Fred', time() - 1800);

$text = $section->addText('Hello World!');
$text->setChanged(\PhpOffice\PhpWord\Element\ChangedElement::TYPE_DELETED, 'Barney', time() - 3600);

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
