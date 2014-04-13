<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), " Create new PhpWord object", EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// Begin code
$section = $phpWord->addSection();
$section->addText('Local image without any styles:');
$section->addImage('resources/_mars.jpg');
$section->addTextBreak(2);

$section->addText('Local image with styles:');
$section->addImage('resources/_earth.jpg', array('width' => 210, 'height' => 210, 'align' => 'center'));
$section->addTextBreak(2);

// Remote image
$source = 'http://php.net/images/logos/php-med-trans-light.gif';
$section->addText("Remote image from: {$source}");
$section->addImage($source);

//Wrapping style
$text = str_repeat('Hello World! ', 15);
$wrappingStyles = array('inline', 'behind', 'infront', 'square', 'tight');
foreach ($wrappingStyles as $wrappingStyle) {
    $section->addTextBreak(5);
    $section->addText('Wrapping style ' . $wrappingStyle);
    $section->addImage('resources/_earth.jpg', array('marginTop' => -1, 'marginLeft' => 1,
        'width' => 80, 'height' => 80, 'wrappingStyle' => $wrappingStyle));
    $section->addText($text);
}

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
