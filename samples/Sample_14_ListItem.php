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

$section->addText(htmlspecialchars('Multilevel list.', ENT_COMPAT, 'UTF-8'));
$section->addListItem(htmlspecialchars('List Item I', ENT_COMPAT, 'UTF-8'), 0, null, 'multilevel');
$section->addListItem(htmlspecialchars('List Item I.a', ENT_COMPAT, 'UTF-8'), 1, null, 'multilevel');
$section->addListItem(htmlspecialchars('List Item I.b', ENT_COMPAT, 'UTF-8'), 1, null, 'multilevel');
$section->addListItem(htmlspecialchars('List Item II', ENT_COMPAT, 'UTF-8'), 0, null, 'multilevel');
$section->addListItem(htmlspecialchars('List Item II.a', ENT_COMPAT, 'UTF-8'), 1, null, 'multilevel');
$section->addListItem(htmlspecialchars('List Item III', ENT_COMPAT, 'UTF-8'), 0, null, 'multilevel');
$section->addTextBreak(2);

$section->addText(htmlspecialchars('Basic simple bulleted list.', ENT_COMPAT, 'UTF-8'));
$section->addListItem(htmlspecialchars('List Item 1', ENT_COMPAT, 'UTF-8'));
$section->addListItem(htmlspecialchars('List Item 2', ENT_COMPAT, 'UTF-8'));
$section->addListItem(htmlspecialchars('List Item 3', ENT_COMPAT, 'UTF-8'));
$section->addTextBreak(2);

$section->addText(htmlspecialchars('Continue from multilevel list above.', ENT_COMPAT, 'UTF-8'));
$section->addListItem(htmlspecialchars('List Item IV', ENT_COMPAT, 'UTF-8'), 0, null, 'multilevel');
$section->addListItem(htmlspecialchars('List Item IV.a', ENT_COMPAT, 'UTF-8'), 1, null, 'multilevel');
$section->addTextBreak(2);

$section->addText(htmlspecialchars('Multilevel predefined list.', ENT_COMPAT, 'UTF-8'));
$section->addListItem(htmlspecialchars('List Item 1', ENT_COMPAT, 'UTF-8'), 0, 'myOwnStyle', $predefinedMultilevel, 'P-Style');
$section->addListItem(htmlspecialchars('List Item 2', ENT_COMPAT, 'UTF-8'), 0, 'myOwnStyle', $predefinedMultilevel, 'P-Style');
$section->addListItem(htmlspecialchars('List Item 3', ENT_COMPAT, 'UTF-8'), 1, 'myOwnStyle', $predefinedMultilevel, 'P-Style');
$section->addListItem(htmlspecialchars('List Item 4', ENT_COMPAT, 'UTF-8'), 1, 'myOwnStyle', $predefinedMultilevel, 'P-Style');
$section->addListItem(htmlspecialchars('List Item 5', ENT_COMPAT, 'UTF-8'), 2, 'myOwnStyle', $predefinedMultilevel, 'P-Style');
$section->addListItem(htmlspecialchars('List Item 6', ENT_COMPAT, 'UTF-8'), 1, 'myOwnStyle', $predefinedMultilevel, 'P-Style');
$section->addListItem(htmlspecialchars('List Item 7', ENT_COMPAT, 'UTF-8'), 0, 'myOwnStyle', $predefinedMultilevel, 'P-Style');
$section->addTextBreak(2);

$section->addText(htmlspecialchars('List with inline formatting.', ENT_COMPAT, 'UTF-8'));
$listItemRun = $section->addListItemRun();
$listItemRun->addText(htmlspecialchars('List item 1', ENT_COMPAT, 'UTF-8'));
$listItemRun->addText(htmlspecialchars(' in bold', ENT_COMPAT, 'UTF-8'), array('bold' => true));
$listItemRun = $section->addListItemRun();
$listItemRun->addText(htmlspecialchars('List item 2', ENT_COMPAT, 'UTF-8'));
$listItemRun->addText(htmlspecialchars(' in italic', ENT_COMPAT, 'UTF-8'), array('italic' => true));
$listItemRun = $section->addListItemRun();
$listItemRun->addText(htmlspecialchars('List item 3', ENT_COMPAT, 'UTF-8'));
$listItemRun->addText(htmlspecialchars(' underlined', ENT_COMPAT, 'UTF-8'), array('underline' => 'dash'));
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

$section->addTitle(htmlspecialchars('Heading 1', ENT_COMPAT, 'UTF-8'), 1);
$section->addTitle(htmlspecialchars('Heading 2', ENT_COMPAT, 'UTF-8'), 2);
$section->addTitle(htmlspecialchars('Heading 3', ENT_COMPAT, 'UTF-8'), 3);

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
