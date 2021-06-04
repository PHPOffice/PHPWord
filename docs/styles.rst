.. _styles:

Styles
======

.. _section-style:

Section
-------

Available Section style options:

- ``borderBottomColor``. Border bottom color.
- ``borderBottomSize``. Border bottom size in *twip*.
- ``borderLeftColor``. Border left color.
- ``borderLeftSize``. Border left size in *twip*.
- ``borderRightColor``. Border right color.
- ``borderRightSize``. Border right size in *twip*.
- ``borderTopColor``. Border top color.
- ``borderTopSize``. Border top size in *twip*.
- ``breakType``. Section break type (nextPage, nextColumn, continuous, evenPage, oddPage).
- ``colsNum``. Number of columns.
- ``colsSpace``. Spacing between columns.
- ``footerHeight``. Spacing to bottom of footer.
- ``gutter``. Page gutter spacing.
- ``headerHeight``. Spacing to top of header.
- ``marginTop``. Page margin top in *twip*.
- ``marginLeft``. Page margin left in *twip*.
- ``marginRight``. Page margin right in *twip*.
- ``marginBottom``. Page margin bottom in *twip*.
- ``orientation``. Page orientation (``portrait``, which is default, or ``landscape``).
   See ``\PhpOffice\PhpWord\Style\Section::ORIENTATION_...`` class constants for possible values
- ``pageSizeH``. Page height in *twip*. Implicitly defined by ``orientation`` option. Any changes are discouraged.
- ``pageSizeW``. Page width in *twip*. Implicitly defined by ``orientation`` option. Any changes are discouraged.
- ``vAlign``. Vertical Page Alignment
   See ``\PhpOffice\PhpWord\SimpleType\VerticalJc`` for possible values

.. _font-style:

Font
----

Available Font style options:

- ``allCaps``. All caps, *true* or *false*.
- ``bgColor``. Font background color, e.g. *FF0000*.
- ``bold``. Bold, *true* or *false*.
- ``color``. Font color, e.g. *FF0000*.
- ``doubleStrikethrough``. Double strikethrough, *true* or *false*.
- ``fgColor``. Font highlight color, e.g. *yellow*, *green*, *blue*.
   See ``\PhpOffice\PhpWord\Style\Font::FGCOLOR_...`` class constants for possible values
- ``hint``. Font content type, *default*, *eastAsia*, or *cs*.
- ``italic``. Italic, *true* or *false*.
- ``name``. Font name, e.g. *Arial*.
- ``rtl``. Right to Left language, *true* or *false*.
- ``size``. Font size, e.g. *20*, *22*.
- ``smallCaps``. Small caps, *true* or *false*.
- ``strikethrough``. Strikethrough, *true* or *false*.
- ``subScript``. Subscript, *true* or *false*.
- ``superScript``. Superscript, *true* or *false*.
- ``underline``. Underline, *single*, *dash*, *dotted*, etc.
   See ``\PhpOffice\PhpWord\Style\Font::UNDERLINE_...`` class constants for possible values
- ``lang``. Language, either a language code like *en-US*, *fr-BE*, etc. or an object (or as an array) if you need to set eastAsian or bidirectional languages
   See ``\PhpOffice\PhpWord\Style\Language`` class for some language codes.
- ``position``. The text position, raised or lowered, in half points
- ``hidden``. Hidden text, *true* or *false*.

.. _paragraph-style:

Paragraph
---------

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

.. _table-style:

Table
-----

Available Table style options:

- ``alignment``. Supports all alignment modes since 1st Edition of ECMA-376 standard up till ISO/IEC 29500:2012.
   See ``\PhpOffice\PhpWord\SimpleType\JcTable`` and ``\PhpOffice\PhpWord\SimpleType\Jc`` class constants for possible values.
- ``bgColor``. Background color, e.g. '9966CC'.
- ``border(Top|Right|Bottom|Left)Color``. Border color, e.g. '9966CC'.
- ``border(Top|Right|Bottom|Left)Size``. Border size in *twip*.
- ``cellMargin(Top|Right|Bottom|Left)``. Cell margin in *twip*.
- ``indent``. Table indent from leading margin. Must be an instance of ``\PhpOffice\PhpWord\ComplexType\TblWidth``.
- ``width``. Table width in Fiftieths of a Percent or Twentieths of a Point.
- ``unit``. The unit to use for the width. One of ``\PhpOffice\PhpWord\SimpleType\TblWidth``. Defaults to *auto*.
- ``layout``. Table layout, either *fixed* or *autofit*  See ``\PhpOffice\PhpWord\Style\Table`` for constants.
- ``cellSpacing`` Cell spacing in *twip*
- ``position`` Floating Table Positioning, see below for options
- ``bidiVisual`` Present table as Right-To-Left

Floating Table Positioning options:

- ``leftFromText`` Distance From Left of Table to Text in *twip*
- ``rightFromText`` Distance From Right of Table to Text in *twip*
- ``topFromText`` Distance From Top of Table to Text in *twip*
- ``bottomFromText`` Distance From Top of Table to Text in *twip*
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

- ``bgColor``. Background color, e.g. '9966CC'.
- ``border(Top|Right|Bottom|Left)Color``. Border color, e.g. '9966CC'.
- ``border(Top|Right|Bottom|Left)Size``. Border size in *twip*.
- ``border(Top|Right|Bottom|Left)Style``. Border style. You can use constants from ``\PhpOffice\PhpWord\SimpleType\Border``
- ``gridSpan``. Number of columns spanned.
- ``textDirection(btLr|tbRl)``. Direction of text.
   You can use constants ``\PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR`` and ``\PhpOffice\PhpWord\Style\Cell::TEXT_DIR_TBRL``
- ``valign``. Vertical alignment, *top*, *center*, *both*, *bottom*.
- ``vMerge``. *restart* or *continue*.
- ``width``. Cell width in *twip*.

.. _image-style:

Image
-----

Available Image style options:

- ``alignment``. See ``\PhpOffice\PhpWord\SimpleType\Jc`` class for the details.
- ``height``. Height in *pt*.
- ``marginLeft``. Left margin in inches, can be negative.
- ``marginTop``. Top margin in inches, can be negative.
- ``width``. Width in *pt*.
- ``wrappingStyle``. Wrapping style, *inline*, *square*, *tight*, *behind*, or *infront*.
- ``wrapDistanceTop``. Top text wrapping in pixels.
- ``wrapDistanceBottom``. Bottom text wrapping in pixels.
- ``wrapDistanceLeft``. Left text wrapping in pixels.
- ``wrapDistanceRight``. Right text wrapping in pixels.

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

- ``width``. Width (in EMU).
- ``height``. Height (in EMU).
- ``3d``. Is 3D; applies to pie, bar, line, area, *true* or *false*.
- ``colors``. A list of colors to use in the chart.
- ``title``. The title for the chart.
- ``showLegend``. Show legend, *true* or *false*.
- ``LegendPosition``. Legend position, *r* (default), *b*, *t*, *l* or *tr*.
- ``categoryLabelPosition``. Label position for categories, *nextTo* (default), *low* or *high*.
- ``valueLabelPosition``. Label position for values, *nextTo* (default), *low* or *high*.
- ``categoryAxisTitle``. The title for the category axis.
- ``valueAxisTitle``. The title for the values axis.
- ``majorTickMarkPos``. The position for major tick marks, *in*, *out*, *cross*, *none* (default).
- ``showAxisLabels``. Show labels for axis, *true* or *false*.
- ``gridX``. Show Gridlines for X-Axis, *true* or *false*.
- ``gridY``. Show Gridlines for Y-Axis, *true* or *false*.
