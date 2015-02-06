<?php
include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$filler = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. '
        . 'Nulla fermentum, tortor id adipiscing adipiscing, tortor turpis commodo. '
        . 'Donec vulputate iaculis metus, vel luctus dolor hendrerit ac. '
        . 'Suspendisse congue congue leo sed pellentesque.';

// Normal
$section = $phpWord->addSection();
$section->addText(htmlspecialchars("Normal paragraph. {$filler}", ENT_COMPAT, 'UTF-8'));

// Two columns
$section = $phpWord->addSection(
    array(
        'colsNum'   => 2,
        'colsSpace' => 1440,
        'breakType' => 'continuous',
    )
);
$section->addText(htmlspecialchars("Two columns, one inch (1440 twips) spacing. {$filler}", ENT_COMPAT, 'UTF-8'));

// Normal
$section = $phpWord->addSection(array('breakType' => 'continuous'));
$section->addText(htmlspecialchars("Normal paragraph again. {$filler}", ENT_COMPAT, 'UTF-8'));

// Three columns
$section = $phpWord->addSection(
    array(
        'colsNum'   => 3,
        'colsSpace' => 720,
        'breakType' => 'continuous',
    )
);
$section->addText(htmlspecialchars("Three columns, half inch (720 twips) spacing. {$filler}", ENT_COMPAT, 'UTF-8'));

// Normal
$section = $phpWord->addSection(array('breakType' => 'continuous'));
$section->addText(htmlspecialchars('Normal paragraph again.', ENT_COMPAT, 'UTF-8'));

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
