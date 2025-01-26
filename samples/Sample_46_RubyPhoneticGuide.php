<?php

use PhpOffice\PhpWord\ComplexType\RubyProperties;
use PhpOffice\PhpWord\Element\TextRun;

include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s'), ' Create sample for Ruby (Phonetic Guide) use', EOL;
$phpWord = new PhpOffice\PhpWord\PhpWord();

// Section for demonstrating ruby (phonetic guide) features
$section = $phpWord->addSection();

$section->addText('Here is some normal text with no ruby, also known as "phonetic guide", text.');

$properties = new RubyProperties();
$properties->setAlignment(RubyProperties::ALIGNMENT_CENTER);
$properties->setFontFaceSize(10);
$properties->setFontPointsAboveBaseText(20);
$properties->setFontSizeForBaseText(18);
$properties->setLanguageId('en-US');

$textRun = $section->addTextRun();
$textRun->addText('Here is a demonstration of ruby text for ');
$baseTextRun = new TextRun(null);
$baseTextRun->addText('this');
$rubyTextRun = new TextRun(null);
$rubyTextRun->addText('ruby-text');
$textRun->addRuby($baseTextRun, $rubyTextRun, $properties);
$textRun->addText(' word.');

$textRun = $section->addTextRun();
$properties = new RubyProperties();
$properties->setAlignment(RubyProperties::ALIGNMENT_CENTER);
$properties->setFontFaceSize(10);
$properties->setFontPointsAboveBaseText(20);
$properties->setFontSizeForBaseText(18);
$properties->setLanguageId('ja-JP');
$textRun->addText('Here is a demonstration of ruby text for Japanese text: ');
$baseTextRun = new TextRun(null);
$baseTextRun->addText('私');
$rubyTextRun = new TextRun(null);
$rubyTextRun->addText('わたし');
$textRun->addRuby($baseTextRun, $rubyTextRun, $properties);

$section->addText('You can also have ruby text for titles:');

$phpWord->addTitleStyle(1, ['name' => 'Arial', 'size' => 24, 'bold' => true, 'color' => '000099']);

$properties = new RubyProperties();
$properties->setAlignment(RubyProperties::ALIGNMENT_CENTER);
$properties->setFontFaceSize(10);
$properties->setFontPointsAboveBaseText(50);
$properties->setFontSizeForBaseText(18);
$properties->setLanguageId('ja-JP');

$baseTextRun = new TextRun(null);
$baseTextRun->addText('私');
$rubyTextRun = new TextRun(null);
$rubyTextRun->addText('わたし');
$textRun = new TextRun();
$textRun->addRuby($baseTextRun, $rubyTextRun, $properties);
$section->addTitle($textRun, 1);

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
