<?php
require_once '../PHPWord.php';

// New Word Document
$PHPWord = new PHPWord();

// New portrait section
$section = $PHPWord->createSection();

// Add first page header
$header = $section->createHeader();
$header->firstPage();
$table = $header->addTable();
$table->addRow();
$table->addCell(4500)->addText('This is the header.');
$table->addCell(4500)->addImage('_earth.jpg', array('width'=>50, 'height'=>50, 'align'=>'right'));

// Add header for all other pages
$subsequent = $section->createHeader();
$subsequent->addText("Subsequent pages in Section 1 will Have this!");

// Add footer
$footer = $section->createFooter();
$footer->addPreserveText('Page {PAGE} of {NUMPAGES}.', array('align'=>'center'));

// Write some text
$section->addTextBreak();
$section->addText('Some text...');

// Create a second page
$section->addPageBreak();

// Write some text
$section->addTextBreak();
$section->addText('Some text...');

// Create a third page
$section->addPageBreak();

// Write some text
$section->addTextBreak();
$section->addText('Some text...');

// New portrait section
$section2 = $PHPWord->createSection();

$sec2Header = $section2->createHeader();
$sec2Header->addText("All pages in Section 2 will Have this!");

// Write some text
$section2->addTextBreak();
$section2->addText('Some text...');

// Save File
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$objWriter->save('HeaderFooter.docx');
?>