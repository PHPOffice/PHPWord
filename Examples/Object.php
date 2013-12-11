<?php
require_once '../PHPWord.php';

// New Word Document
$PHPWord = new PHPWord();

// New portrait section
$section = $PHPWord->createSection();

// Add text elements
$section->addText('You can open this OLE object by double clicking on the icon:');
$section->addTextBreak(2);

// Add object
$section->addObject('_sheet.xls');

// Save File
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$objWriter->save('Object.docx');
?>
