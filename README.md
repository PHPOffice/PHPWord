![PHPWord](https://raw.github.com/PHPOffice/PHPWord/master/samples/resources/PHPWord.png "PHPWord")

# PHPWord

[![Build Status](https://travis-ci.org/PHPOffice/PHPWord.png?branch=master)](https://travis-ci.org/PHPOffice/PHPWord)
[![Latest Stable Version](https://poser.pugx.org/phpoffice/phpword/v/stable.png)](https://packagist.org/packages/phpoffice/phpword) [![Total Downloads](https://poser.pugx.org/phpoffice/phpword/downloads.png)](https://packagist.org/packages/phpoffice/phpword) [![Latest Unstable Version](https://poser.pugx.org/phpoffice/phpword/v/unstable.png)](https://packagist.org/packages/phpoffice/phpword) [![License](https://poser.pugx.org/phpoffice/phpword/license.png)](https://packagist.org/packages/phpoffice/phpword)

## Introduction

PHPWord is a library written in pure PHP that provides a set of classes to write to and read from different document file formats. The current version of PHPWord supports Microsoft [Office Open XML](http://en.wikipedia.org/wiki/Office_Open_XML) (OOXML or OpenXML), OASIS [Open Document Format for Office Applications](http://en.wikipedia.org/wiki/OpenDocument) (OpenDocument or ODF), and [Rich Text Format](http://en.wikipedia.org/wiki/Rich_Text_Format) (RTF).

No Windows operating system is needed for usage because the resulting DOCX, ODT, or RTF files can be opened by all major [word processing softwares](http://en.wikipedia.org/wiki/List_of_word_processors).

PHPWord is an open source project licensed under [LGPL](license.md). PHPWord is [unit tested](https://travis-ci.org/PHPOffice/PHPWord) to make sure that the released versions are stable.

__Want to contribute?__ [Fork us](https://github.com/PHPOffice/PHPWord/fork) or [submit](https://github.com/PHPOffice/PHPWord/issues) your bug reports or feature requests to us.

### Features

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

### File formats

Below are the supported features for each file formats.

#### Writers

| No | Element       | DOCX | ODT | RTF |
|----|---------------|:----:|:---:|:---:|
| 1  | Text          |   v  |  v  |  v  |
| 2  | Text Run      |   v  |  v  |  v  |
| 3  | Title         |   v  |     |     |
| 4  | Link          |   v  |     |     |
| 5  | Preserve Text |   v  |     |     |
| 6  | Text Break    |   v  |  v  |  v  |
| 7  | Page Break    |   v  |     |     |
| 8  | List          |   v  |     |     |
| 9  | Table         |   v  |     |     |
| 10 | Image         |   v  |     |     |
| 11 | MemoryImage   |   v  |     |     |
| 12 | Object        |   v  |     |     |
| 13 | Watermark     |   v  |     |     |
| 14 | TOC           |   v  |     |     |
| 15 | Header        |   v  |     |     |
| 16 | Footer        |   v  |     |     |
| 17 | Footnote      |   v  |     |     |

#### Readers

| No | Element       | DOCX | ODT | RTF |
|----|---------------|:----:|:---:|:---:|
| 1  | Text          |   v  |     |     |
| 2  | Text Run      |   v  |     |     |
| 3  | Title         |      |     |     |
| 4  | Link          |      |     |     |
| 5  | Preserve Text |      |     |     |
| 6  | Text Break    |   v  |     |     |
| 7  | Page Break    |      |     |     |
| 8  | List          |      |     |     |
| 9  | Table         |      |     |     |
| 10 | Image         |      |     |     |
| 11 | MemoryImage   |      |     |     |
| 12 | Object        |      |     |     |
| 13 | Watermark     |      |     |     |
| 14 | TOC           |      |     |     |
| 15 | Header        |      |     |     |
| 16 | Footer        |      |     |     |
| 17 | Footnote      |      |     |     |


## Installing

### Requirements

Mandatory:

* PHP 5.3+
* PHP [Zip](http://php.net/manual/en/book.zip.php) extension
* PHP [XML Parser](http://www.php.net/manual/en/xml.installation.php) extension

Optional PHP extensions:

* [GD](http://php.net/manual/en/book.image.php)
* [XMLWriter](http://php.net/manual/en/book.xmlwriter.php)
* [XSL](http://php.net/manual/en/book.xsl.php)

### Installation

There are two ways to install PHPWord, i.e. via [Composer](http://getcomposer.org/) or manually by downloading the library.

#### Composer

To install via Composer, add the following lines to your ``composer.json``:

```json
{
    "require": {
       "phpoffice/phpword": "dev-master"
    }
}
```

#### Manual installation

To install manually, [download PHPWord package from github](https://github.com/PHPOffice/PHPWord/archive/master.zip). Extract the package and put the contents to your machine. To use the library, include `Classes/PHPWord.php` in your script like below.

```php
require_once '/path/to/PHPWord/Classes/PHPWord.php';
```

### Using samples

After installation, you can browse and use the samples that we've provided, either by command line or using browser. If you can access your PHPWord library folder using browser, point your browser to the `samples` folder, e.g. `http://localhost/PHPWord/samples/`.

## User manual

- [General usage](#general-usage)
- [Containers](#containers)
    - [Sections](#sections)
    - [Headers](#headers)
    - [Footers](#footers)
- [Elements](#elements)
    - [Texts](#texts)
    - [Breaks](#breaks)
    - [Lists](#lists)
    - [Tables](#tables)
    - [Images](#images)
    - [Objects](#images)
    - [Table of contents](#toc)
    - [Footnotes](#footnotes)
- [Templates](#templates)

<a name="general-usage"></a>
## General usage

### Basic example

The following is a basic example of the PHPWord library. More examples are provided in the [samples folder](samples/).

```php
$PHPWord = new PHPWord();

// Every element you want to append to the word document is placed in a section.
// To create a basic section:
$section = $PHPWord->createSection();

// After creating a section, you can append elements:
$section->addText('Hello world!');

// You can directly style your text by giving the addText function an array:
$section->addText('Hello world! I am formatted.',
    array('name'=>'Tahoma', 'size'=>16, 'bold'=>true));

// If you often need the same style again you can create a user defined style
// to the word document and give the addText function the name of the style:
$PHPWord->addFontStyle('myOwnStyle',
    array('name'=>'Verdana', 'size'=>14, 'color'=>'1B2232'));
$section->addText('Hello world! I am formatted by a user defined style',
    'myOwnStyle');

// You can also put the appended element to local object like this:
$fontStyle = new PHPWord_Style_Font();
$fontStyle->setBold(true);
$fontStyle->setName('Verdana');
$fontStyle->setSize(22);
$myTextElement = $section->addText('Hello World!');
$myTextElement->setFontStyle($fontStyle);

// Finally, write the document:
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
$objWriter->save('helloWorld.docx');
```

### Default font

By default, every text appears in Arial 10 point. You can alter the default font by using the following two functions:

```php
$phpWord->setDefaultFontName('Times New Roman');
$phpWord->setDefaultFontSize(12);
```

### Document properties

You can set the document properties such as title, creator, and company name. Use the following functions:

```php
$properties = $PHPWord->getProperties();
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

<a name="measurement-units"></a>
### Measurement units

The base length unit in Open Office XML is twip. Twip means "TWentieth of an Inch Point", i.e. 1 twip = 1/1440 inch.

You can use PHPWord helper functions to convert inches, centimeters, or points to twips.

```php
// Paragraph with 6 points space after
$phpWord->addParagraphStyle('My Style', array(
    'spaceAfter' => PHPWord_Shared_Font::pointSizeToTwips(6))
);

$section = $phpWord->createSection();
$sectionStyle = $section->getSettings();
// half inch left margin
$sectionStyle->setMarginLeft(PHPWord_Shared_Font::inchSizeToTwips(.5));
// 2 cm right margin
$sectionStyle->setMarginRight(PHPWord_Shared_Font::centimeterSizeToTwips(2));
```

## Containers

<a name="sections"></a>
### Sections

Every visible element in word is placed inside of a section. To create a section, use the following code:

```php
$section = $phpWord->createSection($sectionSettings);
```
The `$sectionSettings` is an optional associative array that sets the section. Example:

```php
$sectionSettings = array(
    'orientation' => 'landscape',
    'marginTop' => 600,
    'colsNum' => 2,
);
```
<a name="section-settings"></a>
#### Section settings

Below are the available settings for section:

* `orientation` Page orientation, i.e. 'portrait' (default) or 'landscape'
* `marginTop` Page margin top in twips
* `marginLeft` Page margin left in twips
* `marginRight` Page margin right in twips
* `marginBottom` Page margin bottom in twips
* `borderTopSize` Border top size in twips
* `borderTopColor` Border top color
* `borderLeftSize` Border left size in twips
* `borderLeftColor` Border left color
* `borderRightSize` Border right size in twips
* `borderRightColor` Border right color
* `borderBottomSize` Border bottom size in twips
* `borderBottomColor` Border bottom color
* `headerHeight` Spacing to top of header
* `footerHeight` Spacing to bottom of footer
* `colsNum` Number of columns
* `colsSpace` Spacing between columns
* `breakType` Section break type (nextPage, nextColumn, continuous, evenPage, oddPage)

The following two settings are automatically set by the use of the `orientation` setting. You can alter them but that's not recommended.

* `pageSizeW` Page width in twips
* `pageSizeH` Page height in twips

<a name="section-page-numbering"></a>
#### Section page numbering

You can change a section page numbering.

```php
$section = $phpWord->createSection();
$section->getSettings()->setPageNumberingStart(1);
```

<a name="headers"></a>
### Headers

Each section can have its own header reference. To create a header use the `createHeader` method:

```php
$header = $section->createHeader();
```

Be sure to save the result in a local object. You can use all elements that are available for the footer. See "Footer" section for detail. Additionally, only inside of the header reference you can add watermarks or background pictures. See "Watermarks" section.

<a name="footers"></a>
### Footers

Each section can have its own footer reference. To create a footer, use the `createFooter` method:

```php
$footer = $section->createFooter();
```

Be sure to save the result in a local object to add elements to a footer. You can add the following elements to footers:

* Texts `addText` and `createTextrun`
* Text breaks
* Images
* Tables
* Preserve text

See the "Elements" section for the detail of each elements.

<a name="elements"></a>
## Elements

<a name="texts"></a>
### Texts

Text can be added by using `addText` and `createTextRun` method. `addText` is used for creating simple paragraphs that only contain texts with the same style. `createTextRun` is used for creating complex paragraphs that contain text with different style (some bold, other italics, etc) or other elements, e.g. images or links. The syntaxes are as follow:

```php
$section->addText($text, [$fontStyle], [$paragraphStyle]);
$textrun = $section->createTextRun([$paragraphStyle]);
```

You can use the `$fontStyle` and `$paragraphStyle` variable to define text formatting. There are 2 options to style the inserted text elements, i.e. inline style by using array or defined style by adding style definition.

Inline style examples:

```php
$fontStyle = array('name' => 'Times New Roman', 'size' => 9);
$paragraphStyle = array('align' => 'both');
$section->addText('I am simple paragraph', $fontStyle, $paragraphStyle);

$textrun = $section->createTextRun();
$textrun->addText('I am bold', array('bold' => true));
$textrun->addText('I am italic, array('italic' => true));
$textrun->addText('I am colored, array('color' => 'AACC00'));
```

Defined style examples:

```php
$fontStyle = array('color' => '006699', 'size' => 18, 'bold' => true);
$PHPWord->addFontStyle('fStyle', $fontStyle);
$text = $section->addText('Hello world!', 'fStyle');

$paragraphStyle = array('align' => 'center');
$PHPWord->addParagraphStyle('pStyle', $paragraphStyle);
$text = $section->addText('Hello world!', 'pStyle');
```

<a name="font-style"></a>
#### Font style

Available font styles:

* ``name`` Font name, e.g. _Arial_
* ``size`` Font size, e.g. _20_, _22_,
* ``hint`` Font content type, _default_, _eastAsia_, or _cs_
* ``bold`` Bold, _true_ or _false_
* ``italic`` Italic, _true_ or _false_
* ``superScript`` Superscript, _true_ or _false_
* ``subScript`` Subscript, _true_ or _false_
* ``underline`` Underline, _dash_, _dotted_, etc.
* ``strikethrough`` Strikethrough, _true_ or _false_
* ``color`` Font color, e.g. _FF0000_
* ``fgColor`` Font highlight color, e.g. _yellow_, _green_, _blue_

<a name="paragraph-style"></a>
#### Paragraph style

Available paragraph styles:

* ``align`` Paragraph alignment, _left_, _right_ or _center_
* ``spaceBefore`` Space before paragraph
* ``spaceAfter`` Space after paragraph
* ``indent`` Indent by how much
* ``hanging`` Hanging by how much
* ``basedOn`` Parent style
* ``next`` Style for next paragraph
* ``widowControl`` Allow first/last line to display on a separate page, _true_ or _false_
* ``keepNext`` Keep paragraph with next paragraph, _true_ or _false_
* ``keepLines`` Keep all lines on one page, _true_ or _false_
* ``pageBreakBefore`` Start paragraph on next page, _true_ or _false_
* ``lineHeight`` text line height, e.g. _1.0_, _1.5_, ect...
* ``tabs`` Set of custom tab stops

<a name="titles"></a>
#### Titles

If you want to structure your document or build table of contents, you need titles or headings. To add a title to the document, use the `addTitleStyle` and `addTitle` method.

```php
$PHPWord->addTitleStyle($depth, [$fontStyle], [$paragraphStyle]);
$section->addTitle($text, [$depth]);
```

Its necessary to add a title style to your document because otherwise the title won't be detected as a real title.

<a name="links"></a>
#### Links

You can add Hyperlinks to the document by using the function addLink:

```php
$section->addLink($linkSrc, [$linkName], [$fontStyle], [$paragraphStyle]);
```

* ``$linkSrc`` The URL of the link.
* ``$linkName`` Placeholder of the URL that appears in the document.
* ``$fontStyle`` See "Font style" section.
* ``$paragraphStyle`` See "Paragraph style" section.

#### Preserve texts

The `addPreserveText` method is used to add a page number or page count to headers or footers.

```php
$footer->addPreserveText('Page {PAGE} of {NUMPAGES}.');
```

<a name="breaks"></a>
### Breaks

#### Text breaks

Text breaks are empty new lines. To add text breaks, use the following syntax. All paramaters are optional.

```php
$section->addTextBreak([$breakCount], [$fontStyle], [$paragraphStyle]);
```

* ``$breakCount`` How many lines
* ``$fontStyle`` See "Font style" section.
* ``$paragraphStyle`` See "Paragraph style" section.

#### Page breaks

There are two ways to insert a page breaks, using the `addPageBreak` method or using the `pageBreakBefore` style of paragraph.

```
$section->addPageBreak();
```

<a name="lists"></a>
### Lists

To add a list item use the function `addListItem`.

```php
$section->addListItem($text, [$depth], [$fontStyle], [$listStyle], [$paragraphStyle]);
```

* ``$text`` Text that appears in the document.
* ``$depth`` Depth of list item.
* ``$fontStyle`` See "Font style" section.
* ``$listStyle`` List style of the current element TYPE_NUMBER, TYPE_ALPHANUM, TYPE_BULLET_FILLED, etc. See list of constants in PHPWord_Style_ListItem.
* ``$paragraphStyle`` See "Paragraph style" section.

<a name="tables"></a>
### Tables

To add tables, rows, and cells, use the `addTable`, `addRow`, and `addCell` methods:

```php
$table = $section->addTable([$tableStyle]);
$table->addRow([$height], [$rowStyle]);
$cell = $table->addCell($width, [$cellStyle]);
```

Table style can be defined with `addTableStyle`:

```php
$tableStyle = array(
    'borderColor' => '006699',
    'borderSize' => 6,
    'cellMargin' => 50
);
$firstRowStyle = array('bgColor' => '66BBFF');
$PHPWord->addTableStyle('myTable', $tableStyle, $firstRowStyle);
$table = $section->addTable('myTable');
```

Table styles:

* ``$width`` Table width in percent
* ``$bgColor`` Background color, e.g. '9966CC'
* ``$border(Top|Right|Bottom|Left)Size`` Border size in twips
* ``$border(Top|Right|Bottom|Left)Color`` Border color, e.g. '9966CC'
* ``$cellMargin(Top|Right|Bottom|Left) `` Cell margin in twips

Row styles:

* ``tblHeader`` Repeat table row on every new page, _true_ or _false_
* ``cantSplit`` Table row cannot break across pages, _true_ or _false_

Cell styles:

* ``$width`` Cell width in twips
* ``$valign`` Vertical alignment, _top_, _center_, _both_, _bottom_
* ``$textDirection`` Direction of text
* ``$bgColor`` Background color, e.g. '9966CC'
* ``$border(Top|Right|Bottom|Left)Size`` Border size in twips
* ``$border(Top|Right|Bottom|Left)Color`` Border color, e.g. '9966CC'
* ``$gridSpan `` Number of columns spanned
* ``$vMerge `` _restart_ or _continue_

#### Cell Span

You can span a cell on multiple columms.

```php
$cell = $table->addCell(200);
$cell->getStyle()->setGridSpan(5);
```

<a name="images"></a>
### Images

To add an image, use the `addImage` or `addMemoryImage` method. The first one is used when your source is stored locally, the later is used when your source is a remote URL, either another script that create image or an image on the internet.

Syntax:

```php
$section->addImage($src, [$style]);
$section->addMemoryImage($link, [$style]);
```

Examples:

```php
$section = $phpWord->createSection();
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

$section->addMemoryImage('http://example.com/image.php');
$section->addMemoryImage('http://php.net/logo.jpg');
```

#### Image styles

Available image styles:

* ``width`` Width in pixels
* ``height`` Height in pixels
* ``align`` Image alignment, _left_, _right_, or _center_
* ``marginTop`` Top margin in inches, can be negative
* ``marginLeft`` Left margin in inches, can be negative
* ``wrappingStyle`` Wrapping style, _inline_, _square_, _tight_, _behind_, or _infront_

#### Watermarks

To add a watermark (or page background image), your section needs a header reference. After creating a header, you can use the `addWatermark` method to add a watermark.

```php
$section = $PHPWord->createSection();
$header = $section->createHeader();
$header->addWatermark('resources/_earth.jpg', array('marginTop' => 200, 'marginLeft' => 55));
```

<a name="objects"></a>
### Objects

You can add OLE embeddings, such as Excel spreadsheets or PowerPoint presentations to the document by using `addObject` method.

```php
$section->addObject($src, [$style]);
```

<a name="toc"></a>
### Table of contents

To add a table of contents (TOC), you can use the `addTOC` method. Your TOC can only be generated if you have add at least one title (See "Titles").

```php
$section->addTOC([$fontStyle], [$tocStyle]);
```

* ``tabLeader`` Fill type between the title text and the page number. Use the defined constants in PHPWord_Style_TOC.
* ``tabPos`` The position of the tab where the page number appears in twips.
* ``indent`` The indent factor of the titles in twips.

<a name="footnotes"></a>
### Footnotes

You can create footnotes in texts or textruns, but it's recommended to use textrun to have better layout.

On textrun:

```php
$textrun = $section->createTextRun();
$textrun->addText('Lead text.');
$footnote = $textrun->createFootnote();
$footnote->addText('Footnote text.');
$textrun->addText('Trailing text.');
```

On text:

```php
$section->addText('Lead text.');
$footnote = $section->createFootnote();
$footnote->addText('Footnote text.');
```

## Templates

You can create a docx template with included search-patterns that can be replaced by any value you wish. Only single-line values can be replaced. To load a template file, use the `loadTemplate` method. After loading the docx template, you can use the `setValue` method to change the value of a search pattern. The search-pattern model is: `${search-pattern}`. It is not possible to add new PHPWord elements to a loaded template file.

Example:

```php
$template = $PHPWord->loadTemplate('Template.docx');
$template->setValue('Name', 'Somebody someone');
$template->setValue('Street', 'Coming-Undone-Street 32');
```
