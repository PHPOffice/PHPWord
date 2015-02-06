<?php
include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();
\PhpOffice\PhpWord\Settings::setCompatibility(false);

// New portrait section
$section = $phpWord->addSection();

// Add style definitions
$phpWord->addParagraphStyle('pStyle', array('spacing' => 100));
$phpWord->addFontStyle('BoldText', array('bold' => true));
$phpWord->addFontStyle('ColoredText', array('color' => 'FF8080'));
$phpWord->addLinkStyle(
    'NLink',
    array('color' => '0000FF', 'underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE)
);

// Add text elements
$textrun = $section->addTextRun('pStyle');
$textrun->addText(htmlspecialchars('This is some lead text in a paragraph with a following footnote. ', ENT_COMPAT, 'UTF-8'), 'pStyle');

$footnote = $textrun->addFootnote();
$footnote->addText(htmlspecialchars('Just like a textrun, a footnote can contain native texts. ', ENT_COMPAT, 'UTF-8'));
$footnote->addText(htmlspecialchars('No break is placed after adding an element. ', ENT_COMPAT, 'UTF-8'), 'BoldText');
$footnote->addText(htmlspecialchars('All elements are placed inside a paragraph. ', ENT_COMPAT, 'UTF-8'), 'ColoredText');
$footnote->addTextBreak();
$footnote->addText(htmlspecialchars('But you can insert a manual text break like above, ', ENT_COMPAT, 'UTF-8'));
$footnote->addText(htmlspecialchars('links like ', ENT_COMPAT, 'UTF-8'));
$footnote->addLink('https://github.com/PHPOffice/PHPWord', htmlspecialchars('PHPWord on GitHub', ENT_COMPAT, 'UTF-8'), 'NLink');
$footnote->addText(htmlspecialchars(', image like ', ENT_COMPAT, 'UTF-8'));
$footnote->addImage('resources/_earth.jpg', array('width' => 18, 'height' => 18));
$footnote->addText(htmlspecialchars(', or object like ', ENT_COMPAT, 'UTF-8'));
$footnote->addObject('resources/_sheet.xls');
$footnote->addText(htmlspecialchars('But you can only put footnote in section, not in header or footer.', ENT_COMPAT, 'UTF-8'));

$section->addText(
    htmlspecialchars(
        'You can also create the footnote directly from the section making it wrap in a paragraph '
            . 'like the footnote below this paragraph. But is is best used from within a textrun.',
        ENT_COMPAT,
        'UTF-8'
    )
);
$footnote = $section->addFootnote();
$footnote->addText(htmlspecialchars('The reference for this is wrapped in its own line', ENT_COMPAT, 'UTF-8'));

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
