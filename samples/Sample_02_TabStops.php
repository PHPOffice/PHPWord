<?php
include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s') , ' Create new PhpWord object' , \EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// Ads styles
$phpWord->addParagraphStyle('multipleTab', array(
  'tabs' => array(
    new \PhpOffice\PhpWord\Style\Tab('left', 1550),
    new \PhpOffice\PhpWord\Style\Tab('center', 3200),
    new \PhpOffice\PhpWord\Style\Tab('right', 5300)
  )
));
$phpWord->addParagraphStyle('rightTab', array(
  'tabs' => array(
    new \PhpOffice\PhpWord\Style\Tab('right', 9090)
  )
));
$phpWord->addParagraphStyle('centerTab', array(
  'tabs' => array(
    new \PhpOffice\PhpWord\Style\Tab('center', 4680)
  )
));

// New portrait section
$section = $phpWord->createSection();

// Add listitem elements
$section->addText("Multiple Tabs:\tOne\tTwo\tThree", NULL, 'multipleTab');
$section->addText("Left Aligned\tRight Aligned", NULL, 'rightTab');
$section->addText("\tCenter Aligned",            NULL, 'centerTab');

// Save file
$name = basename(__FILE__, '.php');
$writers = array('Word2007' => 'docx', 'ODText' => 'odt', 'RTF' => 'rtf');
foreach ($writers as $writer => $extension) {
    echo date('H:i:s'), " Write to {$writer} format", \EOL;
    $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, $writer);
    $xmlWriter->save("{$name}.{$extension}");
    rename("{$name}.{$extension}", "results/{$name}.{$extension}");
}

include_once 'Sample_Footer.php';
