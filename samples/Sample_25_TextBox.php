<?php
include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// New section
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
$textbox->addText('Text box content in section.');
$textbox->addText('Another line.');
$cell = $textbox->addTable()->addRow()->addCell();
$cell->addText('Table inside textbox');

// Inside table
$section->addTextBreak(2);
$cell = $section->addTable()->addRow()->addCell(300);
$textbox = $cell->addTextBox(array('borderSize' => 1, 'borderColor' => '#0000FF', 'innerMargin' => 100));
$textbox->addText('Textbox inside table');

// Inside header with textrun
$header = $section->addHeader();
$textbox = $header->addTextBox(array('width' => 600, 'borderSize' => 1, 'borderColor' => '#00FF00'));
$textrun = $textbox->addTextRun();
$textrun->addText('TextBox in header. TextBox can contain a TextRun ');
$textrun->addText('with bold text', array('bold' => true));
$textrun->addText(', ');
$textrun->addLink('https://github.com/PHPOffice/PHPWord', 'PHPWord on GitHub');
$textrun->addText(', and image ');
$textrun->addImage('resources/_earth.jpg', array('width' => 18, 'height' => 18));
$textrun->addText('.');

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
