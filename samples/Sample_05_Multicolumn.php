<?php
include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s') , " Create new PhpWord object" , \EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$filler = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. ' .
    'Nulla fermentum, tortor id adipiscing adipiscing, tortor turpis commodo. ' .
    'Donec vulputate iaculis metus, vel luctus dolor hendrerit ac. ' .
    'Suspendisse congue congue leo sed pellentesque.';

// Normal
$section = $phpWord->createSection();
$section->addText('Normal paragraph. ' . $filler);

// Two columns
$section = $phpWord->createSection(array(
    'colsNum' => 2,
    'colsSpace' => 1440,
    'breakType' => 'continuous'));
$section->addText('Three columns, one inch (1440 twips) spacing. ' . $filler);

// Normal
$section = $phpWord->createSection(array('breakType' => 'continuous'));
$section->addText('Normal paragraph again. ' . $filler);

// Three columns
$section = $phpWord->createSection(array(
    'colsNum' => 3,
    'colsSpace' => 720,
    'breakType' => 'continuous'));
$section->addText('Three columns, half inch (720 twips) spacing. ' . $filler);

// Normal
$section = $phpWord->createSection(array('breakType' => 'continuous'));
$section->addText('Normal paragraph again.');

// Save file
$name = basename(__FILE__, '.php');
$writers = array('Word2007' => 'docx', 'ODText' => 'odt', 'RTF' => 'rtf');
foreach ($writers as $writer => $extension) {
    echo date('H:i:s'), " Write to {$writer} format", \EOL;
    $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, $writer);
    $xmlWriter->save("{$name}.{$extension}");
    rename("{$name}.{$extension}", "results/{$name}.{$extension}");
}

include_once 'Sample_Footer.php';
