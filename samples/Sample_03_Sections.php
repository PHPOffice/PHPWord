<?php
include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s') , ' Create new PhpWord object' , \EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// New portrait section
$section = $phpWord->createSection(array('borderColor' => '00FF00', 'borderSize' => 12));
$section->addText('I am placed on a default section.');

// New landscape section
$section = $phpWord->createSection(array('orientation' => 'landscape'));
$section->addText('I am placed on a landscape section. Every page starting from this section will be landscape style.');
$section->addPageBreak();
$section->addPageBreak();

// New portrait section
$section = $phpWord->createSection(array('marginLeft' => 600, 'marginRight' => 600, 'marginTop' => 600, 'marginBottom' => 600));
$section->addText('This section uses other margins.');

// New portrait section with Header & Footer
$section = $phpWord->createSection(array('marginLeft' => 200, 'marginRight' => 200, 'marginTop' => 200, 'marginBottom' => 200, 'headerHeight' => 50, 'footerHeight' => 50,));
$section->addText('This section and we play with header/footer height.');
$section->createHeader()->addText('Header');
$section->createFooter()->addText('Footer');

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
