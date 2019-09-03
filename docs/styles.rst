.. _styles:

Styles
======

.. _section-style:

Section
-------

Available Section style options:

- ``borderBottomColor``. Border bottom color (``PhpOffice\PhpWord\Style\Colors\BasicColor``).
- ``borderBottomSize``. Border bottom size (``PhpOffice\PhpWord\Style\Lengths\Absolute``).
- ``borderLeftColor``. Border left color (``PhpOffice\PhpWord\Style\Colors\BasicColor``).
- ``borderLeftSize``. Border left size (``PhpOffice\PhpWord\Style\Lengths\Absolute``).
- ``borderRightColor``. Border right color (``PhpOffice\PhpWord\Style\Colors\BasicColor``).
- ``borderRightSize``. Border right size (``PhpOffice\PhpWord\Style\Lengths\Absolute``).
- ``borderTopColor``. Border top color (``PhpOffice\PhpWord\Style\Colors\BasicColor``).
- ``borderTopSize``. Border top size (``PhpOffice\PhpWord\Style\Lengths\Absolute``).
- ``breakType``. Section break type (nextPage, nextColumn, continuous, evenPage, oddPage).
- ``colsNum``. Number of columns.
- ``colsSpace``. Spacing between columns (``PhpOffice\PhpWord\Style\Lengths\Absolute``).
- ``footerHeight``. Spacing to bottom of footer (``PhpOffice\PhpWord\Style\Lengths\Absolute``).
- ``gutter``. Page gutter spacing (``PhpOffice\PhpWord\Style\Lengths\Absolute``).
- ``headerHeight``. Spacing to top of header (``PhpOffice\PhpWord\Style\Lengths\Absolute``).
- ``marginTop``. Page margin top (``PhpOffice\PhpWord\Style\Lengths\Absolute``).
- ``marginLeft``. Page margin left (``PhpOffice\PhpWord\Style\Lengths\Absolute``).
- ``marginRight``. Page margin right (``PhpOffice\PhpWord\Style\Lengths\Absolute``).
- ``marginBottom``. Page margin bottom (``PhpOffice\PhpWord\Style\Lengths\Absolute``).
- ``orientation``. Page orientation (``portrait``, which is default, or ``landscape``).
   See ``\PhpOffice\PhpWord\Style\Section::ORIENTATION_...`` class constants for possible values
- ``pageSizeH``. Page height (``PhpOffice\PhpWord\Style\Lengths\Absolute``). Implicitly defined by ``orientation`` option. Any changes are discouraged.
- ``pageSizeW``. Page width (``PhpOffice\PhpWord\Style\Lengths\Absolute``). Implicitly defined by ``orientation`` option. Any changes are discouraged.
- ``vAlign``. Vertical Page Alignment
   See ``\PhpOffice\PhpWord\SimpleType\VerticalJc`` for possible values

.. _font-style:

Font
----

Available Font style options:

- ``allCaps``. All caps, *true* or *false*.
- ``bgColor``. Font background color (``\PhpOffice\PhpWord\Style\Colors\BasicColor``).
- ``bold``. Bold, *true* or *false*.
- ``color``. Font color (``\PhpOffice\PhpWord\Style\Colors\BasicColor``).
- ``doubleStrikethrough``. Double strikethrough, *true* or *false*.
- ``fgColor``. Font highlight color (``\PhpOffice\PhpWord\Style\Colors\HighlightColor``).
- ``hint``. Font content type, *default*, *eastAsia*, or *cs*.
- ``italic``. Italic, *true* or *false*.
- ``name``. Font name, e.g. *Arial*.
- ``rtl``. Right to Left language, *true* or *false*.
- ``size``. Font size (``\PhpOffice\PhpWord\Style\Lengths\Absolute``).
- ``smallCaps``. Small caps, *true* or *false*.
- ``strikethrough``. Strikethrough, *true* or *false*.
- ``subScript``. Subscript, *true* or *false*.
- ``superScript``. Superscript, *true* or *false*.
- ``underline``. Underline, *single*, *dash*, *dotted*, etc.
   See ``\PhpOffice\PhpWord\Style\Font::UNDERLINE_...`` class constants for possible values
- ``lang``. Language, either a language code like *en-US*, *fr-BE*, etc. or an object (or as an array) if you need to set eastAsian or bidirectional languages
   See ``\PhpOffice\PhpWord\Style\Language`` class for some language codes.
