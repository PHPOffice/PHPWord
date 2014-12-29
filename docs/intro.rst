.. _intro:

Introduction
============

PHPWord is a library written in pure PHP that provides a set of classes
to write to and read from different document file formats. The current
version of PHPWord supports Microsoft `Office Open
XML <http://en.wikipedia.org/wiki/Office_Open_XML>`__ (OOXML or
OpenXML), OASIS `Open Document Format for Office
Applications <http://en.wikipedia.org/wiki/OpenDocument>`__
(OpenDocument or ODF), and `Rich Text
Format <http://en.wikipedia.org/wiki/Rich_Text_Format>`__ (RTF).

PHPWord is an open source project licensed under the terms of `LGPL
version 3 <https://github.com/PHPOffice/PHPWord/blob/develop/COPYING.LESSER>`__.
PHPWord is aimed to be a high quality software product by incorporating
`continuous integration <https://travis-ci.org/PHPOffice/PHPWord>`__ and
`unit testing <http://phpoffice.github.io/PHPWord/coverage/develop/>`__.
You can learn more about PHPWord by reading this Developers'
Documentation and the `API
Documentation <http://phpoffice.github.io/PHPWord/docs/develop/>`__.

Features
--------

-  Set document properties, e.g. title, subject, and creator.
-  Create document sections with different settings, e.g.
   portrait/landscape, page size, and page numbering
-  Create header and footer for each sections
-  Set default font type, font size, and paragraph style
-  Use UTF-8 and East Asia fonts/characters
-  Define custom font styles (e.g. bold, italic, color) and paragraph
   styles (e.g. centered, multicolumns, spacing) either as named style
   or inline in text
-  Insert paragraphs, either as a simple text or complex one (a text
   run) that contains other elements
-  Insert titles (headers) and table of contents
-  Insert text breaks and page breaks
-  Insert right-to-left text
-  Insert and format images, either local, remote, or as page watermarks
-  Insert binary OLE Objects such as Excel or Visio
-  Insert and format table with customized properties for each rows
   (e.g. repeat as header row) and cells (e.g. background color,
   rowspan, colspan)
-  Insert list items as bulleted, numbered, or multilevel
-  Insert hyperlinks
-  Insert footnotes and endnotes
-  Insert drawing shapes (arc, curve, line, polyline, rect, oval)
-  Insert charts (pie, doughnut, bar, line, area, scatter, radar)
-  Insert form fields (textinput, checkbox, and dropdown)
-  Create document from templates
-  Use XSL 1.0 style sheets to transform main document part of OOXML
   template
-  ... and many more features on progress

File formats
------------

Below are the supported features for each file formats.

Writers
~~~~~~~

+---------------------------+----------------------+--------+-------+-------+--------+-------+
| Features                  |                      | DOCX   | ODT   | RTF   | HTML   | PDF   |
+===========================+======================+========+=======+=======+========+=======+
| **Document Properties**   | Standard             | ✓      | ✓     | ✓     | ✓      | ✓     |
+---------------------------+----------------------+--------+-------+-------+--------+-------+
|                           | Custom               | ✓      | ✓     |       |        |       |
+---------------------------+----------------------+--------+-------+-------+--------+-------+
| **Element Type**          | Text                 | ✓      | ✓     | ✓     | ✓      | ✓     |
+---------------------------+----------------------+--------+-------+-------+--------+-------+
|                           | Text Run             | ✓      | ✓     | ✓     | ✓      | ✓     |
+---------------------------+----------------------+--------+-------+-------+--------+-------+
|                           | Title                | ✓      | ✓     |       | ✓      | ✓     |
+---------------------------+----------------------+--------+-------+-------+--------+-------+
|                           | Link                 | ✓      | ✓     | ✓     | ✓      | ✓     |
+---------------------------+----------------------+--------+-------+-------+--------+-------+
|                           | Preserve Text        | ✓      |       |       |        |       |
+---------------------------+----------------------+--------+-------+-------+--------+-------+
|                           | Text Break           | ✓      | ✓     | ✓     | ✓      | ✓     |
+---------------------------+----------------------+--------+-------+-------+--------+-------+
|                           | Page Break           | ✓      |       |  ✓    |        |       |
+---------------------------+----------------------+--------+-------+-------+--------+-------+
|                           | List                 | ✓      |       |       |        |       |
+---------------------------+----------------------+--------+-------+-------+--------+-------+
|                           | Table                | ✓      | ✓     | ✓     | ✓      | ✓     |
+---------------------------+----------------------+--------+-------+-------+--------+-------+
|                           | Image                | ✓      | ✓     | ✓     | ✓      |       |
+---------------------------+----------------------+--------+-------+-------+--------+-------+
|                           | Object               | ✓      |       |       |        |       |
+---------------------------+----------------------+--------+-------+-------+--------+-------+
|                           | Watermark            | ✓      |       |       |        |       |
+---------------------------+----------------------+--------+-------+-------+--------+-------+
|                           | Table of Contents    | ✓      |       |       |        |       |
+---------------------------+----------------------+--------+-------+-------+--------+-------+
|                           | Header               | ✓      |       |       |        |       |
+---------------------------+----------------------+--------+-------+-------+--------+-------+
|                           | Footer               | ✓      |       |       |        |       |
+---------------------------+----------------------+--------+-------+-------+--------+-------+
|                           | Footnote             | ✓      |       |       | ✓      |       |
+---------------------------+----------------------+--------+-------+-------+--------+-------+
|                           | Endnote              | ✓      |       |       | ✓      |       |
+---------------------------+----------------------+--------+-------+-------+--------+-------+
| **Graphs**                | 2D basic graphs      | ✓      |       |       |        |       |
+---------------------------+----------------------+--------+-------+-------+--------+-------+
|                           | 2D advanced graphs   |        |       |       |        |       |
+---------------------------+----------------------+--------+-------+-------+--------+-------+
|                           | 3D graphs            | ✓      |       |       |        |       |
+---------------------------+----------------------+--------+-------+-------+--------+-------+
| **Math**                  | OMML support         |        |       |       |        |       |
+---------------------------+----------------------+--------+-------+-------+--------+-------+
|                           | MathML support       |        |       |       |        |       |
+---------------------------+----------------------+--------+-------+-------+--------+-------+
| **Bonus**                 | Encryption           |        |       |       |        |       |
+---------------------------+----------------------+--------+-------+-------+--------+-------+
|                           | Protection           |        |       |       |        |       |
+---------------------------+----------------------+--------+-------+-------+--------+-------+

