# Styles

Styles can be added to various elements either by including them with the element or by adding the style to the PHPWord document.

## Basic Example

``` php
<?php

// Add a combined font and paragraph style to the document
use PhpOffice\PhpWord\SimpleType\Jc as JcType;
$phpWord->addFontStyle('subtitle',
    array('name' => 'Arial', 'size' => 24, 'color' => '1B2232', 'bold' => true),
    array('spacing' => 480, 'lineHeight' => 2, 'align' => JcType::Both),
);

// Set the styles when adding an element.
$sectionStyle = ['paperSize' => 'Letter', 'vAlign' => VerticalType::CENTER];
$section = $phpWord->addSection($sectionStyle);

// Use a document level style.
$section->addText('Hello, World!', 'subtitle');

```

## Document Styles

Document styles can be set by using the following functions.

``` php
<?php

// Set Default Styles
$phpWord->setDefaultFontName('Times New Roman');
$phpWord->setDefaultFontColor('FF0000');
$phpWord->setDefaultFontSize(12);
$phpWord->setDefaultAsianFontName('標楷體');
$phpWord->setDefaultParagraphStyle($paragraphStyle);

// Add Styles
$phpWord->addParagraphStyle($name, $paragraphStyle);
$phpWord->addFontStyle($name, $fontStyle, [$paragraphStyle]);
$phpWord->addLinkStyle($name, $linkStyle);
$phpWord->addNumberingStyle($name, $numberingStyle);
$phpWord->addTitleStyle($depth, $fontStyle, [$paragraphStyle]);
$phpWord->addTableStyle($name, $tableStyle, [$firstRowStyle]);

```
- `$name`. Name of the style.
    * The names of all styles must be unique, regardless of style type.
    * The names *normal*, *title*, and *heading* (including *heading_#*, where # is the `$depth`) are reserved. Use `setDefaultParagraphStyle` and `addTitleStyle` to set those names.
- `$depth`. Outline level of Title. See [`Elements > Title`](elements/title.md).
- `$fontStyle`. See [`Styles > Font`](styles/font.md).
- `$linkStyle`. See [`Styles > Link`](styles/link.md).
- `$numberingStyle`. See [`Styles > Numbering`](styles/numbering.md).
- `$paragraphStyle`. See [`Styles > Paragraph`](styles/paragraph.md).
- `$tableStyle`. See [`Styles > Table`](styles/table.md).
- `$firstRowStyle`. See [`Styles > Row`](styles/table.md).

When adding font and paragraph styles, consider adding them in the following order to improve the end experience:
- Titles, using `addTitleStyle()`.
- Font and paragraph style combinations, using `addFontStyle()`.
- Paragraph styles based on other styles, using `addParagraphStyle()` with the `basedOn` keyword.
- Unique font and paragraph styles, using `addFontStyle()` and `addParagraphStyle()`.

## PHPWord Style Classes
- [`Style > Border`](styles/border.md).
- [`Style > Cell`](styles/table.md).
- [`Style > Chart`](styles/chart.md).
- `Style > Extrusion`.
- `Style > Fill`.
- [`Style > Font`](styles/font.md).
- `Style > Frame`.
- [`Style > Image`](styles/image.md).
- [`Style > Indentation`](styles/indentation.md).
- [`Style > Language`](styles/language.md).
- `Style > Line`.
- [`Style > LineNumbering`](styles/linenumbering.md).
- [`Style > ListItem`](styles/list.md).
- [`Style > Numbering`](styles/numbering.md).
- `Style > NumberingLevel`.
- `Style > Outline`.
- [`Style > Paper`](styles/paper.md).
- [`Style > Paragraph`](styles/paragraph.md).
- [`Style > Row`](styles/table.md).
- [`Style > Section`](styles/section.md).
- `Style > Shading`.
- `Style > Shadow`.
- `Style > Shape`.
- `Style > Spacing`.
- `Style > TOC`.
- [`Style > Tab`](styles/tab.md).
- [`Style > Table`](styles/table.md).
- [`Style > TablePosition`](styles/table.md).
- `Style > TextBox`.

