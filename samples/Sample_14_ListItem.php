<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), " Create new PhpWord object", EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// Begin code
$section = $phpWord->addSection();

// Style definition

$phpWord->addFontStyle('myOwnStyle', array('color'=>'FF0000'));
$phpWord->addParagraphStyle('P-Style', array('spaceAfter'=>95));
$phpWord->addNumberingStyle(
    'multilevel',
    array('type' => 'multilevel', 'levels' => array(
        array('format' => 'decimal', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360),
        array('format' => 'upperLetter', 'text' => '%2.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720),
        )
     )
);
$predefinedMultilevel = array('listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER_NESTED);

// Lists

$section->addText('Multilevel list.');
$section->addListItem(0, null, 'multilevel')->addText('List Item I');
$section->addListItem(1, null, 'multilevel')->addText('List Item I.a');
$section->addListItem(1, null, 'multilevel')->addText('List Item I.b');
$section->addListItem(0, null, 'multilevel')->addText('List Item II');
$section->addListItem(1, null, 'multilevel')->addText('List Item II.a');
$section->addListItem(0, null, 'multilevel')->addText('List Item III');
$section->addTextBreak(2);

$section->addText('Basic simple bulleted list.');
$section->addListItem()->addText('List Item 1');
$section->addListItem()->addText('List Item 2');
$section->addListItem()->addText('List Item 3');
$section->addTextBreak(2);

$section->addText('Continue from multilevel list above.');
$section->addListItem(0, null, 'multilevel')->addText('List Item IV');
$section->addListItem(1, null, 'multilevel')->addText('List Item IV.a');
$section->addTextBreak(2);

$section->addText('Multilevel predefined list.');
$section->addListItem(0, 'myOwnStyle', $predefinedMultilevel, 'P-Style')->addText('List Item 1');
$section->addListItem(0, 'myOwnStyle', $predefinedMultilevel, 'P-Style')->addText('List Item 2');
$section->addListItem(1, 'myOwnStyle', $predefinedMultilevel, 'P-Style')->addText('List Item 3');
$section->addListItem(1, 'myOwnStyle', $predefinedMultilevel, 'P-Style')->addText('List Item 4');
$section->addListItem(2, 'myOwnStyle', $predefinedMultilevel, 'P-Style')->addText('List Item 5');
$section->addListItem(1, 'myOwnStyle', $predefinedMultilevel, 'P-Style')->addText('List Item 6');
$section->addListItem(0, 'myOwnStyle', $predefinedMultilevel, 'P-Style')->addText('List Item 7');
$section->addTextBreak(2);

$section->addText('Listitems with inline formatting');
$listItemObject = $section->addListItem();
$listItemObject->addText('Testing');
$listItemObject->addText(' Strong', array('bold'=>true));
$listItemObject = $section->addListItem();
$listItemObject->addText('Testing 2');
$listItemObject->addText(' Italic and stron', array('bold'=>true, 'italic'=>true));

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
