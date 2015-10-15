<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$section = $phpWord->addSection();

$section->addText(htmlspecialchars('By default, when you insert an image, it adds a textbreak after its content.', ENT_COMPAT, 'UTF-8'));
$section->addText(
    htmlspecialchars('If we want a simple border around an image, we wrap the image inside a table->row->cell', ENT_COMPAT, 'UTF-8')
);
$section->addText(
    htmlspecialchars(
        'On the image with the red border, even if we set the row height to the height of the image, '
            . 'the textbreak is still there:',
        ENT_COMPAT,
        'UTF-8'
    )
);

$table1 = $section->addTable(array('cellMargin' => 0, 'cellMarginRight' => 0, 'cellMarginBottom' => 0, 'cellMarginLeft' => 0));
$table1->addRow(3750);
$cell1 = $table1->addCell(null, array('valign' => 'top', 'borderSize' => 30, 'borderColor' => 'ff0000'));
$cell1->addImage('./resources/_earth.jpg', array('width' => 250, 'height' => 250, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

$section->addTextBreak();
$section->addText(
    htmlspecialchars(
        "But if we set the rowStyle 'exactHeight' to true, the real row height is used, removing the textbreak:",
        ENT_COMPAT,
        'UTF-8'
    )
);

$table2 = $section->addTable(
    array(
        'cellMargin'       => 0,
        'cellMarginRight'  => 0,
        'cellMarginBottom' => 0,
        'cellMarginLeft'   => 0,
    )
);
$table2->addRow(3750, array('exactHeight' => true));
$cell2 = $table2->addCell(null, array('valign' => 'top', 'borderSize' => 30, 'borderColor' => '00ff00'));
$cell2->addImage('./resources/_earth.jpg', array('width' => 250, 'height' => 250, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

$section->addTextBreak();
$section->addText(
    htmlspecialchars('In this example, image is 250px height. Rows are calculated in twips, and 1px = 15twips.', ENT_COMPAT, 'UTF-8')
);
$section->addText(htmlspecialchars('So: $' . "table2->addRow(3750, array('exactHeight'=>true));", ENT_COMPAT, 'UTF-8'));

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
