.. _containers:

Containers
==========

Containers are objects where you can put elements (texts, lists, tables,
etc). There are 3 main containers, i.e. sections, headers, and footers.
There are 3 elements that can also act as containers, i.e. textruns,
table cells, and footnotes.

Sections
--------

Every visible element in word is placed inside of a section. To create a
section, use the following code:

.. code-block:: php

    $section = $phpWord->addSection($sectionSettings);

The ``$sectionSettings`` is an optional associative array that sets the
section. Example:

.. code-block:: php

    $sectionSettings = array(
        'orientation' => 'landscape',
        'marginTop' => 600,
        'colsNum' => 2,
    );

Section settings
~~~~~~~~~~~~~~~~

Below are the available settings for section:

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

The following two settings are automatically set by the use of the
``orientation`` setting. You can alter them but that's not recommended.

-  ``pageSizeW`` Page width in twips
-  ``pageSizeH`` Page height in twips

Page number
~~~~~~~~~~~

You can change a section page number by using the ``pageNumberingStart``
property of the section.

.. code-block:: php

    // Method 1
    $section = $phpWord->addSection(array('pageNumberingStart' => 1));

    // Method 2
    $section = $phpWord->addSection();
    $section->getSettings()->setPageNumberingStart(1);

Multicolumn
~~~~~~~~~~~

You can change a section layout to multicolumn (like in a newspaper) by
using the ``breakType`` and ``colsNum`` property of the section.

.. code-block:: php

    // Method 1
    $section = $phpWord->addSection(array('breakType' => 'continuous', 'colsNum' => 2));

    // Method 2
    $section = $phpWord->addSection();
    $section->getSettings()->setBreakType('continuous');
    $section->getSettings()->setColsNum(2);


### Line numbering

You can apply line numbering to a section by using the ``lineNumbering``
property of the section.

.. code-block:: php

    // Method 1
    $section = $phpWord->addSection(array('lineNumbering' => array()));

    // Method 2
    $section = $phpWord->addSection();
    $section->getSettings()->setLineNumbering(array());

Below are the properties of the line numbering style.

-  ``start`` Line numbering starting value
-  ``increment`` Line number increments
-  ``distance`` Distance between text and line numbering in twip
-  ``restart`` Line numbering restart setting continuous|newPage|newSection

Headers
-------

Each section can have its own header reference. To create a header use
the ``addHeader`` method:

.. code-block:: php

    $header = $section->addHeader();

Be sure to save the result in a local object. You can use all elements
that are available for the footer. See "Footer" section for detail.
Additionally, only inside of the header reference you can add watermarks
or background pictures. See "Watermarks" section.

Footers
-------

Each section can have its own footer reference. To create a footer, use
the ``addFooter`` method:

.. code-block:: php

    $footer = $section->addFooter();

Be sure to save the result in a local object to add elements to a
footer. You can add the following elements to footers:

-  Texts ``addText`` and ``createTextrun``
-  Text breaks
-  Images
-  Tables
-  Preserve text

See the "Elements" section for the detail of each elements.

Other containers
----------------

Textruns, table cells, and footnotes are elements that can also act as
containers. See the corresponding "Elements" section for the detail of
each elements.
