# ListItem Styles

``` php
<?php

$numStyle = 'decimal';
$phpWord->addNumberingStyle($numStyle, ['type' => 'singleLevel', 'levels' => [
    ['format' => 'decimal', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360],
]]);
$section->addListItem('List Item 1', 0, null, $numStyle);
```

See [`Sample_14_ListItem`](/samples/Sample_14_ListItem.php) for more code samples.

## Options
- `numStyle`. Numbering style name. If no name is provided, a bullet list will be returned.
   * See [`Styles > Numbering`](../styles/numbering.md) for possible values.

## DEPRECATED Constants
- `TYPE_SQUARE_FILLED`.
- `TYPE_BULLET_FILLED`.
- `TYPE_BULLET_EMPTY`.
- `TYPE_NUMBER`.
- `TYPE_NUMBER_NESTED`. L1: 1., L2: 1.1., L3: 1.1.1. etc.
- `TYPE_ALPHANUM`. L1: Decimal, L2: LowerLetter, L3: LowerRoman repeat.

## DEPRECATED Options
- `listType`. Predefined numbering styles.
  * See constants above for possible values.

## Used In
- [`Element > ListItem`](../elements/list.md).
- [`Element > ListItemRun`](../elements/list.md).
