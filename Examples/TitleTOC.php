<?php
require_once '../PHPWord.php';

// New Word Document
$PHPWord = new PHPWord();

// New portrait section
$section = $PHPWord->createSection();

// Define the TOC font style
$fontStyle = array('spaceAfter'=>60, 'size'=>12);

// Add title styles
$PHPWord->addTitleStyle(1, array('size'=>20, 'color'=>'333333', 'bold'=>true));
$PHPWord->addTitleStyle(2, array('size'=>16, 'color'=>'666666'));

// Add text elements
$section->addText('Table of contents:');
$section->addTextBreak(2);

// Add TOC
$section->addTOC($fontStyle);

// Add Titles
$section->addPageBreak();
$section->addTitle('I am Title 1', 1);
$section->addText('Some text...');
$section->addTextBreak(2);

$section->addTitle('I am a Subtitle of Title 1', 2);
$section->addTextBreak(2);
$section->addText('Some more text...');
$section->addTextBreak(2);

$section->addTitle('Another Title (Title 2)', 1);
$section->addText('Some text...');
$section->addPageBreak();
$section->addTitle('I am Title 3', 1);
$section->addText('And more text...');
$section->addTextBreak(2);
$section->addTitle('I am a Subtitle of Title 3', 2);
$section->addText('Again and again, more text...');

echo 'Note: The pagenumbers in the TOC doesnt refresh automatically.';

// Save File
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$objWriter->save('TitleTOC.docx');
?>
