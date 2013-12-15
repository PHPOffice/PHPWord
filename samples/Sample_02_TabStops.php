<?php

error_reporting(E_ALL);

if(php_sapi_name() == 'cli' && empty($_SERVER['REMOTE_ADDR'])) {
  define('EOL', PHP_EOL);
}
else {
  define('EOL', '<br />');
}

require_once '../Classes/PHPWord.php';

// New Word Document
echo date('H:i:s') , ' Create new PHPWord object' , EOL;
$PHPWord = new PHPWord();

// Ads styles
$PHPWord->addParagraphStyle('multipleTab', array(
  'tabs' => array(
    new PHPWord_Style_Tab('left', 1550),
    new PHPWord_Style_Tab('center', 3200),
    new PHPWord_Style_Tab('right', 5300)
  )
));
$PHPWord->addParagraphStyle('rightTab', array(
  'tabs' => array(
    new PHPWord_Style_Tab('right', 9090)
  )
));
$PHPWord->addParagraphStyle('centerTab', array(
  'tabs' => array(
    new PHPWord_Style_Tab('center', 4680)
  )
));

// New portrait section
$section = $PHPWord->createSection();

// Add listitem elements
$section->addText("Multiple Tabs:\tOne\tTwo\tThree", NULL, 'multipleTab');
$section->addText("Left Aligned\tRight Aligned", NULL, 'rightTab');
$section->addText("\tCenter Aligned",            NULL, 'centerTab');

// Save File
echo date('H:i:s') , ' Write to Word2007 format' , EOL;
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$objWriter->save(str_replace('.php', '.docx', __FILE__));

echo date('H:i:s') , ' Write to OpenDocumentText format' , EOL;
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'ODText');
$objWriter->save(str_replace('.php', '.odt', __FILE__));

echo date('H:i:s') , ' Write to RTF format' , EOL;
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'RTF');
$objWriter->save(str_replace('.php', '.rtf', __FILE__));


// Echo memory peak usage
echo date('H:i:s') , ' Peak memory usage: ' , (memory_get_peak_usage(true) / 1024 / 1024) , ' MB' , EOL;

// Echo done
echo date('H:i:s') , ' Done writing file' , EOL;
?>