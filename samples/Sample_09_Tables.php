<?php
declare(strict_types=1);
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\JcTable;
use PhpOffice\PhpWord\Style\Cell;
use PhpOffice\PhpWord\Style\Colors\Hex;
use PhpOffice\PhpWord\Style\Lengths\Absolute;
use PhpOffice\PhpWord\Style\TablePosition;

include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$section = $phpWord->addSection();
$header = array('size' => Absolute::from('pt', 16), 'bold' => true);

// 1. Basic table

$rows = 10;
$cols = 5;
$section->addText('Basic table', $header);

$table = $section->addTable();
for ($r = 1; $r <= 8; $r++) {
    $table->addRow();
    for ($c = 1; $c <= 5; $c++) {
        $table->addCell(Absolute::from('twip', 1750))->addText("Row {$r}, Cell {$c}");
    }
}

// 2. Advanced table

$section->addTextBreak(1);
$section->addText('Fancy table', $header);

$fancyTableStyleName = 'Fancy Table';
$fancyTableStyle = array('borderSize' => Absolute::from('twip', 6), 'borderColor' => new Hex('006699'), 'cellMargin' => Absolute::from('twip', 80), 'alignment' => JcTable::CENTER, 'cellSpacing' => Absolute::from('twip', 50));
$fancyTableFirstRowStyle = array('borderBottomSize' => Absolute::from('twip', 18), 'borderBottomColor' => new Hex('0000FF'), 'bgColor' => new Hex('66BBFF'));
$fancyTableCellStyle = array('valign' => 'center');
$fancyTableCellBtlrStyle = array('valign' => 'center', 'textDirection' => Cell::TEXT_DIR_BTLR);
$fancyTableFontStyle = array('bold' => true);
$phpWord->addTableStyle($fancyTableStyleName, $fancyTableStyle, $fancyTableFirstRowStyle);
$table = $section->addTable($fancyTableStyleName);
$table->addRow(Absolute::from('twip', Absolute::from('twip', 900)));
$table->addCell(Absolute::from('twip', 2000), $fancyTableCellStyle)->addText('Row 1', $fancyTableFontStyle);
$table->addCell(Absolute::from('twip', 2000), $fancyTableCellStyle)->addText('Row 2', $fancyTableFontStyle);
$table->addCell(Absolute::from('twip', 2000), $fancyTableCellStyle)->addText('Row 3', $fancyTableFontStyle);
$table->addCell(Absolute::from('twip', 2000), $fancyTableCellStyle)->addText('Row 4', $fancyTableFontStyle);
$table->addCell(Absolute::from('twip', 500), $fancyTableCellBtlrStyle)->addText('Row 5', $fancyTableFontStyle);
for ($i = 1; $i <= 8; $i++) {
    $table->addRow();
    $table->addCell(Absolute::from('twip', 2000))->addText("Cell {$i}");
    $table->addCell(Absolute::from('twip', 2000))->addText("Cell {$i}");
    $table->addCell(Absolute::from('twip', 2000))->addText("Cell {$i}");
    $table->addCell(Absolute::from('twip', 2000))->addText("Cell {$i}");
    $text = (0 == $i % 2) ? 'X' : '';
    $table->addCell(Absolute::from('twip', 500))->addText($text);
}

/*
 *  3. colspan (gridSpan) and rowspan (vMerge)
 *  ---------------------
 *  |     |   B    |    |
 *  |  A  |--------|  E |
 *  |     | C |  D |    |
 *  ---------------------
 */

$section->addPageBreak();
$section->addText('Table with colspan and rowspan', $header);

$fancyTableStyle = array('borderSize' => 6, 'borderColor' => new Hex('999999'));
$cellRowSpan = array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => new Hex('FFFF00'));
$cellRowContinue = array('vMerge' => 'continue');
$cellColSpan = array('gridSpan' => 2, 'valign' => 'center');
$cellHCentered = array('alignment' => Jc::CENTER);
$cellVCentered = array('valign' => 'center');

$spanTableStyleName = 'Colspan Rowspan';
$phpWord->addTableStyle($spanTableStyleName, $fancyTableStyle);
$table = $section->addTable($spanTableStyleName);

$table->addRow();

$cell1 = $table->addCell(Absolute::from('twip', 2000), $cellRowSpan);
$textrun1 = $cell1->addTextRun($cellHCentered);
$textrun1->addText('A');
$textrun1->addFootnote()->addText('Row span');

$cell2 = $table->addCell(Absolute::from('twip', 4000), $cellColSpan);
$textrun2 = $cell2->addTextRun($cellHCentered);
$textrun2->addText('B');
$textrun2->addFootnote()->addText('Column span');

$table->addCell(Absolute::from('twip', 2000), $cellRowSpan)->addText('E', null, $cellHCentered);

$table->addRow();
$table->addCell(null, $cellRowContinue);
$table->addCell(Absolute::from('twip', 2000), $cellVCentered)->addText('C', null, $cellHCentered);
$table->addCell(Absolute::from('twip', 2000), $cellVCentered)->addText('D', null, $cellHCentered);
$table->addCell(null, $cellRowContinue);

/*
 *  4. colspan (gridSpan) and rowspan (vMerge)
 *  ---------------------
 *  |     |   B    |  1 |
 *  |  A  |        |----|
 *  |     |        |  2 |
 *  |     |---|----|----|
 *  |     | C |  D |  3 |
 *  ---------------------
 * @see https://github.com/PHPOffice/PHPWord/issues/806
 */

$section->addPageBreak();
$section->addText('Table with colspan and rowspan', $header);

$styleTable = array('borderSize' => 6, 'borderColor' => new Hex('999999'));
$phpWord->addTableStyle('Colspan Rowspan', $styleTable);
$table = $section->addTable('Colspan Rowspan');

$row = $table->addRow();
$row->addCell(Absolute::from('twip', 1000), array('vMerge' => 'restart'))->addText('A');
$row->addCell(Absolute::from('twip', 1000), array('gridSpan' => 2, 'vMerge' => 'restart'))->addText('B');
$row->addCell(Absolute::from('twip', 1000))->addText('1');

$row = $table->addRow();
$row->addCell(Absolute::from('twip', 1000), array('vMerge' => 'continue'));
$row->addCell(Absolute::from('twip', 1000), array('vMerge' => 'continue', 'gridSpan' => 2));
$row->addCell(Absolute::from('twip', 1000))->addText('2');

$row = $table->addRow();
$row->addCell(Absolute::from('twip', 1000), array('vMerge' => 'continue'));
$row->addCell(Absolute::from('twip', 1000))->addText('C');
$row->addCell(Absolute::from('twip', 1000))->addText('D');
$row->addCell(Absolute::from('twip', 1000))->addText('3');

// 5. Nested table

$section->addTextBreak(2);
$section->addText('Nested table in a centered and 50% width table.', $header);

$table = $section->addTable(array('width' => new Percent(50), 'alignment' => JcTable::CENTER));
$cell = $table->addRow()->addCell();
$cell->addText('This cell contains nested table.');
$innerCell = $cell->addTable(array('alignment' => JcTable::CENTER))->addRow()->addCell();
$innerCell->addText('Inside nested table');

// 6. Table with floating position

$section->addTextBreak(2);
$section->addText('Table with floating positioning.', $header);

$table = $section->addTable(array('borderSize' => Absolute::from('twip', 6), 'borderColor' => new Hex('999999'), 'position' => array('vertAnchor' => TablePosition::VANCHOR_TEXT, 'bottomFromText' => Absolute::from('cm', 1))));
$cell = $table->addRow()->addCell();
$cell->addText('This is a single cell.');

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
