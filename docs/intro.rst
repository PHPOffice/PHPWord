.. _intro:

Introduction
============

PHPWord is a library written in pure PHP that provides a set of classes
to write to and read from different document file formats. The current
version of PHPWord supports Microsoft `Office Open XML`_ (OOXML or
OpenXML), OASIS `Open Document Format for Office Applications`_
(OpenDocument or ODF), and `Rich Text Format`_ (RTF).

No Windows operating system is needed for usage because the resulting
DOCX, ODT, or RTF files can be opened by all major `word processing
softwares`_.

PHPWord is an open source project licensed under `LGPL`_. PHPWord is
`unit tested`_ to make sure that the released versions are stable.

**Want to contribute?** `Fork us`_ or `submit`_ your bug reports or
feature requests to us.

.. _Office Open XML: http://en.wikipedia.org/wiki/Office_Open_XML
.. _Open Document Format for Office Applications: http://en.wikipedia.org/wiki/OpenDocument
.. _Rich Text Format: http://en.wikipedia.org/wiki/Rich_Text_Format
.. _word processing softwares: http://en.wikipedia.org/wiki/List_of_word_processors
.. _LGPL: license.md
.. _unit tested: https://travis-ci.org/PHPOffice/PHPWord
.. _Fork us: https://github.com/PHPOffice/PHPWord/fork
.. _submit: https://github.com/PHPOffice/PHPWord/issues

Features
--------

-  Set document properties, e.g. title, subject, and creator.
-  Create document sections with different settings,
   e.g. portrait/landscape, page size, and page numbering
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
-  Insert and format images, either local, remote, or as page watermarks
-  Insert binary OLE Objects such as Excel or Visio
-  Insert and format table with customized properties for each rows
   (e.g. repeat as header row) and cells (e.g. background color,
   rowspan, colspan)
-  Insert list items as bulleted, numbered, or multilevel
-  Insert hyperlinks
-  Create document from templates
-  Use XSL 1.0 style sheets to transform main document part of OOXML
   template
-  … and many more features on progress

File formats
------------

Below are the supported features for each file formats.

Writers
~~~~~~~

+------+-----------------+--------+-------+-------+
| No   | Element         | DOCX   | ODT   | RTF   |
+======+=================+========+=======+=======+
| 1    | Text            | v      | v     | v     |
+------+-----------------+--------+-------+-------+
| 2    | Text Run        | v      | v     | v     |
+------+-----------------+--------+-------+-------+
| 3    | Title           | v      |       |       |
+------+-----------------+--------+-------+-------+
| 4    | Link            | v      |       |       |
+------+-----------------+--------+-------+-------+
| 5    | Preserve Text   | v      |       |       |
+------+-----------------+--------+-------+-------+
| 6    | Text Break      | v      | v     | v     |
+------+-----------------+--------+-------+-------+
| 7    | Page Break      | v      |       |       |
+------+-----------------+--------+-------+-------+
| 8    | List            | v      |       |       |
+------+-----------------+--------+-------+-------+
| 9    | Table           | v      |       |       |
+------+-----------------+--------+-------+-------+
| 10   | Image           | v      |       |       |
+------+-----------------+--------+-------+-------+
| 11   | MemoryImage     | v      |       |       |
+------+-----------------+--------+-------+-------+
| 12   | Object          | v      |       |       |
+------+-----------------+--------+-------+-------+
| 13   | Watermark       | v      |       |       |
+------+-----------------+--------+-------+-------+
| 14   | TOC             | v      |       |       |
+------+-----------------+--------+-------+-------+
| 15   | Header          | v      |       |       |
+------+-----------------+--------+-------+-------+
| 16   | Footer          | v      |       |       |
+------+-----------------+--------+-------+-------+
| 17   | Footnote        | v      |       |       |
+------+-----------------+--------+-------+-------+

Readers
~~~~~~~

+------+-----------------+--------+-------+-------+
| No   | Element         | DOCX   | ODT   | RTF   |
+======+=================+========+=======+=======+
| 1    | Text            | v      |       |       |
+------+-----------------+--------+-------+-------+
| 2    | Text Run        | v      |       |       |
+------+-----------------+--------+-------+-------+
| 3    | Title           |        |       |       |
+------+-----------------+--------+-------+-------+
| 4    | Link            |        |       |       |
+------+-----------------+--------+-------+-------+
| 5    | Preserve Text   |        |       |       |
+------+-----------------+--------+-------+-------+
| 6    | Text Break      | v      |       |       |
+------+-----------------+--------+-------+-------+
| 7    | Page Break      |        |       |       |
+------+-----------------+--------+-------+-------+
| 8    | List            |        |       |       |
+------+-----------------+--------+-------+-------+
| 9    | Table           |        |       |       |
+------+-----------------+--------+-------+-------+
| 10   | Image           |        |       |       |
+------+-----------------+--------+-------+-------+
| 11   | MemoryImage     |        |       |       |
+------+-----------------+--------+-------+-------+
| 12   | Object          |        |       |       |
+------+-----------------+--------+-------+-------+
| 13   | Watermark       |        |       |       |
+------+-----------------+--------+-------+-------+
| 14   | TOC             |        |       |       |
+------+-----------------+--------+-------+-------+
| 15   | Header          |        |       |       |
+------+-----------------+--------+-------+-------+
| 16   | Footer          |        |       |       |
+------+-----------------+--------+-------+-------+
| 17   | Footnote        |        |       |       |
+------+-----------------+--------+-------+-------+

