<?php
declare(strict_types=1);
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Colors\Hex;
use PhpOffice\PhpWord\Style\Lengths\Absolute;

include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new PhpWord();
$section = $phpWord->addSection();
$header = array('size' => Absolute::from('pt', 16), 'bold' => true);
//1.Use EastAisa FontStyle
$section->addText('中文楷体样式测试', array('name' => '楷体', 'size' => Absolute::from('pt', 16), 'color' => new Hex('1B2232'), 'lang' => array('latin' => 'en-US', 'eastAsia' => 'zh-CN')));

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
