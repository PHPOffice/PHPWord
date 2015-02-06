<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// Begin code
$fontStyle = array('size' => 24);
$paragraphStyle = array('spacing' => 240, 'size' => 24);
$phpWord->addFontStyle('fontStyle', array('size' => 9));
$phpWord->addParagraphStyle('paragraphStyle', array('spacing' => 480));
$fontStyle = array('size' => 24);

$section = $phpWord->addSection();
$section->addText(htmlspecialchars('Text break with no style:', ENT_COMPAT, 'UTF-8'));
$section->addTextBreak();
$section->addText(htmlspecialchars('Text break with defined font style:', ENT_COMPAT, 'UTF-8'));
$section->addTextBreak(1, 'fontStyle');
$section->addText(htmlspecialchars('Text break with defined paragraph style:', ENT_COMPAT, 'UTF-8'));
$section->addTextBreak(1, null, 'paragraphStyle');
$section->addText(htmlspecialchars('Text break with inline font style:', ENT_COMPAT, 'UTF-8'));
$section->addTextBreak(1, $fontStyle);
$section->addText(htmlspecialchars('Text break with inline paragraph style:', ENT_COMPAT, 'UTF-8'));
$section->addTextBreak(1, null, $paragraphStyle);
$section->addText(htmlspecialchars('Done.', ENT_COMPAT, 'UTF-8'));

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
