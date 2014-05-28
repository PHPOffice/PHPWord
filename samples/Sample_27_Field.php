<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), " Create new PhpWord object", EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// Begin code
$section = $phpWord->addSection();

// Add Field elements
// See Element/Field.php for all options
$section->addField('DATE', array('dateformat'=>'d-M-yyyy H:mm:ss'), array('PreserveFormat', 'LunarCalendar'));
$section->addField('PAGE', array('format'=>'ArabicDash'));
$section->addField('NUMPAGES', array('format'=>'Arabic', 'numformat'=>'0,00'), array('PreserveFormat'));

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
