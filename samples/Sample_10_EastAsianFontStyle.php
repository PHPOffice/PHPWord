<?php
include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s') , ' Create new PHPWord object' , EOL;
$PHPWord = new PHPWord();
$section = $PHPWord->createSection();
$header = array('size' => 16, 'bold' => true);
//1.Use EastAisa FontStyle
$section->addText('中文楷体样式测试',array('name' => '楷体', 'size' => 16, 'color' => '1B2232'));

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
