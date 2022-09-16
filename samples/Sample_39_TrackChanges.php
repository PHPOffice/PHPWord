<?php

use PhpOffice\PhpWord\Element\TrackChange;

include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s') , ' Create new PhpWord object' , EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// New portrait section
$section = $phpWord->addSection();
$textRun = $section->addTextRun();

$text = $textRun->addText('Hello World! Time to ');

$text = $textRun->addText('wake ', ['bold' => true]);
$text->setChangeInfo(TrackChange::INSERTED, 'Fred', time() - 1800);

$text = $textRun->addText('up');
$text->setTrackChange(new TrackChange(TrackChange::INSERTED, 'Fred'));

$text = $textRun->addText('go to sleep');
$text->setChangeInfo(TrackChange::DELETED, 'Barney', new \DateTime('@' . (time() - 3600)));

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
