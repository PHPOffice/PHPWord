<?php

/** Generate a native Microsoft Works document. Run from the repository root. */
require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

$phpWord = new PhpWord();
$phpWord->addTitleStyle(1, ['name' => 'Arial', 'size' => 20, 'bold' => true], ['alignment' => 'center']);
$phpWord->addFontStyle('Emphasis', ['name' => 'Courier New', 'size' => 12, 'italic' => true, 'color' => '146B55']);
$section = $phpWord->addSection(['marginTop' => 720, 'marginBottom' => 720]);
$section->addTitle('Microsoft Works export', 1);
$section->addText('Unicode text: Bonjour — café, Ελληνικά, 日本語.', ['size' => 12]);
$run = $section->addTextRun();
$run->addText('A paragraph with ');
$run->addText('a named font style', 'Emphasis');
$run->addText(', ');
$run->addText('single underline', ['underline' => 'single']);
$run->addText(' and ');
$run->addText('double underline', ['underline' => 'dbl']);
$run->addTextBreak();
$run->addText('A line break within the same paragraph.');
$section->addText('An indented paragraph.', null, ['indentation' => ['left' => 720, 'hanging' => 180]]);
$section->addTextBreak();
$section->addPageBreak();
$section->addText('Second page.', ['bold' => true]);
IOFactory::createWriter($phpWord, 'WPS')->save(__DIR__ . '/Sample_46_WPS.wps');
