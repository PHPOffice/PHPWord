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
$textrun->addText(htmlspecialchars('This is the header with ', ENT_COMPAT, 'UTF-8'));
$textrun->addLink('https://github.com/PHPOffice/PHPWord', htmlspecialchars('PHPWord on GitHub', ENT_COMPAT, 'UTF-8'));
$table->addCell(4500)->addImage('resources/PhpWord.png', array('width' => 80, 'height' => 80, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END));

// Add header for all other pages
$subsequent = $section->addHeader();
$subsequent->addText(htmlspecialchars('Subsequent pages in Section 1 will Have this!', ENT_COMPAT, 'UTF-8'));
$subsequent->addImage('resources/_mars.jpg', array('width' => 80, 'height' => 80));

// Add footer
$footer = $section->addFooter();
$footer->addPreserveText(htmlspecialchars('Page {PAGE} of {NUMPAGES}.', ENT_COMPAT, 'UTF-8'), null, array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
$footer->addLink('https://github.com/PHPOffice/PHPWord', htmlspecialchars('PHPWord on GitHub', ENT_COMPAT, 'UTF-8'));

// Write some text
$section->addTextBreak();
$section->addText(htmlspecialchars('Some text...', ENT_COMPAT, 'UTF-8'));

// Create a second page
$section->addPageBreak();

// Write some text
$section->addTextBreak();
$section->addText(htmlspecialchars('Some text...', ENT_COMPAT, 'UTF-8'));

// Create a third page
$section->addPageBreak();

// Write some text
$section->addTextBreak();
$section->addText(htmlspecialchars('Some text...', ENT_COMPAT, 'UTF-8'));

// New portrait section
$section2 = $phpWord->addSection();

$sec2Header = $section2->addHeader();
$sec2Header->addText(htmlspecialchars('All pages in Section 2 will Have this!', ENT_COMPAT, 'UTF-8'));

// Write some text
$section2->addTextBreak();
$section2->addText(htmlspecialchars('Some text...', ENT_COMPAT, 'UTF-8'));

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
