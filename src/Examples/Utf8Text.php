<?php

require_once '../PHPWord.php';

$utf8Str = '福建省泉州市惠南工业区北一路 • Plain Text << ___ More text?';

$PHPWord = new PHPWord();
$section = $PHPWord->createSection();
$section->addText($utf8Str);

$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$objWriter->save('Utf8Text.docx');