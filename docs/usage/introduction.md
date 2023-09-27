# Introduction

## Basic example

The following is a basic example of the PHPWord library. More examples
are provided in the [samples folder](https://github.com/PHPOffice/PHPWord/tree/master/samples/).

``` php
<?php
require_once 'bootstrap.php';

// Creating the new document...
$phpWord = new \PhpOffice\PhpWord\PhpWord();

/* Note: any element you append to a document must reside inside of a Section. */

// Adding an empty Section to the document...
$section = $phpWord->addSection();
// Adding Text element to the Section having font styled by default...
$section->addText(
    '"Learn from yesterday, live for today, hope for tomorrow. '
        . 'The important thing is not to stop questioning." '
        . '(Albert Einstein)'
);

/*
 * Note: it's possible to customize font style of the Text element you add in three ways:
 * - inline;
 * - using named font style (new font style object will be implicitly created);
 * - using explicitly created font style object.
 */

// Adding Text element with font customized inline...
$section->addText(
    '"Great achievement is usually born of great sacrifice, '
        . 'and is never the result of selfishness." '
        . '(Napoleon Hill)',
    array('name' => 'Tahoma', 'size' => 10)
);

// Adding Text element with font customized using named font style...
$fontStyleName = 'oneUserDefinedStyle';
$phpWord->addFontStyle(
    $fontStyleName,
    array('name' => 'Tahoma', 'size' => 10, 'color' => '1B2232', 'bold' => true)
);
$section->addText(
    '"The greatest accomplishment is not in never falling, '
        . 'but in rising again after you fall." '
        . '(Vince Lombardi)',
    $fontStyleName
);

// Adding Text element with font customized using explicitly created font style object...
$fontStyle = new \PhpOffice\PhpWord\Style\Font();
$fontStyle->setBold(true);
$fontStyle->setName('Tahoma');
$fontStyle->setSize(13);
$myTextElement = $section->addText('"Believe you can and you\'re halfway there." (Theodor Roosevelt)');
$myTextElement->setFontStyle($fontStyle);

// Saving the document as OOXML file...
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('helloWorld.docx');

// Saving the document as ODF file...
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'ODText');
$objWriter->save('helloWorld.odt');

// Saving the document as HTML file...
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
$objWriter->save('helloWorld.html');

/* Note: we skip RTF, because it's not XML-based and requires a different example. */
/* Note: we skip PDF, because "HTML-to-PDF" approach is used to create PDF documents. */
```

## PHPWord Settings

The ``PhpOffice\PhpWord\Settings`` class provides some options that will
affect the behavior of PHPWord. Below are the options.

### XML Writer compatibility

This option sets [XMLWriter::setIndent](http://www.php.net/manual/en/function.xmlwriter-set-indent.php) and [XMLWriter::setIndentString](http://www.php.net/manual/en/function.xmlwriter-set-indent-string.php>). The default value of this option is ``true`` (compatible), which is [required for OpenOffice](https://github.com/PHPOffice/PHPWord/issues/103) to render OOXML document correctly. You can set this option to ``false`` during development to make the resulting XML file easier to read.

``` php
<?php

\PhpOffice\PhpWord\Settings::setCompatibility(false);
```

### Zip class

By default, PHPWord uses [Zip extension](http://php.net/manual/en/book.zip.php) to deal with ZIP compressed archives and files inside them. If you can't have Zip extension installed on your server, you can use pure PHP library alternative, `[PclZip](http://www.phpconcept.net/pclzip/)``, which is included in PHPWord.

``` php
<?php

\PhpOffice\PhpWord\Settings::setZipClass(\PhpOffice\PhpWord\Settings::PCLZIP);
```

### Output escaping

Writing documents of some formats, especially XML-based, requires correct output escaping.
Without it your document may become broken when you put special characters like ampersand, quotes, and others in it.

Escaping can be performed in two ways: outside of the library by a software developer and inside of the library by built-in mechanism.
By default, the built-in mechanism is disabled for backward compatibility with versions prior to v0.13.0.
To turn it on set ``outputEscapingEnabled`` option to ``true`` in your PHPWord configuration file or use the following instruction at runtime:

``` php
<?php

\PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
```

### Default Paper

By default, all sections of the document will print on A4 paper.
You can alter the default paper by using the following function:

``` php
<?php

\PhpOffice\PhpWord\Settings::setDefaultPaper('Letter');
```

### Default font

By default, every text appears in Arial 10 point. You can alter the
default font by using the following two functions:

``` php
<?php

$phpWord->setDefaultFontName('Times New Roman');
$phpWord->setDefaultFontSize(12);
```

## Document settings

Settings for the generated document can be set using ``$phpWord->getSettings()``

### Magnification Setting

The default zoom value is 100 percent. This can be changed either to another percentage

``` php
<?php

$phpWord->getSettings()->setZoom(75);
```

Or to predefined values ``fullPage``, ``bestFit``, ``textFit``

``` php
<?php

$phpWord->getSettings()->setZoom(Zoom::BEST_FIT);
```

### Mirroring the Page Margins

Use mirror margins to set up facing pages for double-sided documents, such as books or magazines.

``` php
<?php

$phpWord->getSettings()->setMirrorMargins(true);
```

!!! note annotate "Don't forget to set both paper size and page size"

    For example, to print a document on A4 paper (landscape) and fold it into A5 pages (portrait), use this section style:

    ``` php
    <?php

    use PhpOffice\PhpWord\Shared\Converter;

    $phpWord->getSettings()->setMirrorMargins(true);
    $phpWord->addSection([
        'paperSize' => 'A4',
        'orientation' => 'landscape',
        'pageSizeW' => Converter::cmToTwip(14.85),
        'pageSizeH' => Converter::cmToTwip(21),
    ]);
    ```

### Printing as folded booklet

Use book-fold printing to set up documents to be printed as foldable pages.

``` php
<?php

$phpWord->getSettings()->setBookFoldPrinting(true);
```

### Spelling and grammatical checks

By default spelling and grammatical errors are shown as soon as you open a word document.
For big documents this can slow down the opening of the document. You can hide the spelling and/or grammatical errors with:

``` php
<?php

$phpWord->getSettings()->setHideGrammaticalErrors(true);
$phpWord->getSettings()->setHideSpellingErrors(true);
```

You can also specify the status of the spell and grammar checks, marking spelling or grammar as dirty will force a re-check when opening the document.

``` php
<?php

$proofState = new \PhpOffice\PhpWord\ComplexType\ProofState();
$proofState->setGrammar(\PhpOffice\PhpWord\ComplexType\ProofState::CLEAN);
$proofState->setSpelling(\PhpOffice\PhpWord\ComplexType\ProofState::DIRTY);

$phpWord->getSettings()->setProofState($proofState);
```

### Track Revisions

Track changes can be activated using ``setTrackRevisions``, you can furture specify

-  Not to use move syntax, instead moved items will be seen as deleted in one place and added in another
-  Not track formatting revisions

``` php
<?php

$phpWord->getSettings()->setTrackRevisions(true);
$phpWord->getSettings()->setDoNotTrackMoves(true);
$phpWord->getSettings()->setDoNotTrackFormatting(true);
```

### Decimal Symbol

The default symbol to represent a decimal figure is the ``.`` in english. In french you might want to change it to ``,`` for instance.

``` php
<?php

$phpWord->getSettings()->setDecimalSymbol(',');
```

### Document Language

The default language of the document can be change with the following.

``` php
<?php

$phpWord->getSettings()->setThemeFontLang(new Language(Language::FR_BE));
```

``Language`` has 3 parameters, one for Latin languages, one for East Asian languages and one for Complex (Bi-Directional) languages.
A couple of language codes are provided in the ``PhpOffice\PhpWord\Style\Language`` class but any valid code/ID can be used.

In case you are generating an RTF document the language need to be set differently.

``` php
<?php

$lang = new Language();
$lang->setLangId(Language::EN_GB_ID);
$phpWord->getSettings()->setThemeFontLang($lang);
```

## Document information

You can set the document information such as title, creator, and company
name. Use the following functions:

``` php
<?php

$properties = $phpWord->getDocInfo();
$properties->setCreator('My name');
$properties->setCompany('My factory');
$properties->setTitle('My title');
$properties->setDescription('My description');
$properties->setCategory('My category');
$properties->setLastModifiedBy('My name');
$properties->setCreated(mktime(0, 0, 0, 3, 12, 2014));
$properties->setModified(mktime(0, 0, 0, 3, 14, 2014));
$properties->setSubject('My subject');
$properties->setKeywords('my, key, word');
```

## Measurement units

The base length unit in Open Office XML is twip. Twip means "TWentieth
of an Inch Point", i.e. 1 twip = 1/1440 inch.

You can use PHPWord helper functions to convert inches, centimeters, or
points to twip.

``` php
<?php

// Paragraph with 6 points space after
$phpWord->addParagraphStyle('My Style', array(
    'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(6))
);

$section = $phpWord->addSection();
$sectionStyle = $section->getStyle();
// half inch left margin
$sectionStyle->setMarginLeft(\PhpOffice\PhpWord\Shared\Converter::inchToTwip(.5));
// 2 cm right margin
$sectionStyle->setMarginRight(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(2));
```

## Document protection

The document (or parts of it) can be password protected.

``` php
<?php

$documentProtection = $phpWord->getSettings()->getDocumentProtection();
$documentProtection->setEditing(DocProtect::READ_ONLY);
$documentProtection->setPassword('myPassword');
```

## Automatically Recalculate Fields on Open

To force an update of the fields present in the document, set updateFields to true

``` php
<?php

$phpWord->getSettings()->setUpdateFields(true);
```

## Hyphenation

Hyphenation describes the process of breaking words with hyphens. There are several options to control hyphenation.

### Auto hyphenation

To automatically hyphenate text set ``autoHyphenation`` to ``true``.

``` php
<?php

$phpWord->getSettings()->setAutoHyphenation(true);
```

### Consecutive Hyphen Limit

The maximum number of consecutive lines of text ending with a hyphen can be controlled by the ``consecutiveHyphenLimit`` option.
There is no limit if the option is not set or the provided value is ``0``.

``` php
<?php

$phpWord->getSettings()->setConsecutiveHyphenLimit(2);
```

### Hyphenation Zone

The hyphenation zone (in *twip*) is the allowed amount of whitespace before hyphenation is applied.
The smaller the hyphenation zone the more words are hyphenated. Or in other words, the wider the hyphenation zone the less words are hyphenated.

``` php
<?php

$phpWord->getSettings()->setHyphenationZone(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(1));
```

### Hyphenate Caps

To control whether or not words in all capital letters shall be hyphenated use the `doNotHyphenateCaps` option.

``` php
<?php

$phpWord->getSettings()->setDoNotHyphenateCaps(true);
```