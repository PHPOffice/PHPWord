# Section
- Extends Border
   * See [`\PhpOffice\PhpWord\Style\Border`](/docs/usage/styles/border) for additional settings.

## Constants
- Orientation
   * `ORIENTATION_PORTRAIT`
   * `ORIENTATION_LANDSCAPE`

## Paper Setup
- `orientation`. Page orientation. `ORIENTATION_PORTRAIT` is default.
   * See constants above for possible values.
- `paper`. Paper size.  `A4` is default.
   * See `\PhpOffice\PhpWord\Style\Paper` for possible values
- `pageSizeH`. Page height in *twip*. `16837.79527559` is default.
- `pageSizeW`. Page width in *twip*. `11905.511811024` is default.

## Section Setup
- `headerHeight`. Spacing to top of header. `720` is default.
- `footerHeight`. Spacing to bottom of footer. `720` is default.
- `breakType`. Section break type (`nextPage`, `nextColumn`, `continuous`, `evenPage`, `oddPage`).
- `pageNumberingStart`. Starting page number.
- `lineNumbering`. Line numbering.
   * See [`\PhpOffice\PhpWord\Style\LineNumbering`](/docs/usage/styles/linenumbering) for possible values.
- `vAlign`. Vertical page alignment.
   * See [`\PhpOffice\PhpWord\SimpleType\VerticalJc`](/docs/usage/simpletypes/verticaljc) for possible values.

## Columns
- `colsNum`. Number of columns. `1` is default.
- `colsSpace`. Spacing between columns. `720` is default.
