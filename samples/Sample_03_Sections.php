<?php
declare(strict_types=1);
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\VerticalJc;
use PhpOffice\PhpWord\Style\Colors\Hex;
use PhpOffice\PhpWord\Style\Lengths\Absolute;

include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new PhpWord();

// New portrait section
$section = $phpWord->addSection(array('borderColor' => new Hex('00FF00'), 'borderSize' => Absolute::from('twip', 12)));
$section->addText('I am placed on a default section.');

// New landscape section
$section = $phpWord->addSection(array('orientation' => 'landscape'));
$section->addText('I am placed on a landscape section. Every page starting from this section will be landscape style.');
$section->addPageBreak();
$section->addPageBreak();

// New portrait section
$section = $phpWord->addSection(
    array('paperSize' => 'Folio', 'marginLeft' => Absolute::from('twip', 600), 'marginRight' => Absolute::from('twip', 600), 'marginTop' => Absolute::from('twip', 600), 'marginBottom' => Absolute::from('twip', 600))
);
$section->addText('This section uses other margins with folio papersize.');

// The text of this section is vertically centered
$section = $phpWord->addSection(
    array('vAlign' => VerticalJc::CENTER)
);
$section->addText('This section is vertically centered.');

// New portrait section with Header & Footer
$section = $phpWord->addSection(
    array(
        'marginLeft'   => Absolute::from('twip', 200),
        'marginRight'  => Absolute::from('twip', 200),
        'marginTop'    => Absolute::from('twip', 200),
        'marginBottom' => Absolute::from('twip', 200),
        'headerHeight' => Absolute::from('twip', 50),
        'footerHeight' => Absolute::from('twip', 50),
    )
);
$section->addText('This section and we play with header/footer height.');
$section->addHeader()->addText('Header');
$section->addFooter()->addText('Footer');

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
