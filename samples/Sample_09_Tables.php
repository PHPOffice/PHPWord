<?php
include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$section = $phpWord->addSection();
$header = array('size' => 16, 'bold' => true);

// 1. Basic table

$rows = 10;
$cols = 5;
$section->addText(htmlspecialchars('Basic table', ENT_COMPAT, 'UTF-8'), $header);

$table = $section->addTable();
for ($r = 1; $r <= 8; $r++) {
    $table->addRow();
    for ($c = 1; $c <= 5; $c++) {
        $table->addCell(1750)->addText(htmlspecialchars("Row {$r}, Cell {$c}", ENT_COMPAT, 'UTF-8'));
    }
}

// 2. Advanced table

$section->addTextBreak(1);
$section->addText(htmlspecialchars('Fancy table', ENT_COMPAT, 'UTF-8'), $header);

$styleTable = array('borderSize' => 6, 'borderColor' => '006699', 'cellMargin' => 80, 'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER);
$styleFirstRow = array('borderBottomSize' => 18, 'borderBottomColor' => '0000FF', 'bgColor' => '66BBFF');
$styleCell = array('valign' => 'center');
$styleCellBTLR = array('valign' => 'center', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR);
$fontStyle = array('bold' => true);
$phpWord->addTableStyle('Fancy Table', $styleTable, $styleFirstRow);
$table = $section->addTable('Fancy Table');
$table->addRow(900);
$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Row 1', ENT_COMPAT, 'UTF-8'), $fontStyle);
$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Row 2', ENT_COMPAT, 'UTF-8'), $fontStyle);
$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Row 3', ENT_COMPAT, 'UTF-8'), $fontStyle);
$table->addCell(2000, $styleCell)->addText(htmlspecialchars('Row 4', ENT_COMPAT, 'UTF-8'), $fontStyle);
$table->addCell(500, $styleCellBTLR)->addText(htmlspecialchars('Row 5', ENT_COMPAT, 'UTF-8'), $fontStyle);
for ($i = 1; $i <= 8; $i++) {
    $table->addRow();
    $table->addCell(2000)->addText(htmlspecialchars("Cell {$i}", ENT_COMPAT, 'UTF-8'));
    $table->addCell(2000)->addText(htmlspecialchars("Cell {$i}", ENT_COMPAT, 'UTF-8'));
    $table->addCell(2000)->addText(htmlspecialchars("Cell {$i}", ENT_COMPAT, 'UTF-8'));
    $table->addCell(2000)->addText(htmlspecialchars("Cell {$i}", ENT_COMPAT, 'UTF-8'));
    $text = (0== $i % 2) ? 'X' : '';
    $table->addCell(500)->addText(htmlspecialchars($text, ENT_COMPAT, 'UTF-8'));
}

/**
 *  3. colspan (gridSpan) and rowspan (vMerge)
 *  ---------------------
 *  |     |   B    |    |
 *  |  A  |--------|  E |
 *  |     | C |  D |    |
 *  ---------------------
 */

$section->addPageBreak();
$section->addText(htmlspecialchars('Table with colspan and rowspan', ENT_COMPAT, 'UTF-8'), $header);

$styleTable = array('borderSize' => 6, 'borderColor' => '999999');
$cellRowSpan = array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => 'FFFF00');
$cellRowContinue = array('vMerge' => 'continue');
$cellColSpan = array('gridSpan' => 2, 'valign' => 'center');
$cellHCentered = array('alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER);
$cellVCentered = array('valign' => 'center');

$phpWord->addTableStyle('Colspan Rowspan', $styleTable);
$table = $section->addTable('Colspan Rowspan');

$table->addRow();

$cell1 = $table->addCell(2000, $cellRowSpan);
$textrun1 = $cell1->addTextRun($cellHCentered);
$textrun1->addText(htmlspecialchars('A', ENT_COMPAT, 'UTF-8'));
$textrun1->addFootnote()->addText(htmlspecialchars('Row span', ENT_COMPAT, 'UTF-8'));

$cell2 = $table->addCell(4000, $cellColSpan);
$textrun2 = $cell2->addTextRun($cellHCentered);
$textrun2->addText(htmlspecialchars('B', ENT_COMPAT, 'UTF-8'));
$textrun2->addFootnote()->addText(htmlspecialchars('Colspan span', ENT_COMPAT, 'UTF-8'));

$table->addCell(2000, $cellRowSpan)->addText(htmlspecialchars('E', ENT_COMPAT, 'UTF-8'), null, $cellHCentered);

$table->addRow();
$table->addCell(null, $cellRowContinue);
$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('C', ENT_COMPAT, 'UTF-8'), null, $cellHCentered);
$table->addCell(2000, $cellVCentered)->addText(htmlspecialchars('D', ENT_COMPAT, 'UTF-8'), null, $cellHCentered);
$table->addCell(null, $cellRowContinue);

/**
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
$section->addText(htmlspecialchars('Table with colspan and rowspan', ENT_COMPAT, 'UTF-8'), $header);

$styleTable = ['borderSize' => 6, 'borderColor' => '999999'];
$phpWord->addTableStyle('Colspan Rowspan', $styleTable);
$table = $section->addTable('Colspan Rowspan');

$row = $table->addRow();

$row->addCell(null, ['vMerge' => 'restart'])->addText('A');
$row->addCell(null, ['gridSpan' => 2, 'vMerge' => 'restart',])->addText('B');
$row->addCell()->addText('1');

$row = $table->addRow();
$row->addCell(null, ['vMerge' => 'continue']);
$row->addCell(null, ['vMerge' => 'continue','gridSpan' => 2,]);
$row->addCell()->addText('2');
$row = $table->addRow();
$row->addCell(null, ['vMerge' => 'continue']);
$row->addCell()->addText('C');
$row->addCell()->addText('D');
$row->addCell()->addText('3');

// 5. Nested table

$section->addTextBreak(2);
$section->addText(htmlspecialchars('Nested table in a centered and 50% width table.', ENT_COMPAT, 'UTF-8'), $header);

$table = $section->addTable(array('width' => 50 * 50, 'unit' => 'pct', 'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER));
$cell = $table->addRow()->addCell();
$cell->addText(htmlspecialchars('This cell contains nested table.', ENT_COMPAT, 'UTF-8'));
$innerCell = $cell->addTable(array('alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER))->addRow()->addCell();
$innerCell->addText(htmlspecialchars('Inside nested table', ENT_COMPAT, 'UTF-8'));

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
