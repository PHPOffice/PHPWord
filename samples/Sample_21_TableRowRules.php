<?php
// Init
error_reporting(E_ALL);
define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
require_once '../Classes/PHPWord.php';

// New Word Document
echo date('H:i:s') , ' Create new PHPWord object' , EOL;
$PHPWord = new PHPWord();
$section = $PHPWord->createSection();

$section->addText("By default, when you insert an image, it adds a textbreak after its content.");
$section->addText("If we want a simple border around an image, we wrap the image inside a table->row->cell");
$section->addText("On the image with the red border, even if we set the row height to the height of the image, the textbreak is still there:");

$table1 = $section->addTable(array("cellMargin"=> 0, "cellMarginRight"=> 0, "cellMarginBottom"=> 0, "cellMarginLeft"=> 0));
$table1->addRow(3750);
$cell1 = $table1->addCell(null, array("valign" => "top", "borderSize" => 30, "borderColor" => "ff0000"));
$cell1->addImage("./resources/_earth.jpg", array("width" => 250, "height" => 250, "align" => "center"));

$section->addTextBreak();
$section->addText("But if we set the rowStyle 'exactHeight' to true, the real row height is used, removing the textbreak:");

$table2 = $section->addTable(array("cellMargin"=> 0, "cellMarginRight"=> 0, "cellMarginBottom"=> 0, "cellMarginLeft"=> 0));
$table2->addRow(3750, array("exactHeight"=>));
$cell2 = $table2->addCell(null, array("valign" => "top", "borderSize" => 30, "borderColor" => "00ff00"));
$cell2->addImage("./resources/_earth.jpg", array("width" => 250, "height" => 250, "align" => "center"));

$section->addTextBreak();
$section->addText("In this example, image is 250px height. Rows are calculated in twips, and 1px = 15twips.");
$section->addText("So: $"."table2->addRow(3750, array('exactHeight'=>true));");

// Save file
$name = basename(__FILE__, '.php');
$writers = array('Word2007' => 'docx', 'ODText' => 'odt', 'RTF' => 'rtf');
foreach ($writers as $writer => $extension) {
    echo date('H:i:s'), " Write to {$writer} format", EOL;
    $objWriter = PHPWord_IOFactory::createWriter($PHPWord, $writer);
    $objWriter->save("{$name}.{$extension}");
    rename("{$name}.{$extension}", "results/{$name}.{$extension}");
}


// Done
echo date('H:i:s'), " Done writing file(s)", EOL;
echo date('H:i:s'), " Peak memory usage: ", (memory_get_peak_usage(true) / 1024 / 1024), " MB", EOL;
