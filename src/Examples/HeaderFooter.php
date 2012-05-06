<?php
require_once '../PHPWord.php';

// New Word Document
$PHPWord = new PHPWord();

// New portrait section
$section = $PHPWord->createSection();

// Add header
$header = $section->createHeader();
$table = $header->addTable();
$table->addRow();
$table->addCell(4500)->addText('This is the header.');
$table->addCell(4500)->addImage('_earth.jpg', array('width'=>50, 'height'=>50, 'align'=>'right'));

// Add footer
$footer = $section->createFooter();
$footer->addPreserveText('Page {PAGE} of {NUMPAGES}.', array('align'=>'center'));

// Write some text
$section->addTextBreak();
$section->addText('Some text...');

// Save File
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$objWriter->save('HeaderFooter.docx');
?>