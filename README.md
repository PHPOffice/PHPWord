# PHPWord

[![Build Status](https://travis-ci.org/PHPOffice/PHPWord.png?branch=master)](https://travis-ci.org/PHPOffice/PHPWord)
[![Latest Stable Version](https://poser.pugx.org/phpoffice/phpword/v/stable.png)](https://packagist.org/packages/phpoffice/phpword) [![Total Downloads](https://poser.pugx.org/phpoffice/phpword/downloads.png)](https://packagist.org/packages/phpoffice/phpword) [![Latest Unstable Version](https://poser.pugx.org/phpoffice/phpword/v/unstable.png)](https://packagist.org/packages/phpoffice/phpword) [![License](https://poser.pugx.org/phpoffice/phpword/license.png)](https://packagist.org/packages/phpoffice/phpword)

__OpenXML - Read, Write and Create Word documents in PHP.__

PHPWord is a library written in PHP that create word documents.

No Windows operating system is needed for usage because the result are docx files (Office Open XML) that can be
opened by all major office software.

__Want to contribute?__ Fork us!

## Requirements

* PHP version 5.3.0 or higher

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

## Documentation

### Table of contents

1. [Basic usage](#basic-usage)
2. [Sections](#sections)
    * [Change Section Page Numbering](#sections-page-numbering)
3. [Text](#text)
    * [Attributes](#text-attributes)
3. [Paragraph Style](#paragraph-style)
    * [Attributes](#paragraph-style-attributes)
4. [Tables](#tables)
    * [Cell Style](#tables-cell-style)
5. [Images](#images)
    * [Attributes](#images-attributes)

<a name="basic-usage"></a>
#### Basic usage

The following is a basic example of the PHPWord library.

```php
$PHPWord = new PHPWord();

// Every element you want to append to the word document is placed in a section. So you need a section:
$section = $PHPWord->createSection();

// After creating a section, you can append elements:
$section->addText('Hello world!');

// You can directly style your text by giving the addText function an array:
$section->addText('Hello world! I am formatted.', array('name'=>'Tahoma', 'size'=>16, 'bold'=>true));

// If you often need the same style again you can create a user defined style to the word document
// and give the addText function the name of the style:
$PHPWord->addFontStyle('myOwnStyle', array('name'=>'Verdana', 'size'=>14, 'color'=>'1B2232'));
$section->addText('Hello world! I am formatted by a user defined style', 'myOwnStyle');

// You can also putthe appended element to local object an call functions like this:
$myTextElement = $section->addText('Hello World!');
$myTextElement->setBold();
$myTextElement->setName('Verdana');
$myTextElement->setSize(22);

// At least write the document to webspace:
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$objWriter->save('helloWorld.docx');
```

<a name="sections"></a>
#### Sections

<a name="sections-page-numbering"></a>
##### Change Section Page Numbering

You can change a section page numbering.

```php
$section = $PHPWord->createSection();
$section->getSettings()->setPageNumberingStart(1);
```

<a name="text"></a>
#### Text

You can add text easily using the following example.

```php
$fontStyle = array();
$paragraphStyle = array();
$section = $PHPWord->createSection();
$section->addText('My text', $fontStyle, $paragraphStyle);
```

<a name="text-attributes"></a>
##### Attributes

* ``size`` text size, e.g. _20_, _22_,
* ``name`` font name, e.g. _Arial_
* ``bold`` text is bold, _true_ or _false_
* ``italic`` text is italic, _true_ or _false_
* ``superScript`` text is super script, _true_ or _false_
* ``subScript`` text is sub script, _true_ or _false_
* ``underline`` text is underline, _true_ or _false_
* ``strikethrough`` text is strikethrough, _true_ or _false_
* ``color`` text color, e.g. _FF0000_
* ``fgColor`` fgColor
* ``line-height`` text line height, e.g. _1.0_, _1.5_, ect...

<a name="paragraph-style"></a>
#### Paragraph Style

<a name="paragraph-style-attributes"></a>
##### Attributes

* ``line-height`` text line height, e.g. _1.0_, _1.5_, ect...
* ``align`` paragraph alignment, _left_, _right_ or _center_
* ``spaceBefore`` space before Paragraph
* ``spaceAfter`` space after Paragraph
* ``tabs`` set of Custom Tab Stops
* ``indent`` indent by how much

<a name="tables"></a>
#### Tables

The following illustrates how to create a table.

```php
$table = $section->addTable();
$table->addRow();
$table->addCell();
```

<a name="tables-cell-style"></a>
##### Cell Style

###### Cell Span

You can span a cell on multiple columms.

```php
$cell = $table->addCell(200);
$cell->getStyle()->setGridSpan(5);
```

<a name="images"></a>
#### Images

You can add images easily using the following example.

```php
$section = $PHPWord->createSection();
$section->addImage('mars.jpg');
```

<a name="images-attributes"></a>
##### Attributes

* ``width`` width in pixels
* ``height`` height in pixels
* ``align`` image alignment, _left_, _right_ or _center_
* ``marginTop`` top margin in inches, can be negative
* ``marginLeft`` left margin in inches, can be negative
* ``wrappingStyle`` can be _inline_, _square_, _tight_, _behind_, _infront_

To add an image with attributes, consider the following example.

```php
$section->addImage(
    'mars.jpg',
    array(
        'width' => 100,
        'height' => 100,
        'marginTop' => -1,
        'marginLeft' => -1,
        'wrappingStyle' => 'behind'
    )
);
 ```