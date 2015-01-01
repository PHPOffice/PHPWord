<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// Begin code
$section = $phpWord->addSection();

// Add Line elements
// See Element/Line.php for all options
$section->addText(htmlspecialchars('Horizontal Line (Inline style):'));
$section->addLine(
    array(
        'width'       => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(4),
        'height'      => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(0),
        'positioning' => 'absolute',
    )
);
$section->addText(htmlspecialchars('Vertical Line (Inline style):'));
$section->addLine(
    array(
        'width'       => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(0),
        'height'      => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(1),
        'positioning' => 'absolute',
    )
);
// Two text break
$section->addTextBreak(1);

$section->addText(htmlspecialchars('Positioned Line (red):'));
$section->addLine(
    array(
        'width'            => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(4),
        'height'           => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(1),
        'positioning'      => 'absolute',
        'posHorizontalRel' => 'page',
        'posVerticalRel'   => 'page',
        'marginLeft'       => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(10),
        'marginTop'        => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(8),
        'wrappingStyle'    => \PhpOffice\PhpWord\Style\Image::WRAPPING_STYLE_SQUARE,
        'color'            => 'red',
    )
);

$section->addText(htmlspecialchars('Horizontal Formatted Line'));
$section->addLine(
    array(
        'width'       => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(15),
        'height'      => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(0),
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
