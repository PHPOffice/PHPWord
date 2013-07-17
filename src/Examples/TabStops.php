<?php
require_once '../PHPWord.php';

// New Word Document
$PHPWord = new PHPWord();

$PHPWord->addParagraphStyle('multipleTab', array(
    'tabs' => array(
        new PHPWord_Style_Tab("left", 1550),
        new PHPWord_Style_Tab("center", 3200),
        new PHPWord_Style_Tab("right", 5300)
    )
));

$PHPWord->addParagraphStyle('rightTab', array(
    'tabs' => array(
        new PHPWord_Style_Tab("right", 9090)
    )
));

$PHPWord->addParagraphStyle('centerTab', array(
    'tabs' => array(
        new PHPWord_Style_Tab("center", 4680)
    )
));

// New portrait section
$section = $PHPWord->createSection();

// Add listitem elements
$section->addText("Multiple Tabs:\tOne\tTwo\tThree", NULL, 'multipleTab');
$section->addText("Left Aligned\tRight Aligned", NULL, 'rightTab');
$section->addText("\tCenter Aligned",            NULL, 'centerTab');

// Save File
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$objWriter->save('TabStops.docx');
?>