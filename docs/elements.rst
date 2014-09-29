.. _elements:

Elements
========

Below are the matrix of element availability in each container. The
column shows the containers while the rows lists the elements.

+-------+-----------------+-----------+----------+----------+---------+------------+------------+
| Num   | Element         | Section   | Header   | Footer   | Cell    | Text Run   | Footnote   |
+=======+=================+===========+==========+==========+=========+============+============+
| 1     | Text            | v         | v        | v        | v       | v          | v          |
+-------+-----------------+-----------+----------+----------+---------+------------+------------+
| 2     | Text Run        | v         | v        | v        | v       | -          | -          |
+-------+-----------------+-----------+----------+----------+---------+------------+------------+
| 3     | Link            | v         | v        | v        | v       | v          | v          |
+-------+-----------------+-----------+----------+----------+---------+------------+------------+
| 4     | Title           | v         | ?        | ?        | ?       | ?          | ?          |
+-------+-----------------+-----------+----------+----------+---------+------------+------------+
| 5     | Preserve Text   | ?         | v        | v        | v\*     | -          | -          |
+-------+-----------------+-----------+----------+----------+---------+------------+------------+
| 6     | Text Break      | v         | v        | v        | v       | v          | v          |
+-------+-----------------+-----------+----------+----------+---------+------------+------------+
| 7     | Page Break      | v         | -        | -        | -       | -          | -          |
+-------+-----------------+-----------+----------+----------+---------+------------+------------+
| 8     | List            | v         | v        | v        | v       | -          | -          |
+-------+-----------------+-----------+----------+----------+---------+------------+------------+
| 9     | Table           | v         | v        | v        | v       | -          | -          |
+-------+-----------------+-----------+----------+----------+---------+------------+------------+
| 10    | Image           | v         | v        | v        | v       | v          | v          |
+-------+-----------------+-----------+----------+----------+---------+------------+------------+
| 11    | Watermark       | -         | v        | -        | -       | -          | -          |
+-------+-----------------+-----------+----------+----------+---------+------------+------------+
| 12    | Object          | v         | v        | v        | v       | v          | v          |
+-------+-----------------+-----------+----------+----------+---------+------------+------------+
| 13    | TOC             | v         | -        | -        | -       | -          | -          |
+-------+-----------------+-----------+----------+----------+---------+------------+------------+
| 14    | Footnote        | v         | -        | -        | v\*\*   | v\*\*      | -          |
+-------+-----------------+-----------+----------+----------+---------+------------+------------+
| 15    | Endnote         | v         | -        | -        | v\*\*   | v\*\*      | -          |
+-------+-----------------+-----------+----------+----------+---------+------------+------------+
| 16    | CheckBox        | v         | v        | v        | v       | -          | -          |
+-------+-----------------+-----------+----------+----------+---------+------------+------------+
| 17    | TextBox         | v         | v        | v        | v       | -          | -          |
+-------+-----------------+-----------+----------+----------+---------+------------+------------+
| 18    | Field           | v         | v        | v        | v       | v          | v          |
+-------+-----------------+-----------+----------+----------+---------+------------+------------+
| 19    | Line            | v         | v        | v        | v       | v          | v          |
+-------+-----------------+-----------+----------+----------+---------+------------+------------+

Legend:

-  ``v`` Available
-  ``v*`` Available only when inside header/footer
-  ``v**`` Available only when inside section
-  ``-`` Not available
-  ``?`` Should be available

Texts
-----

Text can be added by using ``addText`` and ``addTextRun`` method.
``addText`` is used for creating simple paragraphs that only contain
texts with the same style. ``addTextRun`` is used for creating complex
paragraphs that contain text with different style (some bold, other
italics, etc) or other elements, e.g. images or links. The syntaxes are
as follow:

.. code-block:: php

    $section->addText($text, [$fontStyle], [$paragraphStyle]);
    $textrun = $section->addTextRun([$paragraphStyle]);

Text styles
~~~~~~~~~~~

You can use the ``$fontStyle`` and ``$paragraphStyle`` variable to
define text formatting. There are 2 options to style the inserted text
elements, i.e. inline style by using array or defined style by adding
style definition.

Inline style examples:

.. code-block:: php

    $fontStyle = array('name' => 'Times New Roman', 'size' => 9);
    $paragraphStyle = array('align' => 'both');
    $section->addText('I am simple paragraph', $fontStyle, $paragraphStyle);

    $textrun = $section->addTextRun();
    $textrun->addText('I am bold', array('bold' => true));
    $textrun->addText('I am italic', array('italic' => true));
    $textrun->addText('I am colored', array('color' => 'AACC00'));

Defined style examples:

