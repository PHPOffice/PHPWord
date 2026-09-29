<?php

use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\PhpWord;

include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new PhpWord();

$section = $phpWord->addSection();
$section->addText('SVG image without any styles:');
$svg = $section->addImage(__DIR__ . '/resources/sample.svg');

printSeparator($section);

$section->addText('SVG image with styles:');
$svg = $section->addImage(
    __DIR__ . '/resources/sample.svg',
    [
        'width' => 200,
        'height' => 200,
        'align' => 'center',
        'wrappingStyle' => PhpOffice\PhpWord\Style\Image::WRAPPING_STYLE_BEHIND,
    ]
);

function printSeparator(Section $section): void
{
    $section->addTextBreak();
    $lineStyle = ['weight' => 0.2, 'width' => 150, 'height' => 0, 'align' => 'center'];
    $section->addLine($lineStyle);
    $section->addTextBreak(2);
}

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
