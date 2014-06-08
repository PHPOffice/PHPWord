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

    $section = $phpWord->addSection($sectionStyle);

The ``$sectionStyle`` is an optional associative array that sets the
section. Example:

.. code-block:: php

    $sectionStyle = array(
        'orientation' => 'landscape',
        'marginTop' => 600,
        'colsNum' => 2,
    );

Page number
~~~~~~~~~~~

You can change a section page number by using the ``pageNumberingStart``
style of the section.

.. code-block:: php

    // Method 1
    $section = $phpWord->addSection(array('pageNumberingStart' => 1));

    // Method 2
    $section = $phpWord->addSection();
    $section->getStyle()->setPageNumberingStart(1);

Multicolumn
~~~~~~~~~~~

You can change a section layout to multicolumn (like in a newspaper) by
using the ``breakType`` and ``colsNum`` style of the section.

.. code-block:: php

    // Method 1
    $section = $phpWord->addSection(array('breakType' => 'continuous', 'colsNum' => 2));

    // Method 2
    $section = $phpWord->addSection();
    $section->getStyle()->setBreakType('continuous');
    $section->getStyle()->setColsNum(2);

Line numbering
~~~~~~~~~~~~~~

You can apply line numbering to a section by using the ``lineNumbering``
style of the section.

.. code-block:: php

    // Method 1
    $section = $phpWord->addSection(array('lineNumbering' => array()));

    // Method 2
    $section = $phpWord->addSection();
    $section->getStyle()->setLineNumbering(array());

Below are the properties of the line numbering style.

-  ``start`` Line numbering starting value
-  ``increment`` Line number increments
-  ``distance`` Distance between text and line numbering in twip
-  ``restart`` Line numbering restart setting
   continuous\|newPage\|newSection

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
