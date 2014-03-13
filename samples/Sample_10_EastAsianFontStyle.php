<?php

error_reporting(E_ALL);

if(php_sapi_name() == 'cli' && empty($_SERVER['REMOTE_ADDR'])) {
  define('EOL', PHP_EOL);
} else {
  define('EOL', '<br />');
}

require_once '../Classes/PHPWord.php';

// New Word Document
echo date('H:i:s') , ' Create new PHPWord object' , EOL;
$PHPWord = new PHPWord();
$section = $PHPWord->createSection();
$header = array('size' => 16, 'bold' => true);
//1.Use EastAisa FontStyle
$section->addText('中文楷体样式测试',array('name' => '楷体', 'size' => 16, 'color' => '1B2232'));

// Save File
echo date('H:i:s') , ' Write to Word2007 format' , EOL;
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$objWriter->save(str_replace('.php', '.docx', __FILE__));

// Echo memory peak usage
echo date('H:i:s') , ' Peak memory usage: ' , (memory_get_peak_usage(true) / 1024 / 1024) , ' MB' , EOL;

// Echo done
echo date('H:i:s') , ' Done writing file' , EOL;
