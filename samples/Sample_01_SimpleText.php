<?php

error_reporting(E_ALL);

if(php_sapi_name() == 'cli' && empty($_SERVER['REMOTE_ADDR'])) {
	define('EOL', PHP_EOL);
}
else {
	define('EOL', '<br />');
}

require_once '../src/PHPWord.php';

// New Word Document
echo date('H:i:s') , " Create new PHPWord object" , EOL;
$PHPWord = new PHPWord();

// New portrait section
$section = $PHPWord->createSection();

// Add text elements
$section->addText('Hello World!');
$section->addTextBreak(2);

$section->addText('I am inline styled.', array('name'=>'Verdana', 'color'=>'006699'));
$section->addTextBreak(2);

$PHPWord->addFontStyle('rStyle', array('bold'=>true, 'italic'=>true, 'size'=>16));
$PHPWord->addParagraphStyle('pStyle', array('align'=>'center', 'spaceAfter'=>100));
$section->addText('I am styled by two style definitions.', 'rStyle', 'pStyle');
$section->addText('I have only a paragraph style definition.', null, 'pStyle');

// Save File
echo date('H:i:s') , " Write to Word2007 format" , EOL;
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$objWriter->save(str_replace('.php', '.docx', __FILE__));

echo date('H:i:s') , " Write to OpenDocumentText format" , EOL;
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'ODText');
$objWriter->save(str_replace('.php', '.odt', __FILE__));

echo date('H:i:s') , " Write to RTF format" , EOL;
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'RTF');
$objWriter->save(str_replace('.php', '.rtf', __FILE__));


// Echo memory peak usage
echo date('H:i:s') , " Peak memory usage: " , (memory_get_peak_usage(true) / 1024 / 1024) , " MB" , EOL;

// Echo done
echo date('H:i:s') , " Done writing file" , EOL;
?>