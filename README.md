# ![PHPWord](https://raw.githubusercontent.com/PHPOffice/PHPWord/develop/docs/images/phpword.svg "PHPWord")

[![Build Status](https://travis-ci.org/PHPOffice/PHPWord.svg?branch=master)](https://travis-ci.org/PHPOffice/PHPWord)
[![Latest Stable Version](https://poser.pugx.org/phpoffice/phpword/v/stable.png)](https://packagist.org/packages/phpoffice/phpword)
[![Total Downloads](https://poser.pugx.org/phpoffice/phpword/downloads.png)](https://packagist.org/packages/phpoffice/phpword)
[![Latest Unstable Version](https://poser.pugx.org/phpoffice/phpword/v/unstable.png)](https://packagist.org/packages/phpoffice/phpword)
[![License](https://poser.pugx.org/phpoffice/phpword/license.png)](https://packagist.org/packages/phpoffice/phpword)


PHPWord is a library written in pure PHP that provides a set of classes to write to and read from different document file formats. The current version of PHPWord supports Microsoft [Office Open XML](http://en.wikipedia.org/wiki/Office_Open_XML) (OOXML or OpenXML), OASIS [Open Document Format for Office Applications](http://en.wikipedia.org/wiki/OpenDocument) (OpenDocument or ODF), and [Rich Text Format](http://en.wikipedia.org/wiki/Rich_Text_Format) (RTF).

With PHPWord, you can create DOCX, ODT, or RTF documents dynamically using your PHP 5.3+ scripts. Below are some of the things that you can do with PHPWord library:

* Set document properties, e.g. title, subject, and creator.
* Create document sections with different settings, e.g. portrait/landscape, page size, and page numbering
* Create header and footer for each sections
* Set default font type, font size, and paragraph style
* Use UTF-8 and East Asia fonts/characters
* Define custom font styles (e.g. bold, italic, color) and paragraph styles (e.g. centered, multicolumns, spacing) either as named style or inline in text
* Insert paragraphs, either as a simple text or complex one (a text run) that contains other elements
* Insert titles (headers) and table of contents
* Insert text breaks and page breaks
* Insert and format images, either local, remote, or as page watermarks
* Insert binary OLE Objects such as Excel or Visio
* Insert and format table with customized properties for each rows (e.g. repeat as header row) and cells (e.g. background color, rowspan, colspan)
* Insert list items as bulleted, numbered, or multilevel
* Insert hyperlinks
* Create document from templates
* Use XSL 1.0 style sheets to transform main document part of OOXML template
* ... and many more features on progress

__Want to contribute?__ [Fork us](https://github.com/PHPOffice/PHPWord/fork) or [submit](https://github.com/PHPOffice/PHPWord/issues) your bug reports or feature requests to us.

## Requirements
* PHP 5.3+
* PHP [Zip](http://php.net/manual/en/book.zip.php) extension
* PHP [XML Parser](http://www.php.net/manual/en/xml.installation.php) extension

### Optional PHP extensions
* PHP [GD](http://php.net/manual/en/book.image.php) extension
* PHP [XMLWriter](http://php.net/manual/en/book.xmlwriter.php) extension
* PHP [XSL](http://php.net/manual/en/book.xsl.php) extension

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
PhpOffice\PhpWord\Autoloader::register();
```

## Basic usage

The following is a basic example of the PHPWord library. More examples are provided in the [samples folder](samples/).

```php
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// Every element you want to append to the word document is placed in a section.
// To create a basic section:
$section = $phpWord->createSection();

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

## Documentation

__Want to know more?__ Read the full documentation of PHPWord on [Read The Docs](http://phpword.readthedocs.org/).
