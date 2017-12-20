<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// Begin code
$section = $phpWord->addSection();
$section->addText('Local image without any styles:');
$section->addImage('resources/_mars.jpg');
$section->addTextBreak(2);

$section->addText('Local image with styles:');
$section->addImage('resources/_earth.jpg', array('width' => 210, 'height' => 210, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
$section->addTextBreak(2);

// Remote image
$source = 'http://php.net/images/logos/php-med-trans-light.gif';
$section->addText("Remote image from: {$source}");
$section->addImage($source);

// Image from string
$source = 'resources/_mars.jpg';
$fileContent = file_get_contents($source);
$section->addText('Image from string');
$section->addImage($fileContent);

//Wrapping style
$text = str_repeat('Hello World! ', 15);
$wrappingStyles = array('inline', 'behind', 'infront', 'square', 'tight');
foreach ($wrappingStyles as $wrappingStyle) {
    $section->addTextBreak(5);
    $section->addText("Wrapping style {$wrappingStyle}");
    $section->addImage(
        'resources/_earth.jpg',
        array(
            'positioning'   => 'relative',
            'marginTop'     => -1,
            'marginLeft'    => 1,
            'width'         => 80,
            'height'        => 80,
            'wrappingStyle' => $wrappingStyle,
        )
    );
    $section->addText($text);
}

//Absolute positioning
$section->addTextBreak(3);
$section->addText('Absolute positioning: see top right corner of page');
$section->addImage(
    'resources/_mars.jpg',
    array(
        'width'            => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(3),
        'height'           => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(3),
        'positioning'      => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
        'posHorizontal'    => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_RIGHT,
        'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
        'posVerticalRel'   => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
        'marginLeft'       => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(15.5),
        'marginTop'        => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(1.55),
    )
);

//Relative positioning
$section->addTextBreak(3);
$section->addText('Relative positioning: Horizontal position center relative to column,');
$section->addText('Vertical position top relative to line');
$section->addImage(
    'resources/_mars.jpg',
    array(
        'width'            => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(3),
        'height'           => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(3),
        'positioning'      => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE,
        'posHorizontal'    => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_CENTER,
        'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_COLUMN,
        'posVertical'      => \PhpOffice\PhpWord\Style\Image::POSITION_VERTICAL_TOP,
        'posVerticalRel'   => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_LINE,
    )
);

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
