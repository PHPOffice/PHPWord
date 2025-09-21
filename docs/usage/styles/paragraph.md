# Paragraph
- Extends Border
   * See [`\PhpOffice\PhpWord\Style\Border`](/docs/usage/styles/border.md) for additional options.

## Constants
- Orientation
   * `LINE_HEIGHT` = `240`
   * `ORIENTATION_LANDSCAPE`

## Options
- `alignment`. Supports all alignment modes since 1st Edition of ECMA-376 standard up till ISO/IEC 29500:2012.
   * See [`\PhpOffice\PhpWord\SimpleType\Jc`](/docs/usage/simpletypes/jc.md) for possible values.
- `basedOn`. Parent style.
- `bidi`. Right to Left Paragraph Layout, *true* or *false*.
- `contextualSpacing`. Ignore Spacing Above and Below When Using Identical Styles, *true* or *false*.
- `hanging`. Hanging indentation in *half inches*.
- `indentation`. An array of indentation key => value pairs in *twip*. Supports *left*, *right*, *firstLine*, *firstLineChars* and *hanging* indentation.
   See `\PhpOffice\PhpWord\Style\Indentation` for possible identation types.
- `indentHanging`. Hanging indentation in *half inches*.
- `indentFirstLine`. First line indentation in *half inches*.
- `indentFirstLineChars`. First line character indentation in *half inches*.
- `indentLeft`. Left indentation in *half inches*.
- `indentRight`. Right indentation in *half inches*.
- `keepLines`. Keep all lines on one page, *true* or *false*.
- `keepNext`. Keep paragraph with next paragraph, *true* or *false*.
- `lineHeight`. Text line height, e.g. *1.0*, *1.5*, etc.
- `next`. Style for next paragraph.
- `numLevel`. Numbering level. `0` is default.
- `numStyle`. Numbering style name
   * See [`\PhpOffice\PhpWord\Style\Numbering`](/docs/usage/styles/numbering.md).
- `pageBreakBefore`. Start paragraph on next page, *true* or *false*.
- `shading`. Paragraph Shading.
- `spaceAfter`. Space after paragraph in *twip*.
- `spaceBefore`. Space before paragraph in *twip*.
- `spacing`. Space between lines in *twip*. If spacingLineRule is auto, 240 (height of 1 line) will be added, so if you want a double line height, set this to 240.
   * See [`\PhpOffice\PhpWord\SimpleType\LineSpacingRule`](/docs/usage/simpletype/linespacingrule.md) class constants for possible values.
- `spacingLineRule`. Line Spacing Rule. *auto*, *exact*, *atLeast*
   * See [`\PhpOffice\PhpWord\SimpleType\LineSpacingRule`](/docs/usage/simpletype/linespacingrule.md) class constants for possible values.
- `suppressAutoHyphens`. Hyphenation for paragraph, *true* or *false*.
- `tabs`. Set of custom tab stops.
   * See [`\PhpOffice\PhpWord\Style\Tab`](/docs/usage/styles/tabs.md).
- `widowControl`. Allow first/last line to display on a separate page, *true* or *false*.
- `textAlignment`. Vertical Character Alignment on Line.
   * See [`\PhpOffice\PhpWord\SimpleType\TextAlignment`](/docs/usage/simpletypes/textalignment.md) class constants for possible values.
