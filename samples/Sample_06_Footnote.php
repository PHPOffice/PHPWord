<?php
include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();
\PhpOffice\PhpWord\Settings::setCompatibility(false);

// Define styles
$paragraphStyleName = 'pStyle';
$phpWord->addParagraphStyle($paragraphStyleName, array('spacing' => 100));

$boldFontStyleName = 'BoldText';
$phpWord->addFontStyle($boldFontStyleName, array('bold' => true));

$coloredFontStyleName = 'ColoredText';
$phpWord->addFontStyle($coloredFontStyleName, array('color' => 'FF8080', 'bgColor' => 'FFFFCC'));

$linkFontStyleName = 'NLink';
$phpWord->addLinkStyle($linkFontStyleName, array('color' => '0000FF', 'underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE));

// New portrait section
$section = $phpWord->addSection();

// Add text elements
$textrun = $section->addTextRun($paragraphStyleName);
$textrun->addText('This is some lead text in a paragraph with a following footnote. ', $paragraphStyleName);

$footnote = $textrun->addFootnote();
$footnote->addText('Just like a textrun, a footnote can contain native texts. ');
$footnote->addText('No break is placed after adding an element. ', $boldFontStyleName);
$footnote->addText('All elements are placed inside a paragraph. ', $coloredFontStyleName);
$footnote->addTextBreak();
$footnote->addText('But you can insert a manual text break like above, ');
$footnote->addText('links like ');
$footnote->addLink('https://github.com/PHPOffice/PHPWord', 'PHPWord on GitHub', $linkFontStyleName);
$footnote->addText(', image like ');
$footnote->addImage('resources/_earth.jpg', array('width' => 18, 'height' => 18));
$footnote->addText(', or object like ');
$footnote->addObject('resources/_sheet.xls');
$footnote->addText('But you can only put footnote in section, not in header or footer.');

$section->addText(
    'You can also create the footnote directly from the section making it wrap in a paragraph '
        . 'like the footnote below this paragraph. But is is best used from within a textrun.'
);
$footnote = $section->addFootnote();
$footnote->addText('The reference for this is wrapped in its own line');

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
