<?php
include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// Ads styles
$phpWord->addParagraphStyle(
    'multipleTab',
    array(
        'tabs' => array(
            new \PhpOffice\PhpWord\Style\Tab('left', 1550),
            new \PhpOffice\PhpWord\Style\Tab('center', 3200),
            new \PhpOffice\PhpWord\Style\Tab('right', 5300),
        )
    )
);
$phpWord->addParagraphStyle(
    'rightTab',
    array('tabs' => array(new \PhpOffice\PhpWord\Style\Tab('right', 9090)))
);
$phpWord->addParagraphStyle(
    'centerTab',
    array('tabs' => array(new \PhpOffice\PhpWord\Style\Tab('center', 4680)))
);

// New portrait section
$section = $phpWord->addSection();

// Add listitem elements
$section->addText(htmlspecialchars("Multiple Tabs:\tOne\tTwo\tThree"), null, 'multipleTab');
$section->addText(htmlspecialchars("Left Aligned\tRight Aligned"), null, 'rightTab');
$section->addText(htmlspecialchars("\tCenter Aligned"), null, 'centerTab');

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