- ``position``. The text position, raised or lowered (``\PhpOffice\PhpWord\Style\Lengths\Absolute``)
- ``hidden``. Hidden text, *true* or *false*.

.. _paragraph-style:

Paragraph
---------

Available Paragraph style options:

- ``alignment``. Supports all alignment modes since 1st Edition of ECMA-376 standard up till ISO/IEC 29500:2012.
   See ``\PhpOffice\PhpWord\SimpleType\Jc`` class constants for possible values.
- ``basedOn``. Parent style.
- ``hanging``. Hanging (``PhpOffice\PhpWord\Style\Lengths\Absolute``).
- ``indent``. Indent (``PhpOffice\PhpWord\Style\Lengths\Absolute``).
- ``keepLines``. Keep all lines on one page, *true* or *false*.
- ``keepNext``. Keep paragraph with next paragraph, *true* or *false*.
- ``lineHeight``. Text line height, e.g. *1.0*, *1.5*, etc.
- ``next``. Style for next paragraph.
- ``pageBreakBefore``. Start paragraph on next page, *true* or *false*.
- ``spaceBefore``. Space before paragraph (``PhpOffice\PhpWord\Style\Lengths\Absolute``).
- ``spaceAfter``. Space after paragraph (``PhpOffice\PhpWord\Style\Lengths\Absolute``).
- ``spacing``. Space between lines. If spacingLineRule is auto, 1 will be added, so if you want a double line height, set this to 2.
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

.. _table-style:

Table
-----

Available Table style options:

- ``alignment``. Supports all alignment modes since 1st Edition of ECMA-376 standard up till ISO/IEC 29500:2012.
   See ``\PhpOffice\PhpWord\SimpleType\JcTable`` and ``\PhpOffice\PhpWord\SimpleType\Jc`` class constants for possible values.
- ``bgColor``. Background color (``PhpOffice\PhpWord\Style\Colors\BasicColor``).
- ``border(Top|Right|Bottom|Left)Color``. Border color (``PhpOffice\PhpWord\Style\Colors\BasicColor``).
- ``border(Top|Right|Bottom|Left)Size``. Border size (``PhpOffice\PhpWord\Style\Lengths\Absolute``).
- ``cellMargin(Top|Right|Bottom|Left)``. Cell margin (``PhpOffice\PhpWord\Style\Lengths\Absolute``).
- ``indent``. Table indent from leading margin (``PhpOffice\PhpWord\Style\Lengths\Length``).
- ``width``. Table width (``PhpOffice\PhpWord\Style\Lengths\Length``).
- ``layout``. Table layout, either *fixed* or *autofit*  See ``\PhpOffice\PhpWord\Style\Table`` for constants.
- ``cellSpacing`` Cell spacing (``PhpOffice\PhpWord\Style\Lengths\Absolute``).
- ``position`` Floating Table Positioning, see below for options
- ``bidiVisual`` Present table as Right-To-Left

Floating Table Positioning options:

- ``leftFromText`` Distance From Left of Table to Text (``PhpOffice\PhpWord\Style\Lengths\Absolute``)
- ``rightFromText`` Distance From Right of Table to Text (``PhpOffice\PhpWord\Style\Lengths\Absolute``)
- ``topFromText`` Distance From Top of Table to Text (``PhpOffice\PhpWord\Style\Lengths\Absolute``)
- ``bottomFromText`` Distance From Top of Table to Text (``PhpOffice\PhpWord\Style\Lengths\Absolute``)
- ``vertAnchor`` Table Vertical Anchor, one of ``\PhpOffice\PhpWord\Style\TablePosition::VANCHOR_*``
- ``horzAnchor`` Table Horizontal Anchor, one of ``\PhpOffice\PhpWord\Style\TablePosition::HANCHOR_*``
- ``tblpXSpec`` Relative Horizontal Alignment From Anchor, one of ``\PhpOffice\PhpWord\Style\TablePosition::XALIGN_*``
- ``tblpX`` Absolute Horizontal Distance From Anchorin *twip*
- ``tblpYSpec`` Relative Vertical Alignment From Anchor, one of ``\PhpOffice\PhpWord\Style\TablePosition::YALIGN_*``
- ``tblpY`` Absolute Vertical Distance From Anchorin *twip*

