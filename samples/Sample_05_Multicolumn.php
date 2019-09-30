<?php
declare(strict_types=1);
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Lengths\Absolute;

include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new PhpWord();
$filler = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. '
        . 'Nulla fermentum, tortor id adipiscing adipiscing, tortor turpis commodo. '
        . 'Donec vulputate iaculis metus, vel luctus dolor hendrerit ac. '
        . 'Suspendisse congue congue leo sed pellentesque.';

// Normal
$section = $phpWord->addSection();
$section->addText("Normal paragraph. {$filler}");

// Two columns
$section = $phpWord->addSection(
    array(
        'colsNum'   => 2,
        'colsSpace' => Absolute::from('twip', 1440),
        'breakType' => 'continuous',
    )
);
$section->addText("Two columns, one inch (1440 twips) spacing. {$filler}");

// Normal
$section = $phpWord->addSection(array('breakType' => 'continuous'));
$section->addText("Normal paragraph again. {$filler}");

// Three columns
$section = $phpWord->addSection(
    array(
        'colsNum'   => 3,
        'colsSpace' => Absolute::from('twip', 720),
        'breakType' => 'continuous',
    )
);
$section->addText("Three columns, half inch (720 twips) spacing. {$filler}");

// Normal
$section = $phpWord->addSection(array('breakType' => 'continuous'));
$section->addText("Normal paragraph again. {$filler}");

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
