<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s') , " Create new PHPWord object" , EOL;
$PHPWord = new PHPWord();

// New portrait section
$section = $PHPWord->createSection();

// Add first page header
$header = $section->createHeader();
$header->firstPage();
$table = $header->addTable();
$table->addRow();
$table->addCell(4500)->addText('This is the header.');
$table->addCell(4500)->addImage(
    'resources/PHPWord.png',
    array('width' => 80, 'height' => 80, 'align' => 'right')
);

// Add header for all other pages
$subsequent = $section->createHeader();
$subsequent->addText("Subsequent pages in Section 1 will Have this!");

// Add footer
$footer = $section->createFooter();
$footer->addPreserveText('Page {PAGE} of {NUMPAGES}', array('color' => 'FF0000'), array('align' => 'center'));

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


// Save file
$name = basename(__FILE__, '.php');
$writers = array('Word2007' => 'docx', 'ODText' => 'odt', 'RTF' => 'rtf');
foreach ($writers as $writer => $extension) {
    echo date('H:i:s'), " Write to {$writer} format", EOL;
    $objWriter = PHPWord_IOFactory::createWriter($PHPWord, $writer);
    $objWriter->save("{$name}.{$extension}");
    rename("{$name}.{$extension}", "results/{$name}.{$extension}");
}

include_once 'Sample_Footer.php';
