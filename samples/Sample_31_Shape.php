<?php

include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// New section
$section = $phpWord->addSection();

// Define styles
$phpWord->addTitleStyle(1, ['size' => 14, 'bold' => true]);

// Arc
$section->addTitle('Arc', 1);
$section->addShape(
    'arc',
    [
        'points' => '-90 20',
        'frame' => ['width' => 120, 'height' => 120],
        'outline' => ['color' => '#333333', 'weight' => 2, 'startArrow' => 'oval', 'endArrow' => 'open'],
    ]
);

// Curve
$section->addTitle('Curve', 1);
$section->addShape(
    'curve',
    [
        'points' => '1,100 200,1 1,50 200,50',
        'connector' => 'elbow',
        'outline' => [
            'color' => '#66cc00',
            'weight' => 2,
            'dash' => 'dash',
            'startArrow' => 'diamond',
            'endArrow' => 'block',
        ],
    ]
);

// Line
$section->addTitle('Line', 1);
$section->addShape(
    'line',
    [
        'points' => '1,1 150,30',
        'outline' => [
            'color' => '#cc00ff',
            'line' => 'thickThin',
            'weight' => 3,
            'startArrow' => 'oval',
            'endArrow' => 'classic',
        ],
    ]
);

// Polyline
$section->addTitle('Polyline', 1);
$section->addShape(
    'polyline',
    [
        'points' => '1,30 20,10 55,20 75,10 100,40 115,50, 120,15 200,50',
        'outline' => ['color' => '#cc6666', 'weight' => 2, 'startArrow' => 'none', 'endArrow' => 'classic'],
    ]
);

// Rectangle
$section->addTitle('Rectangle', 1);
$section->addShape(
    'rect',
    [
        'roundness' => 0.2,
        'frame' => ['width' => 100, 'height' => 100, 'left' => 1, 'top' => 1],
        'fill' => ['color' => '#FFCC33'],
        'outline' => ['color' => '#990000', 'weight' => 1],
        'shadow' => [],
    ]
);

// Oval
$section->addTitle('Oval', 1);
$section->addShape(
    'oval',
    [
        'frame' => ['width' => 100, 'height' => 70, 'left' => 1, 'top' => 1],
        'fill' => ['color' => '#33CC99'],
        'outline' => ['color' => '#333333', 'weight' => 2],
        'extrusion' => [],
    ]
);

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
