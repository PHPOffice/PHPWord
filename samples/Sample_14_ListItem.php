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
$section->addListItem('List Item I', 0, null, 'multilevel');
$section->addListItem('List Item I.a', 1, null, 'multilevel');
$section->addListItem('List Item I.b', 1, null, 'multilevel');
$section->addListItem('List Item II', 0, null, 'multilevel');
$section->addListItem('List Item II.a', 1, null, 'multilevel');
$section->addListItem('List Item III', 0, null, 'multilevel');
$section->addTextBreak(2);

$section->addText('Basic simple bulleted list.');
$section->addListItem('List Item 1');
$section->addListItem('List Item 2');
$section->addListItem('List Item 3');
$section->addTextBreak(2);

$section->addText('Continue from multilevel list above.');
$section->addListItem('List Item IV', 0, null, 'multilevel');
$section->addListItem('List Item IV.a', 1, null, 'multilevel');
$section->addTextBreak(2);

$section->addText('Multilevel predefined list.');
$section->addListItem('List Item 1', 0, 'myOwnStyle', $predefinedMultilevel, 'P-Style');
$section->addListItem('List Item 2', 0, 'myOwnStyle', $predefinedMultilevel, 'P-Style');
$section->addListItem('List Item 3', 1, 'myOwnStyle', $predefinedMultilevel, 'P-Style');
$section->addListItem('List Item 4', 1, 'myOwnStyle', $predefinedMultilevel, 'P-Style');
$section->addListItem('List Item 5', 2, 'myOwnStyle', $predefinedMultilevel, 'P-Style');
$section->addListItem('List Item 6', 1, 'myOwnStyle', $predefinedMultilevel, 'P-Style');
$section->addListItem('List Item 7', 0, 'myOwnStyle', $predefinedMultilevel, 'P-Style');
$section->addTextBreak(2);

$section->addText('List with inline formatting.');
$listItemRun = $section->addListItemRun();
$listItemRun->addText('List item 1');
$listItemRun->addText(' in bold', array('bold'=>true));
$listItemRun = $section->addListItemRun();
$listItemRun->addText('List item 2');
$listItemRun->addText(' in italic', array('italic'=>true));
$listItemRun = $section->addListItemRun();
$listItemRun->addText('List item 3');
$listItemRun->addText(' underlined', array('underline'=>'dash'));
$section->addTextBreak(2);

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
