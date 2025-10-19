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
- `font`. Font name, e.g. `Arial`. Applies only to the numbering level text, e.g. 1., IV), or a bullet.
- `format`. Numbering format.
   * See [`SimpleType > NumberFormat`](../simpletypes/numberformat.md).
- `hanging`. Indentation removed from the first line in `twip`.
- `hint`. Font content type, `default`, `eastAsia`, or `cs`.
- `left`. Left indentation in `twip`.
- `restart`. Restart numbering level symbol.
- `start`. Starting value. `1` is default.
    * Always use integers for the starting value. If the `format` is `NumberFormat::UPPER_LETTER` and you want to start at `E`, then set `start` to 5.
- `suffix`. Content between numbering symbol and paragraph text, `tab`, `space`, or `nothing`. `tab` is default.
- `tabPos`. Tab position in `twip`.
- `text`. Numbering level text, where `%#` will be replaced with the symbol for a particular level.
    * If the `format` is `NumberFormat::DECIMAL`, `%1` will return the first-level number (1, 2, 3, 4), `%2` will return the second-level number, and so on.
    * If the `format` is `NumberFormat::DECIMAL`, `%1.%2.` will return both the first-level and second level number with periods, 1.1., 1.2., 1.3., 1.4.
    * If the `format` is `NumberFormat::UPPER_ROMAN`, `((%1))` will return the first-level upper roman character, ((I)), ((II)), ((III)), ((IV)).
    * If the `format` is `NumberFormat::UPPER_ROMAN` for the first level and `NumberFormat::DECIMAL` for the second level, `%1.%2` will return IV.1, IV.2, IV.3 for second level items after IV.
    * If the `format` is `NumberFormat::BULLET`, `#` will return #, `•` will return •, and `o` will return o.
    * Regardless of `format`, `Item.` will return Item.

## Bullets
For easy copying, here are some common bullet symbols using a standard unicode font.
- `•` Bullet
- `◦` Open bullet
- `·` Middle dot
- `o` Small letter o
- `●` Circle
- `○` Open circle
- `◌` Dotted circle
- `†` Dagger
- `‡` Double dagger
- `#` Number sign
- `§` Section sign
- `›` Single right arrow
- `»` Double right arrow
- `►` Right arrow
- `■` Square
- `□` Open square
- `▪` Small square
- `▫` Small open square

## Used In
- [`Styles > ListItem`](../styles/list.md).
- [`Styles > Paragraph`](../styles/paragraph.md).
