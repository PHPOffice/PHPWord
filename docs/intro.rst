.. _intro:

Introduction
============

PHPWord is a library written in pure PHP and providing a set of classes that allow you to write to and read from different document file formats, like Word (.docx), WordPad (.rtf), Libre/OpenOffice Writer (.odt).
No Windows operating system is needed for usage because the resulting DOCX, ODT, or RTF files can be opened by all major word processing softwares.
PHPWord is an open source project licensed under LGPL. PHPWord is unit tested to make sure that the released versions are stable.


Supported features
------------------

Currently PHPWord can:

* Set document properties, e.g. title, subject, and creator.
* Create document sections with different settings, e.g. portrait/landscape, page size, and page numbering
* Create header and footer for each sections
* Set default font type, font size, and paragraph style
* Use UTF-8 and East Asia fonts/characters
* Define custom font styles (e.g. bold, italic, color) and paragraph styles (e.g. centered, multicolumns, spacing) either as named style or inline in text
* Insert paragraphs, either as a simple text or complex one (a text run) that contains other elements
* Insert titles (headers) and table of contents
* Insert text breaks and page breaks
* Insert and format images, either local, remote, or as page watermarks
* Insert binary OLE Objects such as Excel or Visio
* Insert and format table with customized properties for each rows (e.g. repeat as header row) and cells (e.g. background color, rowspan, colspan)
* Insert list items as bulleted, numbered, or multilevel
* Insert hyperlinks
* Create document from templates
* Use XSL 1.0 style sheets to transform main document part of OOXML template
* ... and many more features on progress


File formats support
--------------------

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


Requirements
------------------

* PHP version 5.3.0 or higher
* PHP extension ZipArchive
* PHP extension XMLWriter
