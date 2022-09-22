<?php

include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// New section
$section = $phpWord->addSection();

$section->addText('By default, when you insert an image, it adds a textbreak after its content.');
$section->addText('If we want a simple border around an image, we wrap the image inside a table->row->cell');
$section->addText(
    'On the image with the red border, even if we set the row height to the height of the image, '
        . 'the textbreak is still there:'
);

$table1 = $section->addTable(['cellMargin' => 0, 'cellMarginRight' => 0, 'cellMarginBottom' => 0, 'cellMarginLeft' => 0]);
$table1->addRow(3750);
$cell1 = $table1->addCell(null, ['valign' => 'top', 'borderSize' => 30, 'borderColor' => 'ff0000']);
$cell1->addImage('./resources/_earth.jpg', ['width' => 250, 'height' => 250, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

$section->addTextBreak();
$section->addText("But if we set the rowStyle 'exactHeight' to true, the real row height is used, removing the textbreak:");

$table2 = $section->addTable(
    [
        'cellMargin' => 0,
        'cellMarginRight' => 0,
        'cellMarginBottom' => 0,
        'cellMarginLeft' => 0,
    ]
);
$table2->addRow(3750, ['exactHeight' => true]);
$cell2 = $table2->addCell(null, ['valign' => 'top', 'borderSize' => 30, 'borderColor' => '00ff00']);
$cell2->addImage('./resources/_earth.jpg', ['width' => 250, 'height' => 250, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

$section->addTextBreak();
$section->addText('In this example, image is 250px height. Rows are calculated in twips, and 1px = 15twips.');
$section->addText('So: $' . "table2->addRow(3750, array('exactHeight'=>true));");

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
