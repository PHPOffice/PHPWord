<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// Begin code
$section = $phpWord->addSection();

// Style definition

$phpWord->addFontStyle('myOwnStyle', array('color' => 'FF0000'));
$phpWord->addParagraphStyle('P-Style', array('spaceAfter' => 95));
$phpWord->addNumberingStyle(
    'multilevel',
    array(
        'type'   => 'multilevel',
        'levels' => array(
            array('format' => 'decimal', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360),
            array('format' => 'upperLetter', 'text' => '%2.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720),
        ),
    )
);
$predefinedMultilevel = array('listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER_NESTED);

// Lists

$section->addText(htmlspecialchars('Multilevel list.'));
$section->addListItem(htmlspecialchars('List Item I'), 0, null, 'multilevel');
$section->addListItem(htmlspecialchars('List Item I.a'), 1, null, 'multilevel');
$section->addListItem(htmlspecialchars('List Item I.b'), 1, null, 'multilevel');
$section->addListItem(htmlspecialchars('List Item II'), 0, null, 'multilevel');
$section->addListItem(htmlspecialchars('List Item II.a'), 1, null, 'multilevel');
$section->addListItem(htmlspecialchars('List Item III'), 0, null, 'multilevel');
$section->addTextBreak(2);

$section->addText(htmlspecialchars('Basic simple bulleted list.'));
$section->addListItem(htmlspecialchars('List Item 1'));
$section->addListItem(htmlspecialchars('List Item 2'));
$section->addListItem(htmlspecialchars('List Item 3'));
$section->addTextBreak(2);

$section->addText(htmlspecialchars('Continue from multilevel list above.'));
$section->addListItem(htmlspecialchars('List Item IV'), 0, null, 'multilevel');
$section->addListItem(htmlspecialchars('List Item IV.a'), 1, null, 'multilevel');
$section->addTextBreak(2);

$section->addText(htmlspecialchars('Multilevel predefined list.'));
$section->addListItem(htmlspecialchars('List Item 1'), 0, 'myOwnStyle', $predefinedMultilevel, 'P-Style');
$section->addListItem(htmlspecialchars('List Item 2'), 0, 'myOwnStyle', $predefinedMultilevel, 'P-Style');
$section->addListItem(htmlspecialchars('List Item 3'), 1, 'myOwnStyle', $predefinedMultilevel, 'P-Style');
$section->addListItem(htmlspecialchars('List Item 4'), 1, 'myOwnStyle', $predefinedMultilevel, 'P-Style');
$section->addListItem(htmlspecialchars('List Item 5'), 2, 'myOwnStyle', $predefinedMultilevel, 'P-Style');
$section->addListItem(htmlspecialchars('List Item 6'), 1, 'myOwnStyle', $predefinedMultilevel, 'P-Style');
$section->addListItem(htmlspecialchars('List Item 7'), 0, 'myOwnStyle', $predefinedMultilevel, 'P-Style');
$section->addTextBreak(2);

$section->addText(htmlspecialchars('List with inline formatting.'));
$listItemRun = $section->addListItemRun();
$listItemRun->addText(htmlspecialchars('List item 1'));
$listItemRun->addText(htmlspecialchars(' in bold'), array('bold' => true));
$listItemRun = $section->addListItemRun();
$listItemRun->addText(htmlspecialchars('List item 2'));
$listItemRun->addText(htmlspecialchars(' in italic'), array('italic' => true));
$listItemRun = $section->addListItemRun();
$listItemRun->addText(htmlspecialchars('List item 3'));
$listItemRun->addText(htmlspecialchars(' underlined'), array('underline' => 'dash'));
$section->addTextBreak(2);

// Numbered heading

$phpWord->addNumberingStyle(
    'headingNumbering',
    array('type'   => 'multilevel',
          'levels' => array(
              array('pStyle' => 'Heading1', 'format' => 'decimal', 'text' => '%1'),
              array('pStyle' => 'Heading2', 'format' => 'decimal', 'text' => '%1.%2'),
              array('pStyle' => 'Heading3', 'format' => 'decimal', 'text' => '%1.%2.%3'),
          ),
    )
);
$phpWord->addTitleStyle(1, array('size' => 16), array('numStyle' => 'headingNumbering', 'numLevel' => 0));
$phpWord->addTitleStyle(2, array('size' => 14), array('numStyle' => 'headingNumbering', 'numLevel' => 1));
$phpWord->addTitleStyle(3, array('size' => 12), array('numStyle' => 'headingNumbering', 'numLevel' => 2));

$section->addTitle(htmlspecialchars('Heading 1'), 1);
$section->addTitle(htmlspecialchars('Heading 2'), 2);
$section->addTitle(htmlspecialchars('Heading 3'), 3);

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
