<?php
require_once '../Classes/PHPWord.php';

$PHPWord = new PHPWord();

$document = $PHPWord->loadTemplate('Sample_03_TemplateCloneRow.docx');

$document->cloneRow('rowValue', 10);

$document->setValue('rowValue#1', 'Sun');
$document->setValue('rowValue#2', 'Mercury');
$document->setValue('rowValue#3', 'Venus');
$document->setValue('rowValue#4', 'Earth');
$document->setValue('rowValue#5', 'Mars');
$document->setValue('rowValue#6', 'Jupiter');
$document->setValue('rowValue#7', 'Saturn');
$document->setValue('rowValue#8', 'Uranus');
$document->setValue('rowValue#9', 'Neptun');
$document->setValue('rowValue#10', 'Pluto');

$document->setValue('rowNumber#1', '1');
$document->setValue('rowNumber#2', '2');
$document->setValue('rowNumber#3', '3');
$document->setValue('rowNumber#4', '4');
$document->setValue('rowNumber#5', '5');
$document->setValue('rowNumber#6', '6');
$document->setValue('rowNumber#7', '7');
$document->setValue('rowNumber#8', '8');
$document->setValue('rowNumber#9', '9');
$document->setValue('rowNumber#10', '10');

$document->setValue('weekday', date('l'));
$document->setValue('time', date('H:i'));

$document->save('Sample_03_TemplateCloneRow_result.docx');
