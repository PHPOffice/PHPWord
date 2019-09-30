<?php
declare(strict_types=1);
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Colors\Hex;
use PhpOffice\PhpWord\Style\Lengths\Absolute;

include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new PhpWord();

// New section
$section = $phpWord->addSection();

// In section
$textbox = $section->addTextBox(
    array(
        'alignment'   => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
        'width'       => Absolute::from('twip', 400),
        'height'      => Absolute::from('twip', 150),
        'borderSize'  => Absolute::from('twip', 1),
        'borderColor' => new Hex('FF0000'),
    )
);
$textbox->addText('Text box content in section.');
$textbox->addText('Another line.');
$cell = $textbox->addTable()->addRow()->addCell();
$cell->addText('Table inside textbox');

// Inside table
$section->addTextBreak(2);
$cell = $section->addTable()->addRow()->addCell(Absolute::from('twip', 300));
$textbox = $cell->addTextBox(array('borderSize' => Absolute::from('twip', 1), 'borderColor' => new Hex('0000FF'), 'innerMargin' => Absolute::from('twip', 100)));
$textbox->addText('Textbox inside table');

// Inside header with textrun
$header = $section->addHeader();
$textbox = $header->addTextBox(array('width' => Absolute::from('twip', 600), 'borderSize' => Absolute::from('twip', 1), 'borderColor' => new Hex('00FF00')));
$textrun = $textbox->addTextRun();
$textrun->addText('TextBox in header. TextBox can contain a TextRun ');
$textrun->addText('with bold text', array('bold' => true));
$textrun->addText(', ');
$textrun->addLink('https://github.com/PHPOffice/PHPWord', 'PHPWord on GitHub');
$textrun->addText(', and image ');
$textrun->addImage('resources/_earth.jpg', array('width' => Absolute::from('pt', 18), 'height' => Absolute::from('pt', 18)));
$textrun->addText('.');

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
