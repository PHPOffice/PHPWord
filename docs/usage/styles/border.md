# Border Styles

``` php
<?php

$tableStyleName = 'Basic Table'
$tableStyle = ['borderSize' => 10, 'borderColor' => '#FF0000', 'margin' => 50];
$phpWord->addTableStyle($tableStyleName, $tableStyle)
$table = $section->addTable($tableStyleName);
```

See [`Sample_03_Sections`](/samples/Sample_03_Sections.php) and [`Sample_09_Tables`](/samples/Sample_09_Tables.php) for more code samples.

## Options
- `border(Top|Right|Bottom|Left)Size`. Border size in *twip*.
- `border(Top|Right|Bottom|Left)Color`. Border color, eg '9966CC'.
- `border(Top|Right|Bottom|Left)Style`. Border style.
  * See [`SimpleType > Border`](../simpletypes/border.md) class constants for possible values.
- `margin(Top|Right|Bottom|Left)`. Page margin in *twip*. `1440` is default.

## Used In
- [`Style > Cell`](../styles/cell.md).
- [`Style > Paragraph`](../styles/paragraph.md).
- [`Style > Section`](../styles/section.md).
- [`Style > Table`](../styles/table.md).
