# PHPWord - OpenXML - Read, Write and Create Word documents in PHP

PHPWord is a library written in PHP that create word documents.
No Windows operating system is needed for usage because the result are docx files (Office Open XML) that can be
opened by all major office software.

Test patch branch

## Want to contribute?
Fork us!

## Requirements

* PHP version 5.3.0 or higher

## License
PHPWord is licensed under [LGPL (GNU LESSER GENERAL PUBLIC LICENSE)](https://github.com/PHPOffice/PHPWord/blob/master/license.md)

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

## Usage

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

## Images

You can add images easily using the following example.

```php
$section = $PHPWord->createSection();
$section->addImage('mars.jpg');
```

Images settings include:
 * ``width`` width in pixels
 * ``height`` height in pixels
 * ``align`` image alignment, __left__, __right__ or __center__
 * ``marginTop`` top margin in inches, can be negative
 * ``marginLeft`` left margin in inches, can be negative
 * ``wrappingStyle`` can be inline, __square__, __tight__, __behind__, __infront__

 To add an image with settings, consider the following example.

 ```php
$section->addImage(
    'mars.jpg',
    array(
        'width' => 100,
        'height' => 100,
        'marginTop' => -1,
        'marginLeft' => -1,
        wrappingStyle => 'behind'
    )
);
 ```
