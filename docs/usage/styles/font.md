# Font Styles

``` php
<?php

$phpWord->addFontStyle('title', ['color' => '#FF0000', 'size' => 32]);

```

``` php
<?php

use PhpOffice\PhpWord\Style\Font as FontStyle;
$fontStyle = ['name' => 'Arial', 'bold' => true, 'underline' => FontStyle::UNDERLINE_DASH];
$section->addText('Hello, world!', $fontStyle);

```

See [`Sample_01_SimpleText`](/samples/Sample_01_SimpleText.php) for more code samples.

## Constants
- Underline
   * `UNDERLINE_NONE`
   * `UNDERLINE_DASH`, `UNDERLINE_DASHHEAVY`,
   * `UNDERLINE_DASHLONG`, `UNDERLINE_DASHLONGHEAVY`
   * `UNDERLINE_SINGLE`, `UNDERLINE_DOUBLE`, `UNDERLINE_HEAVY`
   * `UNDERLINE_DOTDASH`, `UNDERLINE_DOTDASHHEAVY`
   * `UNDERLINE_DOTDOTDASH`, `UNDERLINE_DOTDOTDASHHEAVY`
   * `UNDERLINE_DOTTED`, `UNDERLINE_DOTTEDHEAVY`
   * `UNDERLINE_WAVY`, `UNDERLINE_WAVYDOUBLE`, `UNDERLINE_WAVYHEAVY`
   * `UNDERLINE_WORDS`
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
   * See [`Style >Language`](../styles/language.md) class for some language codes.
- `lineHeight`. Line height.
- `paragraph`. Paragraph.
   * See [`Style >Paragraph`](../styles/paragraph.md).
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

## Used In
- [`Element > CheckBox`](../elements/checkbox.md).
- [`Element > Field`](../elements/field.md).
- [`Element > FormField`](../elements/formfield.md).
- [`Element > Link`](../elements/link.md).
- [`Element > ListItem`](../elements/list.md).
- [`Element > PreserveText`](../elements/preservetext.md).
- [`Element > SDT`](../elements/sdt.md).
- [`Element > Text`](../elements/text.md).
- [`Element > TextBreak`](../elements/textbreak.md).
- [`Element > TOC`](../elements/toc.md).
