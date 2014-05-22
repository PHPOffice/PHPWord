# ![PHPWord](https://rawgit.com/PHPOffice/PHPWord/develop/docs/images/phpword.svg "PHPWord")

[![Latest Stable Version](https://poser.pugx.org/phpoffice/phpword/v/stable.png)](https://packagist.org/packages/phpoffice/phpword)
[![Build Status](https://travis-ci.org/PHPOffice/PHPWord.svg?branch=master)](https://travis-ci.org/PHPOffice/PHPWord)
[![Code Quality](https://scrutinizer-ci.com/g/PHPOffice/PHPWord/badges/quality-score.png?s=b5997ce59ac2816b4514f3a38de9900f6d492c1d)](https://scrutinizer-ci.com/g/PHPOffice/PHPWord/)
[![Code Coverage](https://scrutinizer-ci.com/g/PHPOffice/PHPWord/badges/coverage.png?s=742a98745725c562955440edc8d2c39d7ff5ae25)](https://scrutinizer-ci.com/g/PHPOffice/PHPWord/)
[![Total Downloads](https://poser.pugx.org/phpoffice/phpword/downloads.png)](https://packagist.org/packages/phpoffice/phpword)
[![License](https://poser.pugx.org/phpoffice/phpword/license.png)](https://packagist.org/packages/phpoffice/phpword)

PHPWord is a library written in pure PHP that provides a set of classes to write to and read from different document file formats. The current version of PHPWord supports Microsoft [Office Open XML](http://en.wikipedia.org/wiki/Office_Open_XML) (OOXML or OpenXML), OASIS [Open Document Format for Office Applications](http://en.wikipedia.org/wiki/OpenDocument) (OpenDocument or ODF), [Rich Text Format](http://en.wikipedia.org/wiki/Rich_Text_Format) (RTF), HTML, and PDF.

PHPWord is an open source project licensed under the terms of [LGPL version 3](https://github.com/PHPOffice/PHPWord/blob/develop/COPYING.LESSER). PHPWord is aimed to be a high quality software product by incorporating [continuous integration](https://travis-ci.org/PHPOffice/PHPWord) and [unit testing](http://phpoffice.github.io/PHPWord/coverage/develop/). You can learn more about PHPWord by reading the [Developers' Documentation](http://phpword.readthedocs.org/) and the [API Documentation](http://phpoffice.github.io/PHPWord/docs/develop/).

## Features

With PHPWord, you can create DOCX, ODT, or RTF documents dynamically using your PHP 5.3+ scripts. Below are some of the things that you can do with PHPWord library:

- Set document properties, e.g. title, subject, and creator.
- Create document sections with different settings, e.g. portrait/landscape, page size, and page numbering
- Create header and footer for each sections
- Set default font type, font size, and paragraph style
- Use UTF-8 and East Asia fonts/characters
- Define custom font styles (e.g. bold, italic, color) and paragraph styles (e.g. centered, multicolumns, spacing) either as named style or inline in text
- Insert paragraphs, either as a simple text or complex one (a text run) that contains other elements
- Insert titles (headers) and table of contents
- Insert text breaks and page breaks
- Insert and format images, either local, remote, or as page watermarks
- Insert binary OLE Objects such as Excel or Visio
- Insert and format table with customized properties for each rows (e.g. repeat as header row) and cells (e.g. background color, rowspan, colspan)
- Insert list items as bulleted, numbered, or multilevel
- Insert hyperlinks
- Insert footnotes and endnotes
- Create document from templates
- Use XSL 1.0 style sheets to transform main document part of OOXML template
- ... and many more features on progress

## Requirements

PHPWord requires the following:

- PHP 5.3+
- [Zip extension](http://php.net/manual/en/book.zip.php)
- [XML Parser extension](http://www.php.net/manual/en/xml.installation.php)
- [GD extension](http://php.net/manual/en/book.image.php) (optional, used to add images)
- [XMLWriter extension](http://php.net/manual/en/book.xmlwriter.php) (optional, used to write DOCX and ODT)
- [XSL extension](http://php.net/manual/en/book.xsl.php) (optional, used to apply XSL style sheet to template )
- [dompdf](https://github.com/dompdf/dompdf) (optional, used to write PDF)

## Installation

It is recommended that you install the PHPWord library [through composer](http://getcomposer.org/). To do so, add
the following lines to your ``composer.json``.

```json
{
    "require": {
       "phpoffice/phpword": "dev-master"
    }
}
```

Alternatively, you can download the latest release from the [releases page](https://github.com/PHPOffice/PHPWord/releases).
In this case, you will have to register the autoloader.

```php
require_once 'path/to/PhpWord/src/PhpWord/Autoloader.php';
\PhpOffice\PhpWord\Autoloader::register();
```

## Usages

The following is a basic example of the PHPWord library. More examples are provided in the [samples folder](samples/).

```php
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// Every element you want to append to the word document is placed in a section.
// To create a basic section:
$section = $phpWord->addSection();

// After creating a section, you can append elements:
$section->addText('Hello world!');

// You can directly style your text by giving the addText function an array:
$section->addText('Hello world! I am formatted.',
    array('name'=>'Tahoma', 'size'=>16, 'bold'=>true));

// If you often need the same style again you can create a user defined style
// to the word document and give the addText function the name of the style:
$phpWord->addFontStyle('myOwnStyle',
    array('name'=>'Verdana', 'size'=>14, 'color'=>'1B2232'));
$section->addText('Hello world! I am formatted by a user defined style',
    'myOwnStyle');

// You can also put the appended element to local object like this:
$fontStyle = new \PhpOffice\PhpWord\Style\Font();
$fontStyle->setBold(true);
$fontStyle->setName('Verdana');
$fontStyle->setSize(22);
$myTextElement = $section->addText('Hello World!');
$myTextElement->setFontStyle($fontStyle);

// Finally, write the document:
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('helloWorld.docx');

$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'ODText');
$objWriter->save('helloWorld.odt');

$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'RTF');
$objWriter->save('helloWorld.rtf');
```

## Contributing

We welcome everyone to contribute to PHPWord. Below are some of the things that you can do to contribute:

- Read [our contributing guide](https://github.com/PHPOffice/PHPWord/blob/master/CONTRIBUTING.md)
- [Fork us](https://github.com/PHPOffice/PHPWord/fork) and [request a pull](https://github.com/PHPOffice/PHPWord/pulls) to the [develop](https://github.com/PHPOffice/PHPWord/tree/develop) branch
- Submit [bug reports or feature requests](https://github.com/PHPOffice/PHPWord/issues) to GitHub
- Follow [@PHPWord](https://twitter.com/PHPWord) and [@PHPOffice](https://twitter.com/PHPOffice) on Twitter
