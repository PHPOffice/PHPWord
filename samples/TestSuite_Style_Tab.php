<?php

use PhpOffice\PhpWord\Style\Tab as TabStyle;

include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s') , ' Create new PhpWord object' , EOL;

$languageEnGb = new PhpOffice\PhpWord\Style\Language(PhpOffice\PhpWord\Style\Language::EN_GB);

$phpWord = new PhpOffice\PhpWord\PhpWord();
$phpWord->getSettings()->setThemeFontLang($languageEnGb);
$phpWord->addTitleStyle(1, ['bold' => true, 'underline' => 'single', 'size' => 18]);
$phpWord->addTitleStyle(2, ['bold' => true, 'underline' => 'single']);
$section = $phpWord->addSection();

$section->addTitle('Testing All Tab Styles', 1);
$section->addTextBreak();

// Tab Stop Type.
$section->addTitle('Tab Stop Type', 2);
$section->addText("Tab\tLeft 2880", null, ['tabs' => [new TabStyle(TabStyle::TAB_STOP_LEFT, 2880)]]);
$section->addText("Tab\tCenter 2880", null, ['tabs' => [new TabStyle(TabStyle::TAB_STOP_CENTER, 2880)]]);
$section->addText("Tab\tRight 2880", null, ['tabs' => [new TabStyle(TabStyle::TAB_STOP_RIGHT, 2880)]]);
$section->addText("Tab\tDecimal 28.80", null, ['tabs' => [new TabStyle(TabStyle::TAB_STOP_DECIMAL, 2880)]]);
$section->addText("Tab\tBar 2880", null, ['tabs' => [new TabStyle(TabStyle::TAB_STOP_BAR, 2880)]]);
$section->addText("Tab\tNum 2880", null, ['tabs' => [new TabStyle(TabStyle::TAB_STOP_NUM, 2880)]]);
$section->addTextBreak();

// Tab Leader.
$section->addTitle('Tab Leader', 2);
$section->addText("Tab\tNone", null, ['tabs' => [new TabStyle(TabStyle::TAB_STOP_LEFT, 1440, TabStyle::TAB_LEADER_NONE)]]);
$section->addText("Tab\tDot", null, ['tabs' => [new TabStyle(TabStyle::TAB_STOP_LEFT, 1440, TabStyle::TAB_LEADER_DOT)]]);
$section->addText("Tab\tHyphen", null, ['tabs' => [new TabStyle(TabStyle::TAB_STOP_LEFT, 1440, TabStyle::TAB_LEADER_HYPHEN)]]);
$section->addText("Tab\tUnderscore", null, ['tabs' => [new TabStyle(TabStyle::TAB_STOP_LEFT, 1440, TabStyle::TAB_LEADER_UNDERSCORE)]]);
$section->addText("Tab\tHeavy", null, ['tabs' => [new TabStyle(TabStyle::TAB_STOP_LEFT, 1440, TabStyle::TAB_LEADER_HEAVY)]]);
$section->addText("Tab\tMiddledot", null, ['tabs' => [new TabStyle(TabStyle::TAB_STOP_LEFT, 1440, TabStyle::TAB_LEADER_MIDDLEDOT)]]);
$section->addTextBreak();

// Multiple Tabs.
$section->addTitle('Multiple Tabs', 2);
$section->addText("\tMultiple Tabs at Left 1440\tCenter 5000\tRight 9340", null, ['tabs' => [new TabStyle(TabStyle::TAB_STOP_LEFT, 1440), new TabStyle(TabStyle::TAB_STOP_CENTER, 5000), new TabStyle(TabStyle::TAB_STOP_RIGHT, 9340)]]);
$section->addTextBreak();

// Clearing Tabs.
$section->addTitle('Clearing Tabs', 2);
$phpWord->addParagraphStyle('pTabs', ['tabs' => [new TabStyle(TabStyle::TAB_STOP_LEFT, 1440), new TabStyle(TabStyle::TAB_STOP_CENTER, 5000), new TabStyle(TabStyle::TAB_STOP_RIGHT, 9340)]]);
$phpWord->addParagraphStyle('pTabsClear', ['basedOn' => 'pTabs', 'tabs' => [new TabStyle(TabStyle::TAB_STOP_CLEAR, 9340)]]);
$section->addText("\tThis paragraph\tmatches the previous\twith multiple tabs", null, 'pTabs');
$section->addText("\tThis paragraph does\tnot\thave the right tab", null, 'pTabsClear');

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
