<?php
declare(strict_types=1);
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Lengths\Absolute;
use PhpOffice\PhpWord\Style\Tab;

include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new PhpWord();

// Define styles
$multipleTabsStyleName = 'multipleTab';
$phpWord->addParagraphStyle(
    $multipleTabsStyleName,
    array(
        'tabs' => array(
            new Tab('left', Absolute::from('twip', 1550)),
            new Tab('center', Absolute::from('twip', 3200)),
            new Tab('right', Absolute::from('twip', 5300)),
        ),
    )
);

$rightTabStyleName = 'rightTab';
$phpWord->addParagraphStyle($rightTabStyleName, array('tabs' => array(new Tab('right', Absolute::from('twip', 9090)))));

$leftTabStyleName = 'centerTab';
$phpWord->addParagraphStyle($leftTabStyleName, array('tabs' => array(new Tab('center', Absolute::from('twip', 4680)))));

// New portrait section
$section = $phpWord->addSection();

// Add listitem elements
$section->addText("Multiple Tabs:\tOne\tTwo\tThree", null, $multipleTabsStyleName);
$section->addText("Left Aligned\tRight Aligned", null, $rightTabStyleName);
$section->addText("\tCenter Aligned", null, $leftTabStyleName);

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