.. code-block:: php

    $fontStyle = array('color' => '006699', 'size' => 18, 'bold' => true);
    $phpWord->addFontStyle('fStyle', $fontStyle);
    $text = $section->addText('Hello world!', 'fStyle');

    $paragraphStyle = array('align' => 'center');
    $phpWord->addParagraphStyle('pStyle', $paragraphStyle);
    $text = $section->addText('Hello world!', 'pStyle');

Font style
^^^^^^^^^^

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

Paragraph style
^^^^^^^^^^^^^^^

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

Titles
~~~~~~

If you want to structure your document or build table of contents, you
need titles or headings. To add a title to the document, use the
``addTitleStyle`` and ``addTitle`` method.

.. code-block:: php

    $phpWord->addTitleStyle($depth, [$fontStyle], [$paragraphStyle]);
    $section->addTitle($text, [$depth]);

Its necessary to add a title style to your document because otherwise
the title won't be detected as a real title.

Links
~~~~~

You can add Hyperlinks to the document by using the function addLink:

.. code-block:: php

    $section->addLink($linkSrc, [$linkName], [$fontStyle], [$paragraphStyle]);

-  ``$linkSrc`` The URL of the link.
-  ``$linkName`` Placeholder of the URL that appears in the document.
-  ``$fontStyle`` See "Font style" section.
-  ``$paragraphStyle`` See "Paragraph style" section.

Preserve texts
~~~~~~~~~~~~~~

The ``addPreserveText`` method is used to add a page number or page
count to headers or footers.

.. code-block:: php

    $footer->addPreserveText('Page {PAGE} of {NUMPAGES}.');

Breaks
------

Text breaks
~~~~~~~~~~~

Text breaks are empty new lines. To add text breaks, use the following
syntax. All paramaters are optional.

.. code-block:: php

    $section->addTextBreak([$breakCount], [$fontStyle], [$paragraphStyle]);

-  ``$breakCount`` How many lines
-  ``$fontStyle`` See "Font style" section.
-  ``$paragraphStyle`` See "Paragraph style" section.

Page breaks
~~~~~~~~~~~

There are two ways to insert a page breaks, using the ``addPageBreak``
method or using the ``pageBreakBefore`` style of paragraph.

:: code-block:: php

    \\$section->addPageBreak();

Lists
-----

To add a list item use the function ``addListItem``.

Basic usage:

.. code-block:: php

    $section->addListItem($text, [$depth], [$fontStyle], [$listStyle], [$paragraphStyle]);

Parameters:

-  ``$text`` Text that appears in the document.
-  ``$depth`` Depth of list item.
-  ``$fontStyle`` See "Font style" section.
-  ``$listStyle`` List style of the current element TYPE\_NUMBER,
   TYPE\_ALPHANUM, TYPE\_BULLET\_FILLED, etc. See list of constants in
   PHPWord\_Style\_ListItem.
-  ``$paragraphStyle`` See "Paragraph style" section.

Advanced usage:

You can also create your own numbering style by changing the
``$listStyle`` parameter with the name of your numbering style.

