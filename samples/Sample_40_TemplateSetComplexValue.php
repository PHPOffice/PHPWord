<?php
declare(strict_types=1);
use PhpOffice\PhpWord\Element\Field;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Style\Colors\HighlightColor;
use PhpOffice\PhpWord\Style\Lengths\Absolute;
use PhpOffice\PhpWord\TemplateProcessor;

include_once 'Sample_Header.php';

// Template processor instance creation
echo date('H:i:s'), ' Creating new TemplateProcessor instance...', EOL;
$templateProcessor = new TemplateProcessor('resources/Sample_40_TemplateSetComplexValue.docx');

$title = new TextRun();
$title->addText('This title has been set ', array('bold' => true, 'italic' => true, 'color' => new HighlightColor('blue')));
$title->addText('dynamically', array('bold' => true, 'italic' => true, 'color' => new HighlightColor('red'), 'underline' => 'single'));
$templateProcessor->setComplexBlock('title', $title);

$inline = new TextRun();
$inline->addText('by a red italic text', array('italic' => true, 'color' => new HighlightColor('red')));
$templateProcessor->setComplexValue('inline', $inline);

$table = new Table(array('borderSize' => Absolute::from('twip', 12), 'borderColor' => new HighlightColor('green'), 'width' => Absolute::from('twip', 6000)));
$table->addRow();
$table->addCell(Absolute::from('twip', 150))->addText('Cell A1');
$table->addCell(Absolute::from('twip', 150))->addText('Cell A2');
$table->addCell(Absolute::from('twip', 150))->addText('Cell A3');
$table->addRow();
$table->addCell(Absolute::from('twip', 150))->addText('Cell B1');
$table->addCell(Absolute::from('twip', 150))->addText('Cell B2');
$table->addCell(Absolute::from('twip', 150))->addText('Cell B3');
$templateProcessor->setComplexBlock('table', $table);

$field = new Field('DATE', array('dateformat' => 'dddd d MMMM yyyy H:mm:ss'), array('PreserveFormat'));
$templateProcessor->setComplexValue('field', $field);

// $link = new Link('https://github.com/PHPOffice/PHPWord');
// $templateProcessor->setComplexValue('link', $link);

echo date('H:i:s'), ' Saving the result document...', EOL;
$templateProcessor->saveAs('results/Sample_40_TemplateSetComplexValue.docx');

echo getEndingNotes(array('Word2007' => 'docx'), 'results/Sample_40_TemplateSetComplexValue.docx');
if (!CLI) {
    include_once 'Sample_Footer.php';
}
