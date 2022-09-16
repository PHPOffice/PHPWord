<?php

include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$section = $phpWord->addSection();
$header = ['size' => 16, 'bold' => true];
//1.Use EastAisa FontStyle
$section->addText('中文楷体样式测试', ['name' => '楷体', 'size' => 16, 'color' => '1B2232', 'lang' => ['latin' => 'en-US', 'eastAsia' => 'zh-CN']]);

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
