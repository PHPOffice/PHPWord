<?php
declare(strict_types=1);
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Colors\HighlightColor;
use PhpOffice\PhpWord\Style\Lengths\Absolute;

include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new PhpWord();

// New section
$section = $phpWord->addSection();

// Add Line elements
// See Element/Line.php for all options
$section->addText('Horizontal Line (Inline style):');
$section->addLine(
    array(
        'width'       => Absolute::from('cm', 4),
        'height'      => Absolute::from('cm', 0),
        'positioning' => 'absolute',
    )
);
$section->addText('Vertical Line (Inline style):');
$section->addLine(
    array(
        'width'       => Absolute::from('cm', 0),
        'height'      => Absolute::from('cm', 1),
        'positioning' => 'absolute',
    )
);
// Two text break
$section->addTextBreak(1);

$section->addText('Positioned Line (red):');
$section->addLine(
    array(
        'width'            => Absolute::from('cm', 4),
        'height'           => Absolute::from('cm', 1),
        'positioning'      => 'absolute',
        'posHorizontalRel' => 'page',
        'posVerticalRel'   => 'page',
        'marginLeft'       => Absolute::from('cm', 10),
        'marginTop'        => Absolute::from('cm', 8),
        'wrappingStyle'    => \PhpOffice\PhpWord\Style\Image::WRAPPING_STYLE_SQUARE,
        'color'            => new HighlightColor('red'),
    )
);

$section->addText('Horizontal Formatted Line');
$section->addLine(
    array(
        'width'       => Absolute::from('cm', 15),
        'height'      => Absolute::from('cm', 0),
        'positioning' => 'absolute',
        'beginArrow'  => \PhpOffice\PhpWord\Style\Line::ARROW_STYLE_BLOCK,
        'endArrow'    => \PhpOffice\PhpWord\Style\Line::ARROW_STYLE_OVAL,
        'dash'        => \PhpOffice\PhpWord\Style\Line::DASH_STYLE_LONG_DASH_DOT_DOT,
        'weight'      => 10,
    )
);

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
