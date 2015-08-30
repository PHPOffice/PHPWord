<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// New portrait section
$section = $phpWord->addSection();

// Add first page header
$header = $section->addHeader();
$header->firstPage();
$table = $header->addTable();
$table->addRow();
$cell = $table->addCell(4500);
$textrun = $cell->addTextRun();
$textrun->addText(htmlspecialchars('This is the header with '));
$textrun->addLink('http://google.com', htmlspecialchars('link to Google'));
$table->addCell(4500)->addImage(
    'resources/PhpWord.png',
    array('width' => 80, 'height' => 80, 'align' => 'right')
);

// Add header for all other pages
$subsequent = $section->addHeader();
$subsequent->addText(htmlspecialchars('Subsequent pages in Section 1 will Have this!'));
$subsequent->addImage('resources/_mars.jpg', array('width' => 80, 'height' => 80));

// Add footer
$footer = $section->addFooter();
$footer->addPreserveText(htmlspecialchars('Page {PAGE} of {NUMPAGES}.'), null, array('align' => 'center'));
$footer->addLink('http://google.com', htmlspecialchars('Direct Google'));

// Write some text
$section->addTextBreak();
$section->addText(htmlspecialchars('Some text...'));

// Create a second page
$section->addPageBreak();

// Write some text
$section->addTextBreak();
$section->addText(htmlspecialchars('Some text...'));

// Create a third page
$section->addPageBreak();

// Write some text
$section->addTextBreak();
$section->addText(htmlspecialchars('Some text...'));

// New portrait section
$section2 = $phpWord->addSection();

$sec2Header = $section2->addHeader();
$sec2Header->addText(htmlspecialchars('All pages in Section 2 will Have this!'));

// Write some text
$section2->addTextBreak();
$section2->addText(htmlspecialchars('Some text...'));

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
