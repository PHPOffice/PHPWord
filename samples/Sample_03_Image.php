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

// Create a new Section
$section = $PHPWord->createSection();

// Behind Test
$section->addText('Hello World! Hello World!  Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World!  Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World!');
$section->addImage(
  'assets/img/flowers.jpg',
  array(
      'width' => 400,
      'height' => 400,
      'marginTop' => -1,
      'marginLeft' => 1,
      'wrappingStyle' => PHPWord_Style_Image::WRAPPING_STYLE_BEHIND 
  )
);

// Square Test
$section = $PHPWord->createSection();
$section->addText('Hello World! Hello World!  Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World!  Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World!');
$section->addImage(
  'assets/img/flowers.jpg',
  array(
      'width' => 400,
      'height' => 400,
      'marginTop' => -1,
      'marginLeft' => 1,
      'wrappingStyle' => PHPWord_Style_Image::WRAPPING_STYLE_SQUARE 
  )
);

// tight Test
$section = $PHPWord->createSection();
$section->addText('Hello World! Hello World!  Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World!  Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World!');
$section->addImage(
  'assets/img/flowers.jpg',
  array(
      'width' => 400,
      'height' => 400,
      'marginTop' => -1,
      'marginLeft' => 1,
      'wrappingStyle' => PHPWord_Style_Image::WRAPPING_STYLE_TIGHT 
  )
);

// infront Test
$section = $PHPWord->createSection();
$section->addText('Hello World! Hello World!  Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World!  Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World!');
$section->addImage(
  'assets/img/flowers.jpg',
  array(
      'width' => 400,
      'height' => 400,
      'marginTop' => -1,
      'marginLeft' => 1,
      'wrappingStyle' => PHPWord_Style_Image::WRAPPING_STYLE_INFRONT 
  )
);

// inline Test
$section = $PHPWord->createSection();
$section->addText('Hello World! Hello World!  Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World!  Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World! Hello World!');
$section->addImage(
  'assets/img/flowers.jpg',
  array(
      'width' => 400,
      'height' => 400,
      'align' => "center",
      'wrappingStyle' => PHPWord_Style_Image::WRAPPING_STYLE_INLINE 
  )
);

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