# Section Styles

``` php
<?php

use PhpOffice\PhpWord\SimpleTypes\VerticalJC as VerticalType;
$sectionStyle = ['paperSize' => 'Letter', 'vAlign' => VerticalType::CENTER];
$section = $phpWord->addSection($sectionStyle);
```

See [`Sample_03_Sections`](/samples/Sample_03_Sections.php) for more code samples.

## Constants
- Orientation
   * `ORIENTATION_PORTRAIT`
   * `ORIENTATION_LANDSCAPE`

## Options
- `breakType`. Section break type (`nextPage`, `nextColumn`, `continuous`, `evenPage`, `oddPage`).
- `colsNum`. Number of columns. `1` is default.
- `colsSpace`. Spacing between columns. `720` is default.
- `footerHeight`. Spacing to bottom of footer. `720` is default.
- `gutter`. Page gutter spacing. `0` is default.
- `headerHeight`. Spacing to top of header. `720` is default.
- `lineNumbering`. Line numbering.
   * See [`Style > LineNumbering`](../styles/linenumbering.md) for possible values.
- `orientation`. Page orientation. `ORIENTATION_PORTRAIT` is default.
   * **IMPORTANT**: Orientation must be set *after* paperSize, pageSizeH, and pageSizeW to work.
   * See constants above for possible values.
- `pageNumberingStart`. Starting page number.
- `pageSizeH`. Page height in *twip*. `16837.79527559` is default.
- `pageSizeW`. Page width in *twip*. `11905.511811024` is default.
- `paperSize`. Paper size.  `A4` is default.
   * See [`Style > Paper`](../styles/paper.md) for possible values
- `vAlign`. Vertical page alignment.
   * See [`SimpleType > VerticalJc`](../simpletypes/verticaljc.md) for possible values.

### Extends Border
- See [`Style > Border`](../styles/border.md) for additional options.

## Used In
- [`Element > Section`](../elements/section.md).
