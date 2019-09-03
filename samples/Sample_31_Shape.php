<?php
declare(strict_types=1);
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Colors\Hex;
use PhpOffice\PhpWord\Style\Lengths\Absolute;

include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new PhpWord();

// New section
$section = $phpWord->addSection();

// Define styles
$phpWord->addTitleStyle(1, array('size' => Absolute::from('pt', 14), 'bold' => true));

// Arc
$section->addTitle('Arc', 1);
$section->addShape(
    'arc',
    array(
        'points'  => '-90 20',
        'frame'   => array('width' => Absolute::from('pt', 120), 'height' => Absolute::from('pt', 120)),
        'outline' => array('color' => new Hex('333333'), 'weight' => 2, 'startArrow' => 'oval', 'endArrow' => 'open'),
    )
);

// Curve
$section->addTitle('Curve', 1);
$section->addShape(
    'curve',
    array(
        'points'    => '1,100 200,1 1,50 200,50',
        'connector' => 'elbow',
        'outline'   => array(
            'color'      => new Hex('66cc00'),
            'weight'     => 2,
            'dash'       => 'dash',
            'startArrow' => 'diamond',
            'endArrow'   => 'block',
        ),
    )
);

// Line
$section->addTitle('Line', 1);
$section->addShape(
    'line',
    array(
        'points'  => '1,1 150,30',
        'outline' => array(
            'color'      => new Hex('cc00ff'),
            'line'       => 'thickThin',
            'weight'     => 3,
            'startArrow' => 'oval',
            'endArrow'   => 'classic',
        ),
    )
);

// Polyline
$section->addTitle('Polyline', 1);
$section->addShape(
    'polyline',
    array(
        'points'  => '1,30 20,10 55,20 75,10 100,40 115,50, 120,15 200,50',
        'outline' => array('color' => new Hex('cc6666'), 'weight' => 2, 'startArrow' => 'none', 'endArrow' => 'classic'),
    )
);

// Rectangle
$section->addTitle('Rectangle', 1);
$section->addShape(
    'rect',
    array(
        'roundness' => 0.2,
        'frame'     => array('width' => Absolute::from('pt', 100), 'height' => Absolute::from('pt', 100), 'left' => Absolute::from('pt', 1), 'top' => Absolute::from('pt', 1)),
        'fill'      => array('color' => new Hex('FFCC33')),
        'outline'   => array('color' => new Hex('990000'), 'weight' => 1),
        'shadow'    => array(),
    )
);

// Oval
$section->addTitle('Oval', 1);
$section->addShape(
    'oval',
    array(
        'frame'     => array('width' => Absolute::from('pt', 100), 'height' => Absolute::from('pt', 70), 'left' => Absolute::from('pt', 1), 'top' => Absolute::from('pt', 1)),
        'fill'      => array('color' => new Hex('33CC99')),
        'outline'   => array('color' => new Hex('333333'), 'weight' => 2),
        'extrusion' => array(),
    )
);

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
