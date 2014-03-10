<?php

error_reporting(E_ALL);

if(php_sapi_name() == 'cli' && empty($_SERVER['REMOTE_ADDR'])) {
  define('EOL', PHP_EOL);
} else {
  define('EOL', '<br />');
}

require_once '../Classes/PHPWord.php';

// New Word Document
echo date('H:i:s') , ' Create new PHPWord object' , EOL;
$PHPWord = new PHPWord();
$section = $PHPWord->createSection();
$header = array('size' => 16, 'bold' => true);

// 1. Basic table

$rows = 10;
$cols = 5;
$section->addText("Basic table", $header);

$table = $section->addTable();
for($r = 1; $r <= 8; $r++) {
    $table->addRow();
    for($c = 1; $c <= 5; $c++) {
        $table->addCell(1750)->addText("Row $r, Cell $c");
    }
}

// 2. Advanced table

$section->addTextBreak(1);
$section->addText("Fancy table", $header);

$styleTable = array('borderSize' => 6, 'borderColor' => '006699', 'cellMargin' => 80);
$styleFirstRow = array('borderBottomSize' => 18, 'borderBottomColor' => '0000FF', 'bgColor' => '66BBFF');
$styleCell = array('valign' => 'center');
$styleCellBTLR = array('valign' => 'center', 'textDirection' => PHPWord_Style_Cell::TEXT_DIR_BTLR);
$fontStyle = array('bold' => true, 'align' => 'center');
$PHPWord->addTableStyle('Fancy Table', $styleTable, $styleFirstRow);
$table = $section->addTable('Fancy Table');
$table->addRow(900);
$table->addCell(2000, $styleCell)->addText('Row 1', $fontStyle);
$table->addCell(2000, $styleCell)->addText('Row 2', $fontStyle);
$table->addCell(2000, $styleCell)->addText('Row 3', $fontStyle);
$table->addCell(2000, $styleCell)->addText('Row 4', $fontStyle);
$table->addCell(500, $styleCellBTLR)->addText('Row 5', $fontStyle);
for($i = 1; $i <= 8; $i++) {
    $table->addRow();
    $table->addCell(2000)->addText("Cell $i");
    $table->addCell(2000)->addText("Cell $i");
    $table->addCell(2000)->addText("Cell $i");
    $table->addCell(2000)->addText("Cell $i");
    $text = ($i % 2 == 0) ? 'X' : '';
    $table->addCell(500)->addText($text);
}

// 3. colspan (gridSpan) and rowspan (vMerge)

$section->addTextBreak(1);
$section->addText("Table with colspan and rowspan", $header);

$styleTable = array('borderSize' => 6, 'borderColor' => '999999');
$cellRowSpan = array('vMerge' => 'restart', 'valign' => 'center');
$cellRowContinue = array('vMerge' => 'continue');
$cellColSpan = array('gridSpan' => 2, 'valign' => 'center');
$cellHCentered = array('align' => 'center');
$cellVCentered = array('valign' => 'center');

$PHPWord->addTableStyle('Colspan Rowspan', $styleTable);
$table = $section->addTable('Colspan Rowspan');
$table->addRow();
$table->addCell(2000, $cellRowSpan)->addText('A', null, $cellHCentered);
$table->addCell(4000, $cellColSpan)->addText('B', null, $cellHCentered);
$table->addCell(2000, $cellRowSpan)->addText('E', null, $cellHCentered);
$table->addRow();
$table->addCell(null, $cellRowContinue);
$table->addCell(2000, $cellVCentered)->addText('C', null, $cellHCentered);
$table->addCell(2000, $cellVCentered)->addText('D', null, $cellHCentered);
$table->addCell(null, $cellRowContinue);

// Save File
echo date('H:i:s') , ' Write to Word2007 format' , EOL;
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$objWriter->save(str_replace('.php', '.docx', __FILE__));

// echo date('H:i:s') , ' Write to OpenDocumentText format' , EOL;
// $objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'ODText');
// $objWriter->save(str_replace('.php', '.odt', __FILE__));
//
// echo date('H:i:s') , ' Write to RTF format' , EOL;
// $objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'RTF');
// $objWriter->save(str_replace('.php', '.rtf', __FILE__));

// Echo memory peak usage
echo date('H:i:s') , ' Peak memory usage: ' , (memory_get_peak_usage(true) / 1024 / 1024) , ' MB' , EOL;

// Echo done
echo date('H:i:s') , ' Done writing file' , EOL;