.. code-block:: php

    $phpWord->addNumberingStyle(
        'multilevel',
        array('type' => 'multilevel', 'levels' => array(
            array('format' => 'decimal', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360),
            array('format' => 'upperLetter', 'text' => '%2.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720),
            )
         )
    );
    $section->addListItem('List Item I', 0, null, 'multilevel');
    $section->addListItem('List Item I.a', 1, null, 'multilevel');
    $section->addListItem('List Item I.b', 1, null, 'multilevel');
    $section->addListItem('List Item II', 0, null, 'multilevel');

Level styles:

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

Tables
------

To add tables, rows, and cells, use the ``addTable``, ``addRow``, and
``addCell`` methods:

.. code-block:: php

    $table = $section->addTable([$tableStyle]);
    $table->addRow([$height], [$rowStyle]);
    $cell = $table->addCell($width, [$cellStyle]);

Table style can be defined with ``addTableStyle``:

.. code-block:: php

    $tableStyle = array(
        'borderColor' => '006699',
        'borderSize' => 6,
        'cellMargin' => 50
    );
    $firstRowStyle = array('bgColor' => '66BBFF');
    $phpWord->addTableStyle('myTable', $tableStyle, $firstRowStyle);
    $table = $section->addTable('myTable');

Table, row, and cell styles
~~~~~~~~~~~~~~~~~~~~~~~~~~~

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

Cell span
~~~~~~~~~

You can span a cell on multiple columns by using ``gridSpan`` or
multiple rows by using ``vMerge``.

.. code-block:: php

    $cell = $table->addCell(200);
    $cell->getStyle()->setGridSpan(5);

See ``Sample_09_Tables.php`` for more code sample.

Images
------

To add an image, use the ``addImage`` method to sections, headers,
footers, textruns, or table cells.

.. code-block:: php

    $section->addImage($src, [$style]);

-  source String path to a local image or URL of a remote image
-  styles Array fo styles for the image. See below.

Examples:

.. code-block:: php

    $section = $phpWord->addSection();
    $section->addImage(
        'mars.jpg',
        array(
            'width' => 100,
            'height' => 100,
            'marginTop' => -1,
            'marginLeft' => -1,
            'wrappingStyle' => 'behind'
        )
    );
    $footer = $section->addFooter();
    $footer->addImage('http://example.com/image.php');
    $textrun = $section->addTextRun();
    $textrun->addImage('http://php.net/logo.jpg');

Image styles
~~~~~~~~~~~~

Available image styles:

-  ``width`` Width in pixels
-  ``height`` Height in pixels
-  ``align`` Image alignment, *left*, *right*, or *center*
-  ``marginTop`` Top margin in inches, can be negative
-  ``marginLeft`` Left margin in inches, can be negative
-  ``wrappingStyle`` Wrapping style, *inline*, *square*, *tight*,
   *behind*, or *infront*

Watermarks
~~~~~~~~~~

To add a watermark (or page background image), your section needs a
header reference. After creating a header, you can use the
``addWatermark`` method to add a watermark.

.. code-block:: php

    $section = $phpWord->addSection();
    $header = $section->addHeader();
    $header->addWatermark('resources/_earth.jpg', array('marginTop' => 200, 'marginLeft' => 55));

Objects
-------

You can add OLE embeddings, such as Excel spreadsheets or PowerPoint
presentations to the document by using ``addObject`` method.

.. code-block:: php

    $section->addObject($src, [$style]);

Table of contents
-----------------

To add a table of contents (TOC), you can use the ``addTOC`` method.
Your TOC can only be generated if you have add at least one title (See
"Titles").

.. code-block:: php

    $section->addTOC([$fontStyle], [$tocStyle], [$minDepth], [$maxDepth]);

-  ``$fontStyle``: See font style section
-  ``$tocStyle``: See available options below
-  ``$minDepth``: Minimum depth of header to be shown. Default 1
-  ``$maxDepth``: Maximum depth of header to be shown. Default 9

Options for ``$tocStyle``:

-  ``tabLeader`` Fill type between the title text and the page number.
   Use the defined constants in PHPWord\_Style\_TOC.
-  ``tabPos`` The position of the tab where the page number appears in
   twips.
-  ``indent`` The indent factor of the titles in twips.

Footnotes & endnotes
--------------------

You can create footnotes with ``addFootnote`` and endnotes with
``addEndnote`` in texts or textruns, but it's recommended to use textrun
to have better layout. You can use ``addText``, ``addLink``,
``addTextBreak``, ``addImage``, ``addObject`` on footnotes and endnotes.

On textrun:

.. code-block:: php

    $textrun = $section->addTextRun();
    $textrun->addText('Lead text.');
    $footnote = $textrun->addFootnote();
    $footnote->addText('Footnote text can have ');
    $footnote->addLink('http://test.com', 'links');
    $footnote->addText('.');
    $footnote->addTextBreak();
    $footnote->addText('And text break.');
    $textrun->addText('Trailing text.');
    $endnote = $textrun->addEndnote();
    $endnote->addText('Endnote put at the end');

On text:

.. code-block:: php

    $section->addText('Lead text.');
    $footnote = $section->addFootnote();
    $footnote->addText('Footnote text.');

The footnote reference number will be displayed with decimal number
starting from 1. This number use ``FooterReference`` style which you can
redefine by ``addFontStyle`` method. Default value for this style is
``array('superScript' => true)``;

Checkboxes
----------

Checkbox elements can be added to sections or table cells by using
``addCheckBox``.

.. code-block:: php

    $section->addCheckBox($name, $text, [$fontStyle], [$paragraphStyle])

-  ``$name`` Name of the check box.
-  ``$text`` Text following the check box
-  ``$fontStyle`` See "Font style" section.
-  ``$paragraphStyle`` See "Paragraph style" section.

Textboxes
---------

To be completed

Fields
------

To be completed

Line
------

Line elements can be added to sections by using ``addLine``.

.. code-block:: php

    $linestyle = array('weight' => 1, 'width' => 100, 'height' => 0, 'color' => 635552);
    $section->addLine($lineStyle)

Available line style attributes:

-  ``weight`` Line width in twips
-  ``color`` Defines the color of stroke
-  ``dash`` Line types: dash, rounddot, squaredot, dashdot, longdash, longdashdot, longdashdotdot
-  ``beginArrow`` Start type of arrow: block, open, classic, diamond, oval
-  ``endArrow`` End type of arrow: block, open, classic, diamond, ovel
-  ``width`` Line-object width in pt
-  ``height`` Line-object height in pt
-  ``flip`` Flip the line element: true, false
