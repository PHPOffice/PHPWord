<?php
use PhpOffice\PhpWord\ComplexType\FootnoteProperties;
use PhpOffice\PhpWord\SimpleType\NumberFormat;

include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();
\PhpOffice\PhpWord\Settings::setCompatibility(false);

$defaultFp = new FootnoteProperties();
$defaultFp->setPos(FootnoteProperties::POSITION_BENEATH_TEXT);
$defaultFp->setNumFmt(NumberFormat::DECIMAL);
$defaultFp->setNumStart(3);
$defaultFp->setNumRestart(FootnoteProperties::RESTART_NUMBER_CONTINUOUS);

$phpWord->setDefaultFootnoteProperties($defaultFp);

$defaultEp = new FootnoteProperties();
$defaultEp->setPos(FootnoteProperties::POSITION_SECTION_END);
$defaultEp->setNumFmt(NumberFormat::DECIMAL_ENCLOSED_CIRCLE);
$defaultEp->setNumStart(8);
$defaultEp->setNumRestart(FootnoteProperties::RESTART_NUMBER_CONTINUOUS);

$phpWord->setDefaultEndnoteProperties($defaultEp);

// First portrait section
$section = $phpWord->addSection();

$textrun = $section->addTextrun();
$textrun->addText('This is some lead text in a paragraph with a following endnote and footnote. ');

// First endnote, follows default properties.
$endnote = $textrun->addEndnote();
$endnote->addText('First endnote, should be on the second page, prefixed by a "8" enclosed in a circle.');

// First footnote, follows default properties.
$footnote = $textrun->addFootnote();
$footnote->addText('First footnote, should be on the first page beneath the text, prefixed by a "3".');

// Create a blank page before the next section
$section->addPageBreak();
$section->addPageBreak();

// Second portrait section
$section = $phpWord->addSection();

// Custom endnote properties for this section
$ep = new FootnoteProperties();
$ep->setPos(FootnoteProperties::POSITION_DOC_END);
$ep->setNumFmt(NumberFormat::DECIMAL_ZERO);
$ep->setNumStart(2);
$ep->setNumRestart(FootnoteProperties::RESTART_NUMBER_EACH_SECTION);

// Custom footnote properties for this section
$fp = new FootnoteProperties();
$fp->setPos(FootnoteProperties::POSITION_PAGE_BOTTOM);
$fp->setNumFmt(NumberFormat::ORDINAL_TEXT);
$fp->setNumStart(6);
$fp->setNumRestart(FootnoteProperties::RESTART_NUMBER_EACH_PAGE);

$section->setEndnoteProperties($ep);
$section->setFootnoteProperties($fp);

$textrun = $section->addTextrun();
$textrun->addText('Second section on a new page with some text followed by an endnote and a footnote. ');

// Second endnote, follows custom properties
$endnote = $textrun->addEndnote();
$endnote->addText('Second endnote, should be on the third page at the end of the section
– even though we told it to be at the end of the document, endnotes only obey default positioning –,
prefixed by "01" – even though we told it to start at 2, the NumRestart value (each section) resets it to 1.');

// Second footnote, follows custom properties
$footnote = $textrun->addFootnote();
$footnote->addText('Second footnote, should be at the bottom of the third page, prefixed by "First"
– even though we told it to start at 6, the NumRestart value (each page), resets it to 1.');

// Third portrait section
$section = $phpWord->addSection();

$textrun = $section->addTextrun();
$textrun->addText('Third and last section, with an endnote and a footnote. ');

// Third endnote, follows default properties
$endnote = $textrun->addEndnote();
$endnote->addText('Third endnote, should be at the bottom of the last section, prefixed by a "10" enclosed in a circle
– note that the previous endnote was counted as part of the continuation, so "10" is displayed, not "9".');

// Third footnote, follows default properties
$footnote = $textrun->addFootnote();
$footnote->addText('Third footnote, should be beneath the text on the last page, prefixed by a "5"
– note that the previous endnote was counted as part of the continuation, so "5" is displayed, not "4".');

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
