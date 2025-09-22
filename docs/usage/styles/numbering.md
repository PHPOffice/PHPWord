# Numbering Styles

``` php
<?php

$phpWord->addNumberingStyle(
    'numberssAndLetters',
    [
        'type' => 'hybridMultilevel',
        'levels' => [
            ['format' => 'decimal', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360],
            ['format' => 'upperLetter', 'text' => '%2.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720],
        ],
    ]
);

```

See [`Sample_14_ListItem`](/samples/Sample_14_ListItem.php) for more code samples.

## Options
- `type`. List type, *singleLevel*, *multilevel*, or *hybridMultilevel*.
  * `singleLevel` is a list with only a single level, such as a simple bullet or numbered list.
  * `multilevel` is a list that can have multiple levels, but all levels use the same type of formatting (e.g., all bullets or all numbers).
  * `hybridMultilevel` is a list that can have multiple levels, with different levels potentially using different formatting (e.g., some levels with bullets and others with numbers or letters).
- `levels`. The style for each level of the list. See below.

## Level Options
- `align`. Same as alignment.
- `alignment`. Supports all alignment modes since 1st Edition of ECMA-376 standard up till ISO/IEC 29500:2012.
   * See [`SimpleType > Jc`](../simpletypes/jc.md) for possible values.
- `font`. Font name, e.g. `Arial`.
- `format`. Numbering format.
   * See [`SimpleType > NumberFormat`](../simpletypes/numberformat.md).
- `hanging`. Hanging in `twip`.
- `hint`. Font content type, `default`, `eastAsia`, or `cs`.
- `left`. Left in `twip`.
- `restart`. Restart numbering level symbol.
- `start`. Starting value. `1` is default.
- `suffix`. Content between numbering symbol and paragraph text, `tab`, `space`, or `nothing`. `tab` is default.
- `tabPos`. Tab position in `twip`.
- `text`. Numbering level text e.g. %1 for nonbullet or bullet character.

## Used In
- [`Styles > ListItem`](../styles/list.md).
- [`Styles > Paragraph`](../styles/paragraph.md).
