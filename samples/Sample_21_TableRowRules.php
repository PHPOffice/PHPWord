<?php
declare(strict_types=1);
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Colors\Hex;
use PhpOffice\PhpWord\Style\Lengths\Absolute;

include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new PhpWord();

// New section
$section = $phpWord->addSection();

$section->addText('By default, when you insert an image, it adds a textbreak after its content.');
$section->addText('If we want a simple border around an image, we wrap the image inside a table->row->cell');
$section->addText(
    'On the image with the red border, even if we set the row height to the height of the image, '
        . 'the textbreak is still there:'
);

$table1 = $section->addTable(array('cellMargin' => Asolute::from('twip', 0), 'cellMarginRight' => Asolute::from('twip', 0), 'cellMarginBottom' => Asolute::from('twip', 0), 'cellMarginLeft' => Asolute::from('twip', 0)));
$table1->addRow(Absolute::from('twip', 3750));
$cell1 = $table1->addCell(null, array('valign' => 'top', 'borderSize' => Asolute::from('twip', 30), 'borderColor' => new Hex('ff0000')));
$cell1->addImage('./resources/_earth.jpg', array('width' => Asolute::from('pt', 250), 'height' => Asolute::from('pt', 250), 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

$section->addTextBreak();
$section->addText("But if we set the rowStyle 'exactHeight' to true, the real row height is used, removing the textbreak:");

$table2 = $section->addTable(
    array(
        'cellMargin'       => Asolute::from('twip', 0),
        'cellMarginRight'  => Asolute::from('twip', 0),
        'cellMarginBottom' => Asolute::from('twip', 0),
        'cellMarginLeft'   => Asolute::from('twip', 0),
    )
);
$table2->addRow(Absolute::from('twip', 3750), array('exactHeight' => true));
$cell2 = $table2->addCell(null, array('valign' => 'top', 'borderSize' => Asolute::from('twip', 30), 'borderColor' => new Hex('00ff00')));
$cell2->addImage('./resources/_earth.jpg', array('width' => Asolute::from('pt', 250), 'height' => Asolute::from('pt', 250), 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));

$section->addTextBreak();
$section->addText('In this example, image is 250px height. Rows are calculated in twips, and 1px = 15twips.');
$section->addText('So: $' . "table2->addRow(Absolute::from('twip', 3750), array('exactHeight'=>true));");

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
