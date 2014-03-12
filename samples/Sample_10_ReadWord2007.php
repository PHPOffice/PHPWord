<?php
// error_reporting(E_ALL );

define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

require_once '../Classes/PHPWord.php';

// Read contents
$sample = 'Sample_10_ReadWord2007';
$source = "resources/{$sample}.docx";
$target = "results/{$sample}";
echo '<p><strong>', date('H:i:s'), " Reading contents from `{$source}`</strong></p>";
$PHPWord = PHPWord_IOFactory::load($source);

// Rewrite contents
echo date('H:i:s') , " Write to Word2007 format" , EOL;
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$objWriter->save("{$sample}.docx");
rename("{$sample}.docx", "{$target}.docx");

echo date('H:i:s') , ' Write to OpenDocumentText format' , EOL;
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'ODText');
$objWriter->save("{$sample}.odt");
rename("{$sample}.odt", "{$target}.odt");

echo date('H:i:s') , ' Write to RTF format' , EOL;
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'RTF');
$objWriter->save("{$sample}.rtf");
rename("{$sample}.rtf", "{$target}.rtf");

// Echo memory peak usage
echo date('H:i:s') , " Peak memory usage: " , (memory_get_peak_usage(true) / 1024 / 1024) , " MB" , EOL;
echo date('H:i:s') , " Done writing file" , EOL;
