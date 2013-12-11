<?php
require_once '../PHPWord.php';

// New Word Document
$PHPWord = new PHPWord();

// New portrait section
$section = $PHPWord->createSection();

// Add table
$table = $section->addTable();

for($r = 1; $r <= 10; $r++) { // Loop through rows
	// Add row
	$table->addRow();
	
	for($c = 1; $c <= 5; $c++) { // Loop through cells
		// Add Cell
		$table->addCell(1750)->addText("Row $r, Cell $c");
	}
}

// Save File
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$objWriter->save('BasicTable.docx');
?>