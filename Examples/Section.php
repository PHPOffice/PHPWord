<?php
require_once '../PHPWord.php';

// New Word Document
$PHPWord = new PHPWord();

// New portrait section
$section = $PHPWord->createSection(array('borderColor'=>'00FF00', 'borderSize'=>12));
$section->addText('I am placed on a default section.');

// New landscape section
$section = $PHPWord->createSection(array('orientation'=>'landscape'));
$section->addText('I am placed on a landscape section. Every page starting from this section will be landscape style.');
$section->addPageBreak();
$section->addPageBreak();

// New portrait section
$section = $PHPWord->createSection(array('marginLeft'=>600, 'marginRight'=>600, 'marginTop'=>600, 'marginBottom'=>600));
$section->addText('This section uses other margins.');



// Save File
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$objWriter->save('Section.docx');
?>