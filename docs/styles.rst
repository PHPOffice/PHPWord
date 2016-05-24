.. _styles:

Styles
======

.. _section-style:

Section
-------

Available Section style options:

- ``borderBottomColor``. Border bottom color.
- ``borderBottomSize``. Border bottom size (in twips).
- ``borderLeftColor``. Border left color.
- ``borderLeftSize``. Border left size (in twips).
- ``borderRightColor``. Border right color.
- ``borderRightSize``. Border right size (in twips).
- ``borderTopColor``. Border top color.
- ``borderTopSize``. Border top size (in twips).
- ``breakType``. Section break type (nextPage, nextColumn, continuous, evenPage, oddPage).
- ``colsNum``. Number of columns.
- ``colsSpace``. Spacing between columns.
- ``footerHeight``. Spacing to bottom of footer.
- ``gutter``. Page gutter spacing.
- ``headerHeight``. Spacing to top of header.
- ``marginTop``. Page margin top (in twips).
- ``marginLeft``. Page margin left (in twips).
- ``marginRight``. Page margin right (in twips).
- ``marginBottom``. Page margin bottom (in twips).
- ``orientation``. Page orientation (``portrait``, which is default, or ``landscape``).
- ``pageSizeH``. Page height (in twips). Implicitly defined by ``orientation`` option. Any changes are discouraged.
- ``pageSizeW``. Page width (in twips). Implicitly defined by ``orientation`` option. Any changes are discouraged.

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
- ``hint``. Font content type, *default*, *eastAsia*, or *cs*.
- ``italic``. Italic, *true* or *false*.
- ``name``. Font name, e.g. *Arial*.
- ``rtl``. Right to Left language, *true* or *false*.
- ``size``. Font size, e.g. *20*, *22*.
- ``smallCaps``. Small caps, *true* or *false*.
- ``strikethrough``. Strikethrough, *true* or *false*.
- ``subScript``. Subscript, *true* or *false*.
- ``superScript``. Superscript, *true* or *false*.
- ``underline``. Underline, *dash*, *dotted*, etc.

.. _paragraph-style:

Paragraph
---------

Available Paragraph style options:

- ``alignment``. Supports all alignment modes since 1st Edition of ECMA-376 standard up till ISO/IEC 29500:2012.
See ``\PhpOffice\PhpWord\SimpleType\Jc`` class for the details.
- ``basedOn``. Parent style.
- ``hanging``. Hanging by how much.
- ``indent``. Indent by how much.
- ``keepLines``. Keep all lines on one page, *true* or *false*.
- ``keepNext``. Keep paragraph with next paragraph, *true* or *false*.
- ``lineHeight``. Text line height, e.g. *1.0*, *1.5*, etc.
- ``next``. Style for next paragraph.
- ``pageBreakBefore``. Start paragraph on next page, *true* or *false*.
- ``spaceBefore``. Space before paragraph.
- ``spaceAfter``. Space after paragraph.
- ``tabs``. Set of custom tab stops.
- ``widowControl``. Allow first/last line to display on a separate page, *true* or *false*.

.. _table-style:

Table
-----

Available Table style options:

- ``alignment``. Supports all alignment modes since 1st Edition of ECMA-376 standard up till ISO/IEC 29500:2012.
See ``\PhpOffice\PhpWord\SimpleType\JcTable`` and ``\PhpOffice\PhpWord\SimpleType\Jc`` classes for the details.
- ``bgColor``. Background color, e.g. '9966CC'.
- ``border(Top|Right|Bottom|Left)Color``. Border color, e.g. '9966CC'.
- ``border(Top|Right|Bottom|Left)Size``. Border size in twips.
- ``cellMargin(Top|Right|Bottom|Left)``. Cell margin in twips.
- ``width``. Table width in percent.

Available Row style options:

- ``cantSplit``. Table row cannot break across pages, *true* or *false*.
- ``exactHeight``. Row height is exact or at least.
- ``tblHeader``. Repeat table row on every new page, *true* or *false*.

Available Cell style options:

- ``bgColor``. Background color, e.g. '9966CC'.
- ``border(Top|Right|Bottom|Left)Color``. Border color, e.g. '9966CC'.
- ``border(Top|Right|Bottom|Left)Size``. Border size in twips.
- ``gridSpan``. Number of columns spanned.
- ``textDirection(btLr|tbRl)``. Direction of text. You can use constants ``\PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR`` and ``\PhpOffice\PhpWord\Style\Cell::TEXT_DIR_TBRL``
- ``valign``. Vertical alignment, *top*, *center*, *both*, *bottom*.
- ``vMerge``. *restart* or *continue*.
- ``width``. Cell width in twips.

.. _image-style:

Image
-----

Available Image style options:

- ``alignment``. See ``\PhpOffice\PhpWord\SimpleType\Jc`` class for the details.
- ``height``. Height in pixels.
- ``marginLeft``. Left margin in inches, can be negative.
- ``marginTop``. Top margin in inches, can be negative.
- ``width``. Width in pixels.
- ``wrappingStyle``. Wrapping style, *inline*, *square*, *tight*, *behind*, or *infront*.

.. _numbering-level-style:

Numbering level
---------------

Available NumberingLevel style options:

- ``alignment``. Supports all alignment modes since 1st Edition of ECMA-376 standard up till ISO/IEC 29500:2012.
See ``\PhpOffice\PhpWord\SimpleType\Jc`` class for the details.
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
