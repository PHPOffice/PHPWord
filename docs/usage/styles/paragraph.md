# Paragraph

Available Paragraph style options:

- ``alignment``. Supports all alignment modes since 1st Edition of ECMA-376 standard up till ISO/IEC 29500:2012.
   See ``\PhpOffice\PhpWord\SimpleType\Jc`` class constants for possible values.
- ``basedOn``. Parent style.
- ``hanging``. Hanging indentation in *half inches*.
- ``indent``. Indent (left indentation) in *half inches*.
- ``indentation``. An array of indentation key => value pairs in *twip*. Supports *left*, *right*, *firstLine* and *hanging* indentation.
   See ``\PhpOffice\PhpWord\Style\Indentation`` for possible identation types.
- ``keepLines``. Keep all lines on one page, *true* or *false*.
- ``keepNext``. Keep paragraph with next paragraph, *true* or *false*.
- ``lineHeight``. Text line height, e.g. *1.0*, *1.5*, etc.
- ``next``. Style for next paragraph.
- ``pageBreakBefore``. Start paragraph on next page, *true* or *false*.
- ``spaceBefore``. Space before paragraph in *twip*.
- ``spaceAfter``. Space after paragraph in *twip*.
- ``spacing``. Space between lines in *twip*. If spacingLineRule is auto, 240 (height of 1 line) will be added, so if you want a double line height, set this to 240.
- ``spacingLineRule``. Line Spacing Rule. *auto*, *exact*, *atLeast*
   See ``\PhpOffice\PhpWord\SimpleType\LineSpacingRule`` class constants for possible values.
- ``suppressAutoHyphens``. Hyphenation for paragraph, *true* or *false*.
- ``tabs``. Set of custom tab stops.
- ``widowControl``. Allow first/last line to display on a separate page, *true* or *false*.
- ``contextualSpacing``. Ignore Spacing Above and Below When Using Identical Styles, *true* or *false*.
- ``bidi``. Right to Left Paragraph Layout, *true* or *false*.
- ``shading``. Paragraph Shading.
- ``textAlignment``. Vertical Character Alignment on Line.
   See ``\PhpOffice\PhpWord\SimpleType\TextAlignment`` class constants for possible values.