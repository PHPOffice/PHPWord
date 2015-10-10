<?php
include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

$section = $phpWord->addSection();

// In section
$textbox = $section->addTextBox(
    array(
        'alignment'   => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
        'width'       => 400,
        'height'      => 150,
        'borderSize'  => 1,
        'borderColor' => '#FF0000',
    )
);
$textbox->addText(htmlspecialchars('Text box content in section.', ENT_COMPAT, 'UTF-8'));
$textbox->addText(htmlspecialchars('Another line.', ENT_COMPAT, 'UTF-8'));
$cell = $textbox->addTable()->addRow()->addCell();
$cell->addText(htmlspecialchars('Table inside textbox', ENT_COMPAT, 'UTF-8'));

// Inside table
$section->addTextBreak(2);
$cell = $section->addTable()->addRow()->addCell(300);
$textbox = $cell->addTextBox(array('borderSize' => 1, 'borderColor' => '#0000FF', 'innerMargin' => 100));
$textbox->addText(htmlspecialchars('Textbox inside table', ENT_COMPAT, 'UTF-8'));

// Inside header with textrun
$header = $section->addHeader();
$textbox = $header->addTextBox(array('width' => 600, 'borderSize' => 1, 'borderColor' => '#00FF00'));
$textrun = $textbox->addTextRun();
$textrun->addText(htmlspecialchars('TextBox in header. TextBox can contain a TextRun ', ENT_COMPAT, 'UTF-8'));
$textrun->addText(htmlspecialchars('with bold text', ENT_COMPAT, 'UTF-8'), array('bold' => true));
$textrun->addText(htmlspecialchars(', ', ENT_COMPAT, 'UTF-8'));
$textrun->addLink('https://github.com/PHPOffice/PHPWord', htmlspecialchars('PHPWord on GitHub', ENT_COMPAT, 'UTF-8'));
$textrun->addText(htmlspecialchars(', and image ', ENT_COMPAT, 'UTF-8'));
$textrun->addImage('resources/_earth.jpg', array('width' => 18, 'height' => 18));
$textrun->addText(htmlspecialchars('.', ENT_COMPAT, 'UTF-8'));

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