Available Row style options:

- ``cantSplit``. Table row cannot break across pages, *true* or *false*.
- ``exactHeight``. Row height is exact or at least.
- ``tblHeader``. Repeat table row on every new page, *true* or *false*.

Available Cell style options:

- ``bgColor``. Background color (``PhpOffice\PhpWord\Style\Colors\BasicColor``).
- ``border(Top|Right|Bottom|Left)Color``. Border color (``PhpOffice\PhpWord\Style\Colors\BasicColor``).
- ``border(Top|Right|Bottom|Left)Size``. Border size (``PhpOffice\PhpWord\Style\Lengths\Absolute``).
- ``gridSpan``. Number of columns spanned.
- ``textDirection(btLr|tbRl)``. Direction of text.
   You can use constants ``\PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR`` and ``\PhpOffice\PhpWord\Style\Cell::TEXT_DIR_TBRL``
- ``valign``. Vertical alignment, *top*, *center*, *both*, *bottom*.
- ``vMerge``. *restart* or *continue*.
- ``width``. Cell width (``PhpOffice\PhpWord\Style\Lengths\Length``).

.. _image-style:

Image
-----

Available Image style options:

- ``alignment``. See ``\PhpOffice\PhpWord\SimpleType\Jc`` class for the details.
- ``height``. Height in *pt*.
- ``marginLeft``. Left margin, can be negative (``PhpOffice\PhpWord\Style\Lengths\Absolute``).
- ``marginTop``. Top margin, can be negative (``PhpOffice\PhpWord\Style\Lengths\Absolute``).
- ``width``. Width (``PhpOffice\PhpWord\Style\Lengths\Absolute``).
- ``wrappingStyle``. Wrapping style, *inline*, *square*, *tight*, *behind*, or *infront*.
- ``wrapDistanceTop``. Top text wrapping (``PhpOffice\PhpWord\Style\Lengths\Absolute``).
- ``wrapDistanceBottom``. Bottom text wrapping (``PhpOffice\PhpWord\Style\Lengths\Absolute``).
- ``wrapDistanceLeft``. Left text wrapping (``PhpOffice\PhpWord\Style\Lengths\Absolute``).
- ``wrapDistanceRight``. Right text wrapping (``PhpOffice\PhpWord\Style\Lengths\Absolute``).

.. _numbering-level-style:

Numbering level
---------------

Available NumberingLevel style options:

- ``alignment``. Supports all alignment modes since 1st Edition of ECMA-376 standard up till ISO/IEC 29500:2012.
   See ``\PhpOffice\PhpWord\SimpleType\Jc`` class constants for possible values.
- ``font``. Font name.
- ``format``. Numbering format bullet\|decimal\|upperRoman\|lowerRoman\|upperLetter\|lowerLetter.
- ``hanging``. See paragraph style.
- ``hint``. See font style.
- ``left``. See paragraph style.
- ``restart``. Restart numbering level symbol.
- ``start``. Starting value.
- ``suffix``. Content between numbering symbol and paragraph text tab\|space\|nothing.
- ``tabPos``. See paragraph style.
- ``text``. Numbering level text e.g. %1 for nonbullet or bullet character.

.. _chart-style:

Chart
-----

Available Chart style options:

- ``width``. Width (``PhpOffice\PhpWord\Style\Lengths\Absolute``).
- ``height``. Height (``PhpOffice\PhpWord\Style\Lengths\Absolute``).
- ``3d``. Is 3D; applies to pie, bar, line, area, *true* or *false*.
- ``colors``. A list of colors to use in the chart.
- ``title``. The title for the chart.
- ``showLegend``. Show legend, *true* or *false*.
- ``categoryLabelPosition``. Label position for categories, *nextTo* (default), *low* or *high*.
- ``valueLabelPosition``. Label position for values, *nextTo* (default), *low* or *high*.
- ``categoryAxisTitle``. The title for the category axis.
- ``valueAxisTitle``. The title for the values axis.
- ``majorTickMarkPos``. The position for major tick marks, *in*, *out*, *cross*, *none* (default).
- ``showAxisLabels``. Show labels for axis, *true* or *false*.
- ``gridX``. Show Gridlines for X-Axis, *true* or *false*.
- ``gridY``. Show Gridlines for Y-Axis, *true* or *false*.
