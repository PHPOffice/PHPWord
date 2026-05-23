<?php

use PhpOffice\PhpWord\SimpleType\Border;
use PhpOffice\PhpWord\Style\Font;

include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s') , ' Create new PhpWord object' , EOL;

$languageEnGb = new PhpOffice\PhpWord\Style\Language(PhpOffice\PhpWord\Style\Language::EN_GB);

$phpWord = new PhpOffice\PhpWord\PhpWord();
$phpWord->getSettings()->setThemeFontLang($languageEnGb);
$phpWord->addTitleStyle(1, ['bold' => true, 'underline' => 'single', 'size' => 18]);
$phpWord->addTitleStyle(2, ['bold' => true, 'underline' => 'single']);
$section = $phpWord->addSection(['borderColor' => '00FF00', 'borderSize' => 12, 'marginLeft' => 1000, 'marginRight' => 400, 'marginTop' => 800, 'marginBottom' => 200]);

$section->addTitle('Testing All Border Styles', 1);
$section->addTextBreak();

// Section Border.
$section->addTitle('Section Border', 2);
$section->addText('This section has a green border 12 twip thick, with a left margin of 1000, right margin of 400, top margin of 800, and bottom margin of 200');
$section->addTextBreak();

// Paragraph Border.
$section->addTitle('Paragraph Border', 2);
$section->addText('Border red, 40 twip thick.', null, ['borderColor' => Font::FGCOLOR_RED, 'borderSize' => 40]);
$section->addText('Top cyan border, double line 20 twip thick', null, ['borderTopColor' => Font::FGCOLOR_CYAN, 'borderTopSize' => 20, 'borderTopStyle' => Border::DOUBLE]);
$section->addText('Left cyan border, double line 20 twip thick', null, ['borderLeftColor' => Font::FGCOLOR_CYAN, 'borderLeftSize' => 20, 'borderLeftStyle' => Border::DOUBLE]);
$section->addText('Right cyan border, double line 20 twip thick', null, ['borderRightColor' => Font::FGCOLOR_CYAN, 'borderRightSize' => 20, 'borderRightStyle' => Border::DOUBLE]);
$section->addText('Bottom cyan border, double line 20 twip thick', null, ['borderBottomColor' => Font::FGCOLOR_CYAN, 'borderBottomSize' => 20, 'borderBottomStyle' => Border::DOUBLE]);
$section->addText('Paragraphs don\'t have margins.');
$section->addTextBreak();

// Table Border.
$section->addTitle('Table Border', 2);
$section->addText('TODO');
$section->addTextBreak();

// Row Border.
$section->addTitle('Row Border', 2);
$section->addText('Not Yet Implemented in Row Style');
$section->addTextBreak();

// Cell Border.
$section->addTitle('Cell Border', 2);
$section->addText('TODO');
$section->addTextBreak();

// Font Border.
$section->addTitle('Font Border, aka Character Border', 2);
$section->addText('Not Yet Implemented in Font Style');
$section->addTextBreak();

