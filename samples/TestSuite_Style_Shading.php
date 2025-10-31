<?php

use PhpOffice\PhpWord\Style\Font as FontStyle;
use PhpOffice\PhpWord\Style\Shading as ShadingStyle;

include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s') , ' Create new PhpWord object' , EOL;

$languageEnGb = new PhpOffice\PhpWord\Style\Language(PhpOffice\PhpWord\Style\Language::EN_GB);

$phpWord = new PhpOffice\PhpWord\PhpWord();
$phpWord->getSettings()->setThemeFontLang($languageEnGb);
$phpWord->addTitleStyle(1, ['bold' => true, 'underline' => 'single', 'size' => 18]);
$phpWord->addTitleStyle(2, ['bold' => true, 'underline' => 'single']);
$section = $phpWord->addSection();

$section->addTitle('Testing All Shading Styles', 1);
$section->addTextBreak();

// Fill.
$section->addTitle('Fill', 2);
$section->addText("Paragraph. Color Yellow, Fill Red.", null, ['shading' => ['fill' => FontStyle::FGCOLOR_RED]]);
$section->addText("Font. Color Yellow, Fill Red.", ['shading' => ['fill' => FontStyle::FGCOLOR_RED]]);
$section->addTextBreak();

// Color.
$section->addTitle('Color using Solid Pattern', 2);
$section->addText("Paragraph. Color Yellow, Fill Red.", null, ['shading' => ['color' => FontStyle::FGCOLOR_YELLOW, 'pattern' => ShadingStyle::PATTERN_SOLID]]);
$section->addText("Font. Color Yellow, Fill Red.", ['shading' => ['color' => FontStyle::FGCOLOR_YELLOW, 'pattern' => ShadingStyle::PATTERN_SOLID]]);
$section->addTextBreak();

// Color and Fill.
$section->addTitle('Color using Solid Pattern and Fill. Which will win?', 2);
$section->addText("Paragraph. Color Yellow, Fill Red.", null, ['shading' => ['fill' => FontStyle::FGCOLOR_RED, 'color' => FontStyle::FGCOLOR_YELLOW, 'pattern' => ShadingStyle::PATTERN_SOLID]]);
$section->addText("Font. Color Yellow, Fill Red.", ['shading' => ['fill' => FontStyle::FGCOLOR_RED, 'color' => FontStyle::FGCOLOR_YELLOW, 'pattern' => ShadingStyle::PATTERN_SOLID]]);
$section->addTextBreak();

// Color and Fill.
$section->addTitle('Color using Diagonal Cross Pattern and Fill', 2);
$section->addText("Paragraph. Color Yellow, Fill Red.", null, ['shading' => ['fill' => FontStyle::FGCOLOR_RED, 'color' => FontStyle::FGCOLOR_YELLOW, 'pattern' => ShadingStyle::PATTERN_DCROSS]]);
$section->addText("Font. Color Yellow, Fill Red.", ['shading' => ['fill' => FontStyle::FGCOLOR_RED, 'color' => FontStyle::FGCOLOR_YELLOW, 'pattern' => ShadingStyle::PATTERN_DCROSS]]);
$section->addTextBreak();

// Pattern.
$section->addTitle('Patterns', 2);
$textrun = $section->addTextRun();
$textrun->addText("This text uses the patterns: ");
$textrun->addText("Solid, ", ['size' => 24, 'shading' => ['color' => FontStyle::FGCOLOR_BLUE, 'pattern' => ShadingStyle::PATTERN_SOLID]]);
$textrun->addText("Clear, ", ['size' => 24, 'shading' => ['color' => FontStyle::FGCOLOR_BLUE, 'pattern' => ShadingStyle::PATTERN_CLEAR]]);
$textrun->addText("Horizontal Stripe, ", ['size' => 24, 'shading' => ['color' => FontStyle::FGCOLOR_BLUE, 'pattern' => ShadingStyle::PATTERN_HSTRIPE]]);
$textrun->addText("Vertical Stripe, ", ['size' => 24, 'shading' => ['color' => FontStyle::FGCOLOR_BLUE, 'pattern' => ShadingStyle::PATTERN_VSTRIPE]]);
$textrun->addText("Diagonal Stripe, ", ['size' => 24, 'shading' => ['color' => FontStyle::FGCOLOR_BLUE, 'pattern' => ShadingStyle::PATTERN_DSTRIPE]]);
$textrun->addText("Horizontal Cross, ", ['size' => 24, 'shading' => ['color' => FontStyle::FGCOLOR_BLUE, 'pattern' => ShadingStyle::PATTERN_HCROSS]]);
$textrun->addText("Diagonal Cross, ", ['size' => 24, 'shading' => ['color' => FontStyle::FGCOLOR_BLUE, 'pattern' => ShadingStyle::PATTERN_DCROSS]]);
$section->addTextBreak();

