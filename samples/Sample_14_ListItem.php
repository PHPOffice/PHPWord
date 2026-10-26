<?php

include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new PhpOffice\PhpWord\PhpWord();

// Define styles
$fontStyleName = 'myOwnStyle';
$phpWord->addFontStyle($fontStyleName, ['color' => 'FF0000']);

$paragraphStyleName = 'P-Style';
$phpWord->addParagraphStyle($paragraphStyleName, ['spaceAfter' => 95]);

$multilevelNumberingStyleName = 'multilevel';
$phpWord->addNumberingStyle(
    $multilevelNumberingStyleName,
    [
        'type' => 'multilevel',
        'levels' => [
            ['format' => 'decimal', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360],
            ['format' => 'upperLetter', 'text' => '%2.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720],
        ],
    ]
);

$predefinedMultilevelStyle = ['listType' => PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER_NESTED];

// New section
$section = $phpWord->addSection();

// Lists
$section->addText('Multilevel list.');
$section->addListItem('List Item I', 0, null, $multilevelNumberingStyleName);
$section->addListItem('List Item I.a', 1, null, $multilevelNumberingStyleName);
$section->addListItem('List Item I.b', 1, null, $multilevelNumberingStyleName);
$section->addListItem('List Item II', 0, null, $multilevelNumberingStyleName);
$section->addListItem('List Item II.a', 1, null, $multilevelNumberingStyleName);
$section->addListItem('List Item III', 0, null, $multilevelNumberingStyleName);
$section->addTextBreak(2);

$section->addText('Basic simple bulleted list.');
$section->addListItem('List Item 1');
$section->addListItem('List Item 2');
$section->addListItem('List Item 3');
$section->addTextBreak(2);

$section->addText('Continue from multilevel list above.');
$section->addListItem('List Item IV', 0, null, $multilevelNumberingStyleName);
$section->addListItem('List Item IV.a', 1, null, $multilevelNumberingStyleName);
$section->addTextBreak(2);

$section->addText('Multilevel predefined list.');
$section->addListItem('List Item 1', 0, $fontStyleName, $predefinedMultilevelStyle, $paragraphStyleName);
$section->addListItem('List Item 2', 0, $fontStyleName, $predefinedMultilevelStyle, $paragraphStyleName);
$section->addListItem('List Item 3', 1, $fontStyleName, $predefinedMultilevelStyle, $paragraphStyleName);
$section->addListItem('List Item 4', 1, $fontStyleName, $predefinedMultilevelStyle, $paragraphStyleName);
$section->addListItem('List Item 5', 2, $fontStyleName, $predefinedMultilevelStyle, $paragraphStyleName);
$section->addListItem('List Item 6', 1, $fontStyleName, $predefinedMultilevelStyle, $paragraphStyleName);
$section->addListItem('List Item 7', 0, $fontStyleName, $predefinedMultilevelStyle, $paragraphStyleName);
$section->addTextBreak(2);

$section->addText('List with inline formatting.');
$listItemRun = $section->addListItemRun();
$listItemRun->addText('List item 1');
$listItemRun->addText(' in bold', ['bold' => true]);
$listItemRun = $section->addListItemRun(1, $predefinedMultilevelStyle, $paragraphStyleName);
$listItemRun->addText('List item 2');
$listItemRun->addText(' in italic', ['italic' => true]);
$footnote = $listItemRun->addFootnote();
$footnote->addText('this is a footnote on a list item');
$listItemRun = $section->addListItemRun();
$listItemRun->addText('List item 3');
$listItemRun->addText(' underlined', ['underline' => 'dash']);
$section->addTextBreak(2);

// Numbered heading
$headingNumberingStyleName = 'headingNumbering';
$phpWord->addNumberingStyle(
    $headingNumberingStyleName,
    ['type' => 'multilevel',
        'levels' => [
            ['pStyle' => 'Heading1', 'format' => 'decimal', 'text' => '%1'],
            ['pStyle' => 'Heading2', 'format' => 'decimal', 'text' => '%1.%2'],
            ['pStyle' => 'Heading3', 'format' => 'decimal', 'text' => '%1.%2.%3'],
        ],
    ]
);
$phpWord->addTitleStyle(1, ['size' => 16], ['numStyle' => $headingNumberingStyleName, 'numLevel' => 0]);
$phpWord->addTitleStyle(2, ['size' => 14], ['numStyle' => $headingNumberingStyleName, 'numLevel' => 1]);
$phpWord->addTitleStyle(3, ['size' => 12], ['numStyle' => $headingNumberingStyleName, 'numLevel' => 2]);

$section->addTitle('Heading 1', 1);
$section->addTitle('Heading 2', 2);
$section->addTitle('Heading 3', 3);

$html = '
<p>Loading from html, including type on nested list.</p>
<ol>
<li>Items in the first level of the list
<ol type="i">
<li>Sub-item 1 (lcroman)</li>
</ol>
</li>
<li>&nbsp;Another item at the first level</li>
<li>Yet another item at the first level</li>
<ol>
<li>Sub-item 1 (lcalpha)</li>
<li>Sub-item 2</li>
</ol>
</ol>
<p>End of html load.</p>
';
PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, false, false);
$section->addText('Testing setNumId for new numbering start (there are better ways to do this).');
$predefinedStyle = ['listType' => PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER];
$section->addText('First List');
$section->addListItem('First List Item 1', 0, null, $predefinedStyle);
$section->addListItem('First List Item 2', 0, null, $predefinedStyle);
$section->addText('Second List');
$temp = $section->addListItem('Second List Item 1', 0, null, $predefinedStyle);
$temp->getStyle()->setNumId(0);
$section->addText('End of setNumId test.');

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