// Border Styles.
$section->addTitle('Border Styles', 2);
$section->addText('Top border single line, bottom border dash dot stroke', null, ['borderTopStyle' => Border::SINGLE, 'borderBottomStyle' => Border::DASH_DOT_STROKED, 'borderTopColor' => Font::FGCOLOR_BLACK, 'borderTopSize' => 20, 'borderBottomColor' => Font::FGCOLOR_BLUE, 'borderBottomSize' => 20]);
$section->addText('Top border dashed, bottom border dash small gap', null, ['borderTopStyle' => Border::DASHED, 'borderBottomStyle' => Border::DASH_SMALL_GAP, 'borderTopColor' => Font::FGCOLOR_BLACK, 'borderTopSize' => 20, 'borderBottomColor' => Font::FGCOLOR_BLUE, 'borderBottomSize' => 20]);
$section->addText('Top border dot dash, bottom border dot dot dash', null, ['borderTopStyle' => Border::DOT_DASH, 'borderBottomStyle' => Border::DOT_DOT_DASH, 'borderTopColor' => Font::FGCOLOR_BLACK, 'borderTopSize' => 20, 'borderBottomColor' => Font::FGCOLOR_BLUE, 'borderBottomSize' => 20]);
$section->addText('Top border dotted, bottom border double line', null, ['borderTopStyle' => Border::DOTTED, 'borderBottomStyle' => Border::DOUBLE, 'borderTopColor' => Font::FGCOLOR_BLACK, 'borderTopSize' => 20, 'borderBottomColor' => Font::FGCOLOR_BLUE, 'borderBottomSize' => 20]);
$section->addText('Top border double wave, bottom border inset', null, ['borderTopStyle' => Border::DOUBLE_WAVE, 'borderBottomStyle' => Border::INSET, 'borderTopColor' => Font::FGCOLOR_BLACK, 'borderTopSize' => 20, 'borderBottomColor' => Font::FGCOLOR_BLUE, 'borderBottomSize' => 20]);
$section->addTextBreak();
$section->addText('No borders. Border set to nil and none.', null, ['borderTopStyle' => Border::NIL, 'borderBottomStyle' => Border::NONE, 'borderTopColor' => Font::FGCOLOR_BLACK, 'borderTopSize' => 20, 'borderBottomColor' => Font::FGCOLOR_BLUE, 'borderBottomSize' => 20]);
$section->addTextBreak();
$section->addText('Top border outset, bottom border thick', null, ['borderTopStyle' => Border::OUTSET, 'borderBottomStyle' => Border::THICK, 'borderTopColor' => Font::FGCOLOR_BLACK, 'borderTopSize' => 20, 'borderBottomColor' => Font::FGCOLOR_BLUE, 'borderBottomSize' => 20]);
$section->addText('Top border thick thin large gap, bottom border thick thin medium gap', null, ['borderTopStyle' => Border::THICK_THIN_LARGE_GAP, 'borderBottomStyle' => Border::THICK_THIN_MEDIUM_GAP, 'borderTopColor' => Font::FGCOLOR_BLACK, 'borderTopSize' => 20, 'borderBottomColor' => Font::FGCOLOR_BLUE, 'borderBottomSize' => 20]);
$section->addText('Top border thick thin small gap, bottom border thin thick large gap', null, ['borderTopStyle' => Border::THICK_THIN_SMALL_GAP, 'borderBottomStyle' => Border::THIN_THICK_LARGE_GAP, 'borderTopColor' => Font::FGCOLOR_BLACK, 'borderTopSize' => 20, 'borderBottomColor' => Font::FGCOLOR_BLUE, 'borderBottomSize' => 20]);
$section->addText('Top border tin thick medium gap, bottom border thin thick small gap', null, ['borderTopStyle' => Border::THIN_THICK_MEDIUM_GAP, 'borderBottomStyle' => Border::THIN_THICK_SMALL_GAP, 'borderTopColor' => Font::FGCOLOR_BLACK, 'borderTopSize' => 20, 'borderBottomColor' => Font::FGCOLOR_BLUE, 'borderBottomSize' => 20]);
$section->addText('Top border thin thick thin large gap, bottom border thin thick thin medium gap', null, ['borderTopStyle' => Border::THIN_THICK_THINLARGE_GAP, 'borderBottomStyle' => Border::THIN_THICK_THIN_MEDIUM_GAP, 'borderTopColor' => Font::FGCOLOR_BLACK, 'borderTopSize' => 20, 'borderBottomColor' => Font::FGCOLOR_BLUE, 'borderBottomSize' => 20]);
$section->addText('Top border thin thick thin small gap, bottom border three d emboss', null, ['borderTopStyle' => Border::THIN_THICK_THIN_SMALL_GAP, 'borderBottomStyle' => Border::THREE_D_EMBOSS, 'borderTopColor' => Font::FGCOLOR_BLACK, 'borderTopSize' => 20, 'borderBottomColor' => Font::FGCOLOR_BLUE, 'borderBottomSize' => 20]);
$section->addText('Top border three d engrave, bottom border triple line', null, ['borderTopStyle' => Border::THREE_D_ENGRAVE, 'borderBottomStyle' => Border::TRIPLE, 'borderTopColor' => Font::FGCOLOR_BLACK, 'borderTopSize' => 20, 'borderBottomColor' => Font::FGCOLOR_BLUE, 'borderBottomSize' => 20]);
$section->addText('Top border wave, bottom border single line', null, ['borderTopStyle' => Border::WAVE, 'borderBottomStyle' => Border::SINGLE, 'borderTopColor' => Font::FGCOLOR_BLACK, 'borderTopSize' => 20, 'borderBottomColor' => Font::FGCOLOR_BLUE, 'borderBottomSize' => 20]);

// Section Border.
$section = $phpWord->addSection(['borderLeftColor' => Font::FGCOLOR_MAGENTA, 'borderLeftSize' => 100, 'borderLeftStyle' => Border::DOUBLE_WAVE]);
$section->addTitle('Section Border', 2);
$section->addText('This is just a left border, magenta, double wave, 100 twip thick. Default margins.');

// Section Border.
$section = $phpWord->addSection(['borderRightColor' => Font::FGCOLOR_MAGENTA, 'borderRightSize' => 50, 'borderRightStyle' => Border::DOUBLE_WAVE, 'borderBottomColor' => Font::FGCOLOR_MAGENTA, 'borderBottomSize' => 50, 'borderBottomStyle' => Border::DOUBLE_WAVE]);
$section->addTitle('Section Border', 2);
$section->addText('This is just a right and bottom border, magenta, double wave, 50 twip thick. Default margins.');

// Section Border.
$section = $phpWord->addSection(['borderTopColor' => Font::FGCOLOR_MAGENTA, 'borderTopSize' => 400, 'borderTopStyle' => Border::TRIPLE]);
$section->addTitle('Section Border', 2);
$section->addText('This is top border, magenta, triple line, 400 twip thick. Default margins.');

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