// Pattern.
$section->addTitle('Patterns with Fill', 2);
$textrun = $section->addTextRun();
$textrun->addText("Same as previous, with red fill: ");
$textrun->addText("Solid, ", ['size' => 24, 'shading' => ['fill' => FontStyle::FGCOLOR_RED, 'color' => FontStyle::FGCOLOR_BLUE, 'pattern' => ShadingStyle::PATTERN_SOLID]]);
$textrun->addText("Clear, ", ['size' => 24, 'shading' => ['fill' => FontStyle::FGCOLOR_RED, 'color' => FontStyle::FGCOLOR_BLUE, 'pattern' => ShadingStyle::PATTERN_CLEAR]]);
$textrun->addText("Horizontal Stripe, ", ['size' => 24, 'shading' => ['fill' => FontStyle::FGCOLOR_RED, 'color' => FontStyle::FGCOLOR_BLUE, 'pattern' => ShadingStyle::PATTERN_HSTRIPE]]);
$textrun->addText("Vertical Stripe, ", ['size' => 24, 'shading' => ['fill' => FontStyle::FGCOLOR_RED, 'color' => FontStyle::FGCOLOR_BLUE, 'pattern' => ShadingStyle::PATTERN_VSTRIPE]]);
$textrun->addText("Diagonal Stripe, ", ['size' => 24, 'shading' => ['fill' => FontStyle::FGCOLOR_RED, 'color' => FontStyle::FGCOLOR_BLUE, 'pattern' => ShadingStyle::PATTERN_DSTRIPE]]);
$textrun->addText("Horizontal Cross, ", ['size' => 24, 'shading' => ['fill' => FontStyle::FGCOLOR_RED, 'color' => FontStyle::FGCOLOR_BLUE, 'pattern' => ShadingStyle::PATTERN_HCROSS]]);
$textrun->addText("Diagonal Cross, ", ['size' => 24, 'shading' => ['fill' => FontStyle::FGCOLOR_RED, 'color' => FontStyle::FGCOLOR_BLUE, 'pattern' => ShadingStyle::PATTERN_DCROSS]]);
$section->addTextBreak();

// Table
$rows = 3;
$cols = 3;

$section->addTitle('Table Fill Yellow', 2);
$tableFill = ['shading' => ['fill' => FontStyle::FGCOLOR_YELLOW]];
$phpWord->addTableStyle('fillYellow', $tableFill);
$table = $section->addTable('fillYellow');
for ($r = 1; $r <= $rows; ++$r) {
    $table->addRow();
    for ($c = 1; $c <= $cols; ++$c) {
        $table->addCell(1750)->addText("Row {$r}, Cell {$c}");
    }
}
$section->addTextBreak();

$section->addTitle('Table Color Light Green, Pattern Solid', 2);
$tableColor = ['shading' => ['color' => FontStyle::FGCOLOR_LIGHTGREEN, 'pattern' => ShadingStyle::PATTERN_SOLID]];
$phpWord->addTableStyle('colorYellow', $tableColor);
$table = $section->addTable('colorYellow');
for ($r = 1; $r <= $rows; ++$r) {
    $table->addRow();
    for ($c = 1; $c <= $cols; ++$c) {
        $table->addCell(1750)->addText("Row {$r}, Cell {$c}");
    }
}
$section->addTextBreak();

$section->addTitle('Table Color Yellow, Pattern vStripe', 2);
$tablePattern = ['shading' => ['color' => FontStyle::FGCOLOR_YELLOW, 'pattern' => ShadingStyle::PATTERN_VSTRIPE]];
$phpWord->addTableStyle('patternYellow', $tablePattern);
$table = $section->addTable('patternYellow');
for ($r = 1; $r <= $rows; ++$r) {
    $table->addRow();
    for ($c = 1; $c <= $cols; ++$c) {
        $table->addCell(1750)->addText("Row {$r}, Cell {$c}");
    }
}
$section->addTextBreak();

// Cell
$rows = 3;
$cols = 3;

$section->addTitle('Cell Shading, using the same options as the previous tables', 2);
$cellStyle1 = ['shading' => ['fill' => FontStyle::FGCOLOR_YELLOW]];
$cellStyle2 = ['shading' => ['color' => FontStyle::FGCOLOR_LIGHTGREEN, 'pattern' => ShadingStyle::PATTERN_SOLID]];
$cellStyle3 = ['shading' => ['color' => FontStyle::FGCOLOR_YELLOW, 'pattern' => ShadingStyle::PATTERN_VSTRIPE]];
$table = $section->addTable();
for ($r = 1; $r <= $rows; ++$r) {
    $table->addRow();
    for ($c = 1; $c <= $cols; ++$c) {
        $table->addCell(1750, ${'cellStyle' . $c})->addText("Row {$r}, Cell {$c}");
    }
}

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
