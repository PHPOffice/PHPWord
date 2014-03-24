<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s') , " Create new PhpWord object" , \EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$phpWord->setDefaultParagraphStyle(array(
    'align' => 'both',
    'spaceAfter' => \PhpOffice\PhpWord\Shared\Font::pointSizeToTwips(12),
    'spacing' => 120,
));

// Sample
$section = $phpWord->createSection();

$section->addText('Below are the samples on how to control your paragraph ' .
    'pagination. See "Line and Page Break" tab on paragraph properties ' .
    'window to see the attribute set by these controls.',
    array('bold' => true), null);

$section->addText('Paragraph with widowControl = false (default: true). ' .
    'A "widow" is the last line of a paragraph printed by itself at the top ' .
    'of a page. An "orphan" is the first line of a paragraph printed by ' .
    'itself at the bottom of a page. Set this option to "false" if you want ' .
    'to disable this automatic control.',
    null, array('widowControl' => false));

$section->addText('Paragraph with keepNext = true (default: false). ' .
    '"Keep with next" is used to prevent Word from inserting automatic page ' .
    'breaks between paragraphs. Set this option to "true" if you do not want ' .
    'your paragraph to be separated with the next paragraph.',
    null, array('keepNext' => true));

$section->addText('Paragraph with keepLines = true (default: false). ' .
    '"Keep lines together" will prevent Word from inserting an automatic page ' .
    'break within a paragraph. Set this option to "true" if you do not want ' .
    'all lines of your paragraph to be in the same page.',
    null, array('keepLines' => true));

$section->addText('Keep scrolling. More below.');

$section->addText('Paragraph with pageBreakBefore = true (default: false). ' .
    'Different with all other control above, "page break before" separates ' .
    'your paragraph into the next page. This option is most useful for ' .
    'heading styles.',
    null, array('pageBreakBefore' => true));

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
