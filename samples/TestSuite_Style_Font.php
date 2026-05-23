<?php

use PhpOffice\PhpWord\Style\Font;

include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s') , ' Create new PhpWord object' , EOL;

$languageEnGb = new PhpOffice\PhpWord\Style\Language(PhpOffice\PhpWord\Style\Language::EN_GB);

$phpWord = new PhpOffice\PhpWord\PhpWord();
$phpWord->getSettings()->setThemeFontLang($languageEnGb);
$phpWord->addTitleStyle(1, ['bold' => true, 'underline' => 'single', 'size' => 18]);
$phpWord->addTitleStyle(2, ['bold' => true, 'underline' => 'single']);
$section = $phpWord->addSection();

$section->addTitle('Testing All Font Styles', 1);
$section->addText('See also: Style > Language');
$section->addText('See also: Style > Shading');
$section->addText('See also: Style > Spacing');
$section->addTextBreak();

// Default Font.
$phpWord->setDefaultFontName('Verdana');
$phpWord->setDefaultFontColor('341539');
$phpWord->setDefaultFontSize(11);
$section->addTitle('Default Font', 2);
$section->addText('Default font is Verdana, dark purple (#341539 or rgb 52,21,57), size 11');
$section->addTextBreak();

// Name.
$section->addTitle('Name', 2);
$section->addText('Arial', ['name' => 'Arial']);
$section->addText('Times New Roman', ['name' => 'Times New Roman']);
$section->addText('monospace', ['name' => 'monospace']);
$section->addText('Fallback aka Font Family set to serif', ['name' => 'Junkmail Garbage', 'fallback' => 'serif']);
$section->addText('Fallback aka Font Family set to  sans-serif', ['name' => 'Junkmail Garbage', 'fallback' => 'sans-serif']);
$section->addText('Todo: Test Hint, Ascii, Ansi, and EastAsia, and CS');
$section->addTextBreak();

// RTL.
$section->addTitle('Right-to-Left', 2);
$section->addText('Hey, this font is going the other way!', ['rtl' => true]);
$section->addText('!yaw rehto eht gniog si tnof siht ,yeH', ['rtl' => true]);
$section->addTextBreak();

// Language.
$section->addTitle('Language', 2);
$section->addText('C\'est du français.', ['lang' => 'fr-FR']);
$section->addText('זה עברית.', ['lang' => 'he-IL']);
$section->addText('이것은 한국어입니다', ['name' => 'ko-KR']);
$section->addTextBreak();

// Color.
$section->addTitle('Color', 2);
$section->addText('Dark Green', ['color' => Font::FGCOLOR_DARKGREEN]);
$section->addText('Red', ['color' => Font::FGCOLOR_RED]);
$section->addText('Blue', ['color' => Font::FGCOLOR_BLUE]);
$section->addTextBreak();

// Size.
$section->addTitle('Size', 2);
$section->addText('8 point font', ['size' => 8]);
$section->addText('14 point font', ['size' => 14]);
$section->addText('24 point font', ['size' => 24]);
$section->addTextBreak();

// Bold, italic.
$section->addTitle('Bold/Italic', 2);
$section->addText('Bold', ['bold' => true]);
$section->addText('Italic', ['italic' => true]);
$section->addTextBreak();

// Strikethrough, double strikethrough.
$section->addTitle('Strikethrough', 2);
$section->addText('Strikethrough', ['strikethrough' => true]);
$section->addText('Double Strikethrough', ['doubleStrikethrough' => true]);
$section->addTextBreak();

// Small caps, all caps.
$section->addTitle('Caps', 2);
$section->addText('This Is A Small Caps Sentence.', ['smallCaps' => true]);
$section->addText('This Is An All-Caps Sentence.', ['allCaps' => true]);
$section->addTextBreak();

// Hidden.
$section->addTitle('Hidden Text (you should not be able to see the next line)', 2);
$section->addText('This sentence is hidden.', ['hidden' => true]);
$section->addTextBreak();

