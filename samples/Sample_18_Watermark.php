<?php
declare(strict_types=1);
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Lengths\Absolute;

include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new PhpWord();

// Begin code
$section = $phpWord->addSection();
$header = $section->addHeader();
$header->addWatermark('resources/_earth.jpg', array('marginTop' => Absolute::from('twip', 200), 'marginLeft' => Absolute::from('twip', 55)));
$section->addText('The header reference to the current section includes a watermark image.');

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