Readers
~~~~~~~

+---------------------------+----------------------+--------+-------+-------+-------+-------+
| Features                  |                      | DOCX   | DOC   | ODT   | RTF   | HTML  |
+===========================+======================+========+=======+=======+=======+=======+
| **Document Properties**   | Standard             | ✓      |       |       |       |       |
+---------------------------+----------------------+--------+-------+-------+-------+-------+
|                           | Custom               | ✓      |       |       |       |       |
+---------------------------+----------------------+--------+-------+-------+-------+-------+
| **Element Type**          | Text                 | ✓      | ✓     | ✓     | ✓     | ✓     |
+---------------------------+----------------------+--------+-------+-------+-------+-------+
|                           | Text Run             | ✓      |       |       |       |       |
+---------------------------+----------------------+--------+-------+-------+-------+-------+
|                           | Title                | ✓      |       | ✓     |       |       |
+---------------------------+----------------------+--------+-------+-------+-------+-------+
|                           | Link                 | ✓      | ✓     |       |       |       |
+---------------------------+----------------------+--------+-------+-------+-------+-------+
|                           | Preserve Text        | ✓      |       |       |       |       |
+---------------------------+----------------------+--------+-------+-------+-------+-------+
|                           | Text Break           | ✓      | ✓     |       |       |       |
+---------------------------+----------------------+--------+-------+-------+-------+-------+
|                           | Page Break           | ✓      |       |       |       |       |
+---------------------------+----------------------+--------+-------+-------+-------+-------+
|                           | List                 | ✓      |       | ✓     |       | ✓     |
+---------------------------+----------------------+--------+-------+-------+-------+-------+
|                           | Table                | ✓      |       |       |       | ✓     |
+---------------------------+----------------------+--------+-------+-------+-------+-------+
|                           | Image                | ✓      | ✓     |       |       |       |
+---------------------------+----------------------+--------+-------+-------+-------+-------+
|                           | Object               |        |       |       |       |       |
+---------------------------+----------------------+--------+-------+-------+-------+-------+
|                           | Watermark            |        |       |       |       |       |
+---------------------------+----------------------+--------+-------+-------+-------+-------+
|                           | Table of Contents    |        |       |       |       |       |
+---------------------------+----------------------+--------+-------+-------+-------+-------+
|                           | Header               | ✓      |       |       |       |       |
+---------------------------+----------------------+--------+-------+-------+-------+-------+
|                           | Footer               | ✓      |       |       |       |       |
+---------------------------+----------------------+--------+-------+-------+-------+-------+
|                           | Footnote             | ✓      |       |       |       |       |
+---------------------------+----------------------+--------+-------+-------+-------+-------+
|                           | Endnote              | ✓      |       |       |       |       |
+---------------------------+----------------------+--------+-------+-------+-------+-------+
| **Graphs**                | 2D basic graphs      |        |       |       |       |       |
+---------------------------+----------------------+--------+-------+-------+-------+-------+
|                           | 2D advanced graphs   |        |       |       |       |       |
+---------------------------+----------------------+--------+-------+-------+-------+-------+
|                           | 3D graphs            |        |       |       |       |       |
+---------------------------+----------------------+--------+-------+-------+-------+-------+
| **Math**                  | OMML support         |        |       |       |       |       |
+---------------------------+----------------------+--------+-------+-------+-------+-------+
|                           | MathML support       |        |       |       |       |       |
+---------------------------+----------------------+--------+-------+-------+-------+-------+
| **Bonus**                 | Encryption           |        |       |       |       |       |
+---------------------------+----------------------+--------+-------+-------+-------+-------+
|                           | Protection           |        |       |       |       |       |
+---------------------------+----------------------+--------+-------+-------+-------+-------+

Contributing
------------

We welcome everyone to contribute to PHPWord. Below are some of the
things that you can do to contribute.

-  Read `our contributing
   guide <https://github.com/PHPOffice/PHPWord/blob/master/CONTRIBUTING.md>`__.
-  `Fork us <https://github.com/PHPOffice/PHPWord/fork>`__ and `request
   a pull <https://github.com/PHPOffice/PHPWord/pulls>`__ to the
   `develop <https://github.com/PHPOffice/PHPWord/tree/develop>`__
   branch.
-  Submit `bug reports or feature
   requests <https://github.com/PHPOffice/PHPWord/issues>`__ to GitHub.
-  Follow `@PHPWord <https://twitter.com/PHPWord>`__ and
   `@PHPOffice <https://twitter.com/PHPOffice>`__ on Twitter.
