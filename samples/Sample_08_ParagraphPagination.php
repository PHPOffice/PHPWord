<?php
include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$phpWord->setDefaultParagraphStyle(
    array(
        'align'      => 'both',
        'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(12),
        'spacing'    => 120,
    )
);

// Sample
$section = $phpWord->addSection();

$section->addText(
    htmlspecialchars(
        'Below are the samples on how to control your paragraph '
            . 'pagination. See "Line and Page Break" tab on paragraph properties '
            . 'window to see the attribute set by these controls.'
    ),
    array('bold' => true),
    array('space' => array('before' => 360, 'after' => 480))
);

$section->addText(
    htmlspecialchars(
        'Paragraph with widowControl = false (default: true). '
            . 'A "widow" is the last line of a paragraph printed by itself at the top '
            . 'of a page. An "orphan" is the first line of a paragraph printed by '
            . 'itself at the bottom of a page. Set this option to "false" if you want '
            . 'to disable this automatic control.'
    ),
    null,
    array('widowControl' => false, 'indentation' => array('left' => 240, 'right' => 120))
);

$section->addText(
    htmlspecialchars(
        'Paragraph with keepNext = true (default: false). '
            . '"Keep with next" is used to prevent Word from inserting automatic page '
            . 'breaks between paragraphs. Set this option to "true" if you do not want '
            . 'your paragraph to be separated with the next paragraph.'
    ),
    null,
    array('keepNext' => true, 'indentation' => array('firstLine' => 240))
);

$section->addText(
    htmlspecialchars(
        'Paragraph with keepLines = true (default: false). '
            . '"Keep lines together" will prevent Word from inserting an automatic page '
            . 'break within a paragraph. Set this option to "true" if you do not want '
            . 'all lines of your paragraph to be in the same page.'
    ),
    null,
    array('keepLines' => true, 'indentation' => array('left' => 240, 'hanging' => 240))
);

$section->addText(htmlspecialchars('Keep scrolling. More below.'));

$section->addText(
    htmlspecialchars(
        'Paragraph with pageBreakBefore = true (default: false). '
            . 'Different with all other control above, "page break before" separates '
            . 'your paragraph into the next page. This option is most useful for '
            . 'heading styles.'
    ),
    null,
    array('pageBreakBefore' => true)
);

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
