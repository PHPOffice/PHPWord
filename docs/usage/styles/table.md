# Table, Row, and Cell

``` php
<?php

$tableStyle = ['borderColor' => '006699', 'borderSize'  => 6, 'cellMargin'  => 50];
$firstRowStyle = ['bgColor' => '66BBFF'];
$phpWord->addTableStyle('myTable', $tableStyle, $firstRowStyle);
$table = $section->addTable('myTable');

```

See [`Sample_09_Tables`](/samples/Sample_09_Tables.php)  and [`Sample_21_TableRowRules`](/samples/Sample_21_TableRowRules.php) for more code samples.

## Table Constants
- Layout
   * `LAYOUT_AUTO`. AutoFit table layout.
   * `LAYOUT_FIXED`. Fixed width table layout.

## Table Options
- `align`. Same as alignment.
- `alignment`. Supports all alignment modes since 1st Edition of ECMA-376 standard up till ISO/IEC 29500:2012.
   * See [`SimpleType > JcTable`](../simpletypes/jctable.md) and [`SimpleType > Jc`](../simpletypes/jc.md) for possible values.
- `bidiVisual` Present table as Right-To-Left
- `bgColor`. Background color, e.g. *9966CC*.
- `border(Top|Right|Bottom|Left|InsideH|InsideV)Color`. Border color, e.g. '9966CC'.
- `border(Top|Right|Bottom|Left|InsideH|InsideV)Size`. Border size in *twip*.
- `cellMargin(Top|Right|Bottom|Left)`. Cell margin in *twip*.
- `cellSpacing` Cell spacing in *twip*
- `columnWidths` Array of widths for each column.
- `indent`. Table indent from leading margin. Must be an instance of `\PhpOffice\PhpWord\ComplexType\TblWidth`.
- `layout`. Table layout, either *fixed* or *autofit*. Defaults to *autofit*.
   * See constants above for possible values.
- `position` Floating Table Positioning, see below for options
- `shading`. Table Shading.
- `width`. Table width in Fiftieths of a Percent or Twentieths of a Point.
- `unit`. The unit to use for the width. Defaults to *auto*.
   * See [`SimpleType > TblWidth`](../simpletypes/tblwidth.md) and [`SimpleType > TblWidth`](../simpletypes/tblwidth.md) for possible values.

### Extends Border
- See [`Style > Border`](../styles/border.md) for additional options.

## Floating Table Positioning Options
- `leftFromText` Distance From Left of Table to Text in *twip*
- `rightFromText` Distance From Right of Table to Text in *twip*
- `topFromText` Distance From Top of Table to Text in *twip*
- `bottomFromText` Distance From Top of Table to Text in *twip*
- `vertAnchor` Table Vertical Anchor, one of `\PhpOffice\PhpWord\Style\TablePosition::VANCHOR_*`
- `horzAnchor` Table Horizontal Anchor, one of `\PhpOffice\PhpWord\Style\TablePosition::HANCHOR_*`
- `tblpXSpec` Relative Horizontal Alignment From Anchor, one of `\PhpOffice\PhpWord\Style\TablePosition::XALIGN_*`
- `tblpX` Absolute Horizontal Distance From Anchorin *twip*
- `tblpYSpec` Relative Vertical Alignment From Anchor, one of `\PhpOffice\PhpWord\Style\TablePosition::YALIGN_*`
- `tblpY` Absolute Vertical Distance From Anchorin *twip*

## Row Options
- `cantSplit`. Table row cannot break across pages, *true* or *false*. Default is *false*.
- `exactHeight`. Row height is exact or at least. Default is *false*.
- `tblHeader`. Repeat table row on every new page, *true* or *false*. Default is *false*.

## Cell Constants
- Text Direction
   * `TEXT_DIR_LRTB`. Left to Right, Top to Bottom.
   * `TEXT_DIR_TBRL`. Top to Bottom, Right to Left.
   * `TEXT_DIR_BTLR`. Bottom to Top, Left to Right.
   * `TEXT_DIR_LRTBV`. Left to Right, Top to Bottom Rotated.
   * `TEXT_DIR_TBRLV`. Top to Bottom, Right to Left Rotated.
   * `TEXT_DIR_TBLRV`. Top to Bottom, Left to Right Rotated.
- Vertical merge (rowspan).
   * `VMERGE_RESTART`.
   * `VMERGE_CONTINUE`.
- Default Border Color.
   * `DEFAULT_BORDER_COLOR`. *000000*.

## Cell Options
- `bgColor`. Background color, e.g. '9966CC'.
- `gridSpan`. Number of columns spanned.
- `noWrap`. Prevent text from wrapping in the cell. Default is *true*.
- `padding(Top|Right|Bottom|Left)`. Cell padding in *twip*.
- `shading`. Cell Shading.
- `textDirection`. Direction of text.
   * See constants above for possible values.
- `valign`. Vertical alignment, *top*, *center*, *both*, *bottom*.
- `vMerge`. *restart* or *continue*.
- `width`. Cell width in *twip*.
- `unit`. The unit to use for the width. Defaults to *auto*.
   * See [`SimpleType > TblWidth`](../simpletypes/tblwidth.md) and [`SimpleType > TblWidth`](../simpletypes/tblwidth.md) for possible values.

### Extends Border
- See [`Style > Border`](../styles/border.md) for additional options.

## Used In
- [`Element > Table`](../elements/table.md).