// Underline.
$section->addTitle('Underline', 2);
$textrun = $section->addTextRun();
$textrun->addText('This text is ');
$textrun->addText('underline none, ', ['underline' => Font::UNDERLINE_NONE]);
$textrun->addText('underline dash, ', ['underline' => Font::UNDERLINE_DASH]);
$textrun->addText('underline dash heavy, ', ['underline' => Font::UNDERLINE_DASHHEAVY]);
$textrun->addText('underline dash long, ', ['underline' => Font::UNDERLINE_DASHLONG]);
$textrun->addText('underline dash long heavy, ', ['underline' => Font::UNDERLINE_DASHLONGHEAVY]);
$textrun->addText('underline single, ', ['underline' => Font::UNDERLINE_SINGLE]);
$textrun->addText('underline double, ', ['underline' => Font::UNDERLINE_DOUBLE]);
$textrun->addText('underline heavy, ', ['underline' => Font::UNDERLINE_HEAVY]);
$textrun->addText('underline dot dash, ', ['underline' => Font::UNDERLINE_DOTDASH]);
$textrun->addText('underline dot dash heavy, ', ['underline' => Font::UNDERLINE_DOTDASHHEAVY]);
$textrun->addText('underline dot dot dash, ', ['underline' => Font::UNDERLINE_DOTDOTDASH]);
$textrun->addText('underline dot dot dash heavy, ', ['underline' => Font::UNDERLINE_DOTDOTDASHHEAVY]);
$textrun->addText('underline dotted, ', ['underline' => Font::UNDERLINE_DOTTED]);
$textrun->addText('underline dotted heavy, ', ['underline' => Font::UNDERLINE_DOTTEDHEAVY]);
$textrun->addText('underline wavy, ', ['underline' => Font::UNDERLINE_WAVY]);
$textrun->addText('underline wavy double, ', ['underline' => Font::UNDERLINE_WAVYDOUBLE]);
$textrun->addText('underline wavy heavy, ', ['underline' => Font::UNDERLINE_WAVYHEAVY]);
$textrun->addText('only the words are underlined and not    the    spaces.', ['underline' => Font::UNDERLINE_WORDS]);
$section->addTextBreak();

// Foreground Color.
$section->addTitle('Foreground Color, aka Highlight', 2);
$section->addText('Highlight yellow', ['fgColor' => Font::FGCOLOR_YELLOW]);
$section->addText('Highlight cyan', ['fgColor' => Font::FGCOLOR_CYAN]);
$section->addText('Highlight light gray', ['fgColor' => Font::FGCOLOR_LIGHTGRAY]);
$section->addTextBreak();

// Superscript, subscript, position.
$section->addTitle('Super/subscript', 2);
$textrun = $section->addTextRun();
$textrun->addText('This text is ');
$textrun->addText('superscript', ['superScript' => true]);
$textrun->addText(' and ');
$textrun->addText('subscript', ['subScript' => true]);
$textrun = $section->addTextRun();
$textrun->addText('This text is ');
$textrun->addText('raised half a step', ['position' => 8]);
$textrun->addText(' and ');
$textrun->addText('lowered half a step', ['position' => -8]);
$section->addTextBreak();

// noProof.
$section->addTitle('No Proof', 2);
$section->addText('Even thoughdffds pthispdsentence spdfi is full of tyhpeing errors, there should be no proofing.', ['noProof' => true]);
$section->addTextBreak();

// Whitespace.
$section->addTitle('For HTML Only', 2);
$section->addText('Lots    of     whitespace      here.', ['whiteSpace' => 'pre-wrap']);
$section->addTextBreak();

// Contains Paragraph.
$section->addTitle('Font with embedded Paragraph Style', 2);
$section->addText('This text should be centered.', ['paragraph' => ['align' => 'center']]);

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
