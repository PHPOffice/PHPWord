<?php
include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s'), ' Create new PhpWord object', EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// New portrait section
$section = $phpWord->addSection(array('borderColor' => '00FF00', 'borderSize' => 12));
$section->addText(htmlspecialchars('I am placed on a default section.'));

// New landscape section
$section = $phpWord->addSection(array('orientation' => 'landscape'));
$section->addText(
    htmlspecialchars(
        'I am placed on a landscape section. Every page starting from this section will be landscape style.'
    )
);
$section->addPageBreak();
$section->addPageBreak();

// New portrait section
$section = $phpWord->addSection(
    array('paperSize' => 'Folio', 'marginLeft' => 600, 'marginRight' => 600, 'marginTop' => 600, 'marginBottom' => 600)
);
$section->addText(htmlspecialchars('This section uses other margins with folio papersize.'));

// New portrait section with Header & Footer
$section = $phpWord->addSection(
    array(
        'marginLeft'   => 200,
        'marginRight'  => 200,
        'marginTop'    => 200,
        'marginBottom' => 200,
        'headerHeight' => 50,
        'footerHeight' => 50,
    )
);
$section->addText(htmlspecialchars('This section and we play with header/footer height.'));
$section->addHeader()->addText(htmlspecialchars('Header'));
$section->addFooter()->addText(htmlspecialchars('Footer'));

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
