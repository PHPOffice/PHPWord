<?php
declare(strict_types=1);
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Image;
use PhpOffice\PhpWord\Style\Lengths\Absolute;

include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new PhpWord();

// Begin code
$section = $phpWord->addSection();
$section->addText('Local image without any styles:');
$section->addImage('resources/_mars.jpg');

printSeparator($section);
$section->addText('Local image with styles:');
$section->addImage('resources/_earth.jpg', array('width' => Absolute::from('pt', 210), 'height' => Absolute::from('pt', 210), 'alignment' => Jc::CENTER));

// Remote image
printSeparator($section);
$source = 'http://php.net/images/logos/php-med-trans-light.gif';
$section->addText("Remote image from: {$source}");
$section->addImage($source);

// Image from string
printSeparator($section);
$source = 'resources/_mars.jpg';
$fileContent = file_get_contents($source);
$section->addText('Image from string');
$section->addImage($fileContent);

//Wrapping style
printSeparator($section);
$text = str_repeat('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. ', 2);
$wrappingStyles = array('inline', 'behind', 'infront', 'square', 'tight');
foreach ($wrappingStyles as $wrappingStyle) {
    $section->addText("Wrapping style {$wrappingStyle}");
    $section->addImage(
        'resources/_earth.jpg',
        array(
            'positioning'        => 'relative',
            'marginTop'          => Absolute::from('cm', -1),
            'marginLeft'         => Absolute::from('cm', 1),
            'width'              => Absolute::from('pt', 80),
            'height'             => Absolute::from('pt', 80),
            'wrappingStyle'      => $wrappingStyle,
            'wrapDistanceRight'  => Absolute::from('cm', 1),
            'wrapDistanceBottom' => Absolute::from('cm', 1),
        )
    );
    $section->addText($text);
    printSeparator($section);
}

//Absolute positioning
$section->addText('Absolute positioning: see top right corner of page');
$section->addImage(
    'resources/_mars.jpg',
    array(
        'width'            => Absolute::from('cm', 3),
        'height'           => Absolute::from('cm', 3),
        'positioning'      => Image::POSITION_ABSOLUTE,
        'posHorizontal'    => Image::POSITION_HORIZONTAL_RIGHT,
        'posHorizontalRel' => Image::POSITION_RELATIVE_TO_PAGE,
        'posVerticalRel'   => Image::POSITION_RELATIVE_TO_PAGE,
        'marginLeft'       => Absolute::from('cm', 15.5),
        'marginTop'        => Absolute::from('cm', 1.55),
    )
);

//Relative positioning
printSeparator($section);
$section->addText('Relative positioning: Horizontal position center relative to column,');
$section->addText('Vertical position top relative to line');
$section->addImage(
    'resources/_mars.jpg',
    array(
        'width'            => Absolute::from('cm', 3),
        'height'           => Absolute::from('cm', 3),
        'positioning'      => Image::POSITION_RELATIVE,
        'posHorizontal'    => Image::POSITION_HORIZONTAL_CENTER,
        'posHorizontalRel' => Image::POSITION_RELATIVE_TO_COLUMN,
        'posVertical'      => Image::POSITION_VERTICAL_TOP,
        'posVerticalRel'   => Image::POSITION_RELATIVE_TO_LINE,
    )
);

function printSeparator(Section $section)
{
    $section->addTextBreak();
    $lineStyle = array('weight' => Absolute::from('twip', 0.2), 'width' => Absolute::from('twip', 150), 'height' => Absolute::from('twip', 0), 'align' => 'center');
    $section->addLine($lineStyle);
    $section->addTextBreak(2);
}

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
