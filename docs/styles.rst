.. _styles:

Styles
======

Section
-------

Below are the available styles for section:

-  ``pageSizeW`` Page width in twips (the default is 11906/A4 size)
-  ``pageSizeH`` Page height in twips (the default is 16838/A4 size)
-  ``orientation`` Page orientation, i.e. 'portrait' (default) or
   'landscape'
-  ``marginTop`` Page margin top in twips
-  ``marginLeft`` Page margin left in twips
-  ``marginRight`` Page margin right in twips
-  ``marginBottom`` Page margin bottom in twips
-  ``borderTopSize`` Border top size in twips
-  ``borderTopColor`` Border top color
-  ``borderLeftSize`` Border left size in twips
-  ``borderLeftColor`` Border left color
-  ``borderRightSize`` Border right size in twips
-  ``borderRightColor`` Border right color
-  ``borderBottomSize`` Border bottom size in twips
-  ``borderBottomColor`` Border bottom color
-  ``headerHeight`` Spacing to top of header
-  ``footerHeight`` Spacing to bottom of footer
-  ``gutter`` Page gutter spacing
-  ``colsNum`` Number of columns
-  ``colsSpace`` Spacing between columns
-  ``breakType`` Section break type (nextPage, nextColumn, continuous,
   evenPage, oddPage)

Font
----

Available font styles:

-  ``name`` Font name, e.g. *Arial*
-  ``size`` Font size, e.g. *20*, *22*,
-  ``hint`` Font content type, *default*, *eastAsia*, or *cs*
-  ``bold`` Bold, *true* or *false*
-  ``italic`` Italic, *true* or *false*
-  ``superScript`` Superscript, *true* or *false*
-  ``subScript`` Subscript, *true* or *false*
-  ``underline`` Underline, *dash*, *dotted*, etc.
-  ``strikethrough`` Strikethrough, *true* or *false*
-  ``doubleStrikethrough`` Double strikethrough, *true* or *false*
-  ``color`` Font color, e.g. *FF0000*
-  ``fgColor`` Font highlight color, e.g. *yellow*, *green*, *blue*
-  ``bgColor`` Font background color, e.g. *FF0000*
-  ``smallCaps`` Small caps, *true* or *false*
-  ``allCaps`` All caps, *true* or *false*
-  ``rtl`` Right to Left language, *true* or *false*

Paragraph
---------

Available paragraph styles:

-  ``align`` Paragraph alignment, *left*, *right* or *center*
-  ``spaceBefore`` Space before paragraph
-  ``spaceAfter`` Space after paragraph
-  ``indent`` Indent by how much
-  ``hanging`` Hanging by how much
-  ``basedOn`` Parent style
-  ``next`` Style for next paragraph
-  ``widowControl`` Allow first/last line to display on a separate page,
   *true* or *false*
-  ``keepNext`` Keep paragraph with next paragraph, *true* or *false*
-  ``keepLines`` Keep all lines on one page, *true* or *false*
-  ``pageBreakBefore`` Start paragraph on next page, *true* or *false*
-  ``lineHeight`` text line height, e.g. *1.0*, *1.5*, ect...
-  ``tabs`` Set of custom tab stops

Table
-----

Table styles:

-  ``width`` Table width in percent
-  ``bgColor`` Background color, e.g. '9966CC'
-  ``border(Top|Right|Bottom|Left)Size`` Border size in twips
-  ``border(Top|Right|Bottom|Left)Color`` Border color, e.g. '9966CC'
-  ``cellMargin(Top|Right|Bottom|Left)`` Cell margin in twips

Row styles:

-  ``tblHeader`` Repeat table row on every new page, *true* or *false*
-  ``cantSplit`` Table row cannot break across pages, *true* or *false*
-  ``exactHeight`` Row height is exact or at least

Cell styles:

-  ``width`` Cell width in twips
-  ``valign`` Vertical alignment, *top*, *center*, *both*, *bottom*
-  ``textDirection`` Direction of text
-  ``bgColor`` Background color, e.g. '9966CC'
-  ``border(Top|Right|Bottom|Left)Size`` Border size in twips
-  ``border(Top|Right|Bottom|Left)Color`` Border color, e.g. '9966CC'
-  ``gridSpan`` Number of columns spanned
-  ``vMerge`` *restart* or *continue*

Image
-----

Available image styles:

-  ``width`` Width in pixels
-  ``height`` Height in pixels
-  ``align`` Image alignment, *left*, *right*, or *center*
-  ``marginTop`` Top margin in inches, can be negative
-  ``marginLeft`` Left margin in inches, can be negative
-  ``wrappingStyle`` Wrapping style, *inline*, *square*, *tight*,
   *behind*, or *infront*

Numbering level
---------------

-  ``start`` Starting value
-  ``format`` Numbering format
   bullet\|decimal\|upperRoman\|lowerRoman\|upperLetter\|lowerLetter
-  ``restart`` Restart numbering level symbol
-  ``suffix`` Content between numbering symbol and paragraph text
   tab\|space\|nothing
-  ``text`` Numbering level text e.g. %1 for nonbullet or bullet
   character
-  ``align`` Numbering symbol align left\|center\|right\|both
-  ``left`` See paragraph style
-  ``hanging`` See paragraph style
-  ``tabPos`` See paragraph style
-  ``font`` Font name
-  ``hint`` See font style
