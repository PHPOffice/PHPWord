<?php
// Init
error_reporting(E_ALL);
define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
require_once '../Classes/PHPWord.php';

// New Word Document
echo date('H:i:s') , ' Create new PHPWord object' , EOL;
$PHPWord = new PHPWord();

// New portrait section
$section = $PHPWord->createSection(array('borderColor' => '00FF00', 'borderSize' => 12));
$section->addText('I am placed on a default section.');

// New landscape section
$section = $PHPWord->createSection(array('orientation' => 'landscape'));
$section->addText('I am placed on a landscape section. Every page starting from this section will be landscape style.');
$section->addPageBreak();
$section->addPageBreak();

// New portrait section
$section = $PHPWord->createSection(array('marginLeft' => 600, 'marginRight' => 600, 'marginTop' => 600, 'marginBottom' => 600));
$section->addText('This section uses other margins.');

// New portrait section with Header & Footer
$section = $PHPWord->createSection(array('marginLeft' => 200, 'marginRight' => 200, 'marginTop' => 200, 'marginBottom' => 200, 'headerHeight' => 50, 'footerHeight' => 50,));
$section->addText('This section and we play with header/footer height.');
$section->createHeader()->addText('Header');
$section->createFooter()->addText('Footer');

// Save file
$name = basename(__FILE__, '.php');
$writers = array('Word2007' => 'docx', 'ODText' => 'odt', 'RTF' => 'rtf');
foreach ($writers as $writer => $extension) {
    echo date('H:i:s'), " Write to {$writer} format", EOL;
    $objWriter = PHPWord_IOFactory::createWriter($PHPWord, $writer);
    $objWriter->save("{$name}.{$extension}");
    rename("{$name}.{$extension}", "results/{$name}.{$extension}");
}

// Done
echo date('H:i:s'), " Done writing file(s)", EOL;
echo date('H:i:s'), " Peak memory usage: ", (memory_get_peak_usage(true) / 1024 / 1024), " MB", EOL;
