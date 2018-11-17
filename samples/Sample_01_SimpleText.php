<?php
use PhpOffice\PhpWord\Style\Font;

include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s') , ' Create new PhpWord object' , EOL;

$languageEnGb = new \PhpOffice\PhpWord\Style\Language(\PhpOffice\PhpWord\Style\Language::EN_GB);

$phpWord = new \PhpOffice\PhpWord\PhpWord();

$section = $phpWord->addSection();
$section->addText(
    '"Learn from yesterday, live for today, hope for tomorrow. '
    . 'The important thing is not to stop questioning." '
    . '(Albert Einstein)',
    [],
    [
        'spacing' => \PhpOffice\Common\Font::pointSizeToTwips(1),
        'spacingLineRule' => \PhpOffice\PhpWord\SimpleType\LineSpacingRule::EXACT
    ]
    );

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
