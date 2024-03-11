# Footnote & Endnote

You can create footnotes with ``addFootnote`` and endnotes with``addEndnote`` in texts or textruns, but it's recommended to use textrun to have better layout. You can use ``addText``, ``addLink``,``addTextBreak``, ``addImage``, ``addOLEObject`` on footnotes and endnotes.

On textrun:

``` php
<?php

$textrun = $section->addTextRun();
$textrun->addText('Lead text.');
$footnote = $textrun->addFootnote();
$footnote->addText('Footnote text can have ');
$footnote->addLink('http://test.com', 'links');
$footnote->addText('.');
$footnote->addTextBreak();
$footnote->addText('And text break.');
$textrun->addText('Trailing text.');
$endnote = $textrun->addEndnote();
$endnote->addText('Endnote put at the end');
```

On text:

``` php
<?php

$section->addText('Lead text.');
$footnote = $section->addFootnote();
$footnote->addText('Footnote text.');
```

By default the footnote reference number will be displayed with decimal number
starting from 1. This number uses the ``FooterReference`` style which you can
redefine with the ``addFontStyle`` method. Default value for this style is
``array('superScript' => true)``;

The footnote numbering can be controlled by setting the FootnoteProperties on the Section.

``` php
<?php

$fp = new \PhpOffice\PhpWord\ComplexType\FootnoteProperties();
//sets the position of the footnote (pageBottom (default), beneathText, sectEnd, docEnd)
$fp->setPos(\PhpOffice\PhpWord\ComplexType\FootnoteProperties::POSITION_BENEATH_TEXT);
//set the number format to use (decimal (default), upperRoman, upperLetter, ...)
$fp->setNumFmt(\PhpOffice\PhpWord\SimpleType\NumberFormat::LOWER_ROMAN);
//force starting at other than 1
$fp->setNumStart(2);
//when to restart counting (continuous (default), eachSect, eachPage)
$fp->setNumRestart(\PhpOffice\PhpWord\ComplexType\FootnoteProperties::RESTART_NUMBER_EACH_PAGE);
//And finaly, set it on the Section
$section->setFootnoteProperties($fp);
```