# Paragraph Styles

``` php
<?php

$phpWord->addParagraphStyle('header', ['align' => 'center', 'spaceAfter' => 240]);
$section->addText('Hello, World!', null, 'header');

```

``` php
<?php

use PhpOffice\PhpWord\SimpleType\Jc as JcType;
$paragraphStyle = ['align' => JcType::BOTH, 'keepNext' => true];
$section->addText('Hello, World!', null, $paragraphStyle);

```

See [`Sample_01_SimpleText`](/samples/Sample_01_SimpleText.php) and [`Sample_08_ParagraphPagination`](/samples/Sample_08_ParagraphPagination.php) for more code samples.

## Constants
- `LINE_HEIGHT` = `240`

## Options
- `align`. Same as alignment.
- `alignment`. Supports all alignment modes since 1st Edition of ECMA-376 standard up till ISO/IEC 29500:2012.
   * See [`SimpleType > Jc`](../simpletypes/jc.md) for possible values.
- `basedOn`. Parent style.
- `bidi`. Right to Left Paragraph Layout, *true* or *false*.
- `contextualSpacing`. Ignore Spacing Above and Below When Using Identical Styles, *true* or *false*.
- `hanging`. Hanging indentation in *half inches*.
- `indentation`. An array of indentation key => value pairs in *twip*. Supports *left*, *right*, *firstLine*, *firstLineChars* and *hanging* indentation.
   * See [`Style > Indentation`](../styles/indentation.md) for possible identation types.
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
   * See [`Style > Numbering`](../styles/numbering.md).
- `pageBreakBefore`. Start paragraph on next page, *true* or *false*.
- `shading`. Paragraph Shading.
- `spaceAfter`. Space after paragraph in *twip*.
- `spaceBefore`. Space before paragraph in *twip*.
- `spacing`. Space between lines in *twip*. If spacingLineRule is auto, 240 (height of 1 line) will be added, so if you want a double line height, set this to 240.
- `spacingLineRule`. Line Spacing Rule. *auto*, *exact*, *atLeast*
   * See [`SimpleType > LineSpacingRule`](../simpletypes/linespacingrule.md) class constants for possible values.
- `suppressAutoHyphens`. Hyphenation for paragraph, *true* or *false*.
- `tabs`. Set of custom tab stops.
   * See [`Style > Tab`](../styles/tabs.md).
- `widowControl`. Allow first/last line to display on a separate page, *true* or *false*.
- `textAlignment`. Vertical Character Alignment on Line.
   * See [`SimpleType > TextAlignment`](/docs/usage/simpletypes/textalignment.md) class constants for possible values.

### Extends Border
- See [`Style > Border`](../styles/border.md) for additional options.

## Used In
- [`Element > CheckBox`](../elements/checkbox.md).
- [`Element > Endnote`](../elements/endnote.md).
- [`Element > Footnote`](../elements/footnote.md).
- [`Element > FormField`](../elements/formfield.md).
- [`Element > Link`](../elements/link.md).
- [`Element > ListItem`](../elements/list.md).
- [`Element > ListItemRun`](../elements/list.md).
- [`Element > PreserveText`](../elements/preservetext.md).
- [`Element > SDT`](../elements/sdt.md).
- [`Element > Text`](../elements/text.md).
- [`Element > TextBreak`](../elements/textbreak.md).
- [`Element > TextRun`](../elements/text.md).
