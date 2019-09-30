<?php
declare(strict_types=1);
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Colors\Hex;
use PhpOffice\PhpWord\Style\Lengths\Absolute;

include_once 'Sample_Header.php';

// New Word document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new PhpWord();
$phpWord->getSettings()->setUpdateFields(true);

// New section
$section = $phpWord->addSection();

// Define styles
$fontStyle12 = array('spaceAfter' => Absolute::from('twip', 60), 'size' => Absolute::from('pt', 12));
$fontStyle10 = array('size' => Absolute::from('pt', 10));
$phpWord->addTitleStyle(null, array('size' => Absolute::from('pt', 22), 'bold' => true));
$phpWord->addTitleStyle(1, array('size' => Absolute::from('pt', 20), 'color' => new Hex('333333'), 'bold' => true));
$phpWord->addTitleStyle(2, array('size' => Absolute::from('pt', 16), 'color' => new Hex('666666')));
$phpWord->addTitleStyle(3, array('size' => Absolute::from('pt', 14), 'italic' => true));
$phpWord->addTitleStyle(4, array('size' => Absolute::from('pt', 12)));

// Add text elements
$section->addTitle('Table of contents 1', 0);
$section->addTextBreak(2);

// Add TOC #1
$toc = $section->addTOC($fontStyle12);
$section->addTextBreak(2);

// Filler
$section->addText('Text between TOC');
$section->addTextBreak(2);

// Add TOC #1
$section->addText('Table of contents 2');
$section->addTextBreak(2);
$toc2 = $section->addTOC($fontStyle10);
$toc2->setMinDepth(2);
$toc2->setMaxDepth(3);

// Add Titles
$section->addPageBreak();
$section->addTitle('Foo n Bar', 1);
$section->addText('Some text...');
$section->addTextBreak(2);

$section->addTitle('I am a Subtitle of Title 1', 2);
$section->addTextBreak(2);
$section->addText('Some more text...');
$section->addTextBreak(2);

$section->addTitle('Another Title (Title 2)', 1);
$section->addText('Some text...');
$section->addPageBreak();
$section->addTitle('I am Title 3', 1);
$section->addText('And more text...');
$section->addTextBreak(2);
$section->addTitle('I am a Subtitle of Title 3', 2);
$section->addText('Again and again, more text...');
$section->addTitle('Subtitle 3.1.1', 3);
$section->addText('Text');
$section->addTitle('Subtitle 3.1.1.1', 4);
$section->addText('Text');
$section->addTitle('Subtitle 3.1.1.2', 4);
$section->addText('Text');
$section->addTitle('Subtitle 3.1.2', 3);
$section->addText('Text');

echo date('H:i:s'), ' Note: Please refresh TOC manually.', EOL;

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
