# ListItem Styles

``` php
<?php

use PhpOffice\PhpWord\Style\ListItem as ListStyle;
$listStyle = ['listType' => ListStyle::TYPE_NUMBER_NESTED];
$section->addListItem('List Item 1', 0, null, $listStyle);
```

See [`Sample_14_ListItem`](/samples/Sample_14_ListItem.php) for more code samples.

## Constants
- `TYPE_SQUARE_FILLED`.
- `TYPE_BULLET_FILLED`.
- `TYPE_BULLET_EMPTY`.
- `TYPE_NUMBER`.
- `TYPE_NUMBER_NESTED`. L1: 1., L2: 1.1., L3: 1.1.1. etc.
- `TYPE_ALPHANUM`. L1: Decimal, L2: LowerLetter, L3: LowerRoman repeat.

## Options
- `listType`. Predefined numbering styles.
  * See constants above for possible values.
- `numStyle`.
   * See [`Styles > Numbering`](../styles/numbering.md) for possible values.

## Used In
- [`Element > ListItem`](../elements/list.md).
- [`Element > ListItemRun`](../elements/list.md).
