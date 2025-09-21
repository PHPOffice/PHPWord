# Font

## Constants
- Underline
   * `UNDERLINE_NONE`
   * `ORIENTATION_DASH`, `ORIENTATION_DASHHEAVY`, `ORIENTATION_DASHLONG`, `ORIENTATION_DASHLONGHEAVY`
   * `ORIENTATION_SINGLE`, `ORIENTATION_DOUBLE`, `ORIENTATION_HEAVY`
   * `ORIENTATION_DOTDASH`, `ORIENTATION_DOTDASHHEAVY`, `ORIENTATION_DOTDOTDASH`, `ORIENTATION_DOTDOTDASHHEAVY`
   * `ORIENTATION_DOTTED`, `ORIENTATION_DOTTEDHEAVY`
   * `ORIENTATION_WAVY`, `ORIENTATION_WAVYDOUBLE`, `ORIENTATION_WAVYHEAVY`
   * `ORIENTATION_WORDS`
- Foreground Color
   * `FGCOLOR_YELLOW`, `FGCOLOR_LIGHTGREEN`, `FGCOLOR_CYAN`, `FGCOLOR_MAGENTA`, `FGCOLOR_BLUE`, `FGCOLOR_RED`
   * `FGCOLOR_DARKBLUE`, `FGCOLOR_DARKCYAN`, `FGCOLOR_DARKGREEN`, `FGCOLOR_DARKMAGENTA`, `FGCOLOR_DARKRED`, `FGCOLOR_DARKYELLOW`
   * `FGCOLOR_DARKGRAY`, `FGCOLOR_LIGHTGRAY`, `FGCOLOR_BLACK`

## Options
- `allCaps`. All caps, *true* or *false*.
- `bgColor`. Font background color, e.g. *FF0000*.
- `bold`. Bold, *true* or *false*.
- `color`. Font color, e.g. *FF0000*.
- `doubleStrikethrough`. Double strikethrough, *true* or *false*.
- `fallbackFont`. Fallback generic font for html/pdf. Possible values are *sans-serif*, *serif*, and *monospace* (other css values for generic fonts are accepted).
- `fgColor`. Font highlight color, e.g. *yellow*, *green*, *blue*.
   * See constants above for possible values.
- `hidden`. Hidden text, *true* or *false*.
- `hint`. Font content type, *default*, *eastAsia*, or *cs*.
- `italic`. Italic, *true* or *false*.
- `kerning`. Font kerning: halfpoint.
- `lang`. Language, either a language code like *en-US*, *fr-BE*, etc. or an object (or as an array) if you need to set eastAsian or bidirectional languages
   * See [`\PhpOffice\PhpWord\Style\Language`](/docs/usage/styles/language.md) class for some language codes.
- `lineHeight`. Line height.
- `paragraph`. Paragraph.
   * See [`\PhpOffice\PhpWord\Style\Paragraph`](/docs/usage/styles/paragraph.md).
- `name`. Font name, e.g. *Arial*.
- `noProof`. Disable AutoCorrect, *true* or *false*.
- `position`. The text position, raised or lowered, in half points
- `rtl`. Right to Left language, *true* or *false*.
- `scale`. Expande/compress text.
- `shading`. Shading.
- `size`. Font size, e.g. *20*, *22*.
- `smallCaps`. Small caps, *true* or *false*.
- `spacing`. Characters spacing adjustment in *twip*.
- `strikethrough`. Strikethrough, *true* or *false*.
- `subScript`. Subscript, *true* or *false*.
- `superScript`. Superscript, *true* or *false*.
- `underline`. Underline, *single*, *dash*, *dotted*, etc. `UNDERLINE_NONE` is default.
   * See constants above for possible values.
- `whiteSpace`. How white space is handled when generating html/pdf. Possible values are *pre-wrap* and *normal* (other css values for white space are accepted, but are not expected to be useful).
