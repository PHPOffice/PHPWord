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
* ... and many more features on progress


Requirements
------------------

* PHP version 5.3.0 or higher
* PHP extension ZipArchive
* PHP extension XMLWriter
