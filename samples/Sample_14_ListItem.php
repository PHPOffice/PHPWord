<?php
/**
 * List item sample
 */

// Init
error_reporting(E_ALL);
define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
require_once '../Classes/PHPWord.php';

// New Word document
echo date('H:i:s'), " Create new PHPWord object", EOL;
$PHPWord = new PHPWord();

// Begin code
$section = $PHPWord->createSection();

// Add listitem elements
$section->addListItem('List Item 1', 0);
$section->addListItem('List Item 2', 0);
$section->addListItem('List Item 3', 0);
$section->addTextBreak(2);

// Add listitem elements
$section->addListItem('List Item 1', 0);
$section->addListItem('List Item 1.1', 1);
$section->addListItem('List Item 1.2', 1);
$section->addListItem('List Item 1.3 (styled)', 1, array('bold'=>true));
$section->addListItem('List Item 1.3.1', 2);
$section->addListItem('List Item 1.3.2', 2);
$section->addTextBreak(2);

// Add listitem elements
$listStyle = array('listType'=>PHPWord_Style_ListItem::TYPE_NUMBER);
$section->addListItem('List Item 1', 0, null, $listStyle);
$section->addListItem('List Item 2', 0, null, $listStyle);
$section->addListItem('List Item 3', 0, null, $listStyle);
$section->addTextBreak(2);

// Add listitem elements
$PHPWord->addFontStyle('myOwnStyle', array('color'=>'FF0000'));
$PHPWord->addParagraphStyle('P-Style', array('spaceAfter'=>95));
$listStyle = array('listType'=>PHPWord_Style_ListItem::TYPE_NUMBER_NESTED);
$section->addListItem('List Item 1', 0, 'myOwnStyle', $listStyle, 'P-Style');
$section->addListItem('List Item 2', 0, 'myOwnStyle', $listStyle, 'P-Style');
$section->addListItem('List Item 3', 1, 'myOwnStyle', $listStyle, 'P-Style');
$section->addListItem('List Item 4', 1, 'myOwnStyle', $listStyle, 'P-Style');
$section->addListItem('List Item 5', 2, 'myOwnStyle', $listStyle, 'P-Style');
$section->addListItem('List Item 6', 1, 'myOwnStyle', $listStyle, 'P-Style');
$section->addListItem('List Item 7', 0, 'myOwnStyle', $listStyle, 'P-Style');

// End code

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
