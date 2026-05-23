#

![PHPWord](images/phpword.svg)

PHPWord is a library written in pure PHP that provides a set ofclasses to write to different document file formats, i.e. [Microsoft Office Open XML](http://en.wikipedia.org/wiki/Office_Open_XML)(`.docx`), OASIS [Open Document Format for Office Applications](http://en.wikipedia.org/wiki/OpenDocument) (`.odt`), [Rich Text Format](http://en.wikipedia.org/wiki/Rich_Text_Format) (`.rtf`), [Microsoft Word Binary File](https://en.wikipedia.org/wiki/Doc_(computing)) (`.doc`), HTML (`.html`), and PDF (`.pdf`).

PHPWord is an open source project licensed under the terms of [LGPL version 3](https://github.com/PHPOffice/PHPWord/blob/master/COPYING.LESSER). PHPWord is aimed to be a high quality software product by incorporating [continuous integration and unit testing](https://github.com/PHPOffice/PHPWord/actions/workflows/php.yml). You can learn more about PHPWord by reading this Developers'Documentation.
<!---
-  and the `API Documentation <http://phpoffice.github.io/PHPWord/docs/develop/>`__
-->

## Features

- Set document properties, e.g. title, subject, and creator.
- Create document sections with different settings, e.g. portrait/landscape, page size, and page numbering
- Create header and footer for each sections
- Set default font type, font size, and paragraph style
- Use UTF-8 and East Asia fonts/characters
- Define custom font styles (e.g. bold, italic, color) and paragraph styles (e.g. centered, multicolumns, spacing) either as named style or inline in text
- Insert paragraphs, either as a simple text or complex one (a text run) that contains other elements
- Insert titles (headers) and table of contents
- Insert text breaks and page breaks
- Insert and format images, either local, remote, or as page watermarks
- Insert binary OLE Objects such as Excel or Visio
- Insert and format table with customized properties for each rows (e.g. repeat as header row) and cells (e.g. background color, rowspan, colspan)
- Insert list items as bulleted, numbered, or multilevel
- Insert hyperlinks
- Insert footnotes and endnotes
- Insert drawing shapes (arc, curve, line, polyline, rect, oval)
- Insert charts (pie, doughnut, bar, line, area, scatter, radar)
- Insert form fields (textinput, checkbox, and dropdown)
- Create document from templates
- Use XSL 1.0 style sheets to transform headers, main document part, and footers of an OOXML template
- ... and many more features on progress

## File formats

Below are the supported features for each file formats.


### Writers


| Features                  |                      | OOXML  | ODF   | RTF   | HTML   | PDF   |
|---------------------------|----------------------|--------|-------|-------|--------|--------|
| **Document Properties**   | Standard             | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |
|                           | Custom               | :white_check_mark: | :white_check_mark: |       |        |       |
| **Element Type**          | Text                 | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |
|                           | Text Run             | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |
|                           | Title                | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |
|                           | Link                 | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |
|                           | Preserve Text        | :white_check_mark: |       |       |        |       |
|                           | Text Break           | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |
|                           | Page Break           | :white_check_mark: |       |  :white_check_mark:    |        |       |
|                           | List                 | :white_check_mark: | :white_check_mark: |       |        |       |
|                           | Table                | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |
|                           | Image                | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |       |
|                           | Object               | :white_check_mark: |       |       |        |       |
|                           | Watermark            | :white_check_mark: |       |       |        |       |
|                           | Table of Contents    | :white_check_mark: |       |       |        |       |
|                           | Header               | :white_check_mark: |       |       |        |       |
|                           | Footer               | :white_check_mark: |       |       |        |       |
|                           | Footnote             | :white_check_mark: |       |       | :white_check_mark: |       |
|                           | Endnote              | :white_check_mark: |       |       | :white_check_mark: |       |
|                           | Comments             | :white_check_mark: |       |       |        |       |
| **Graphs**                | 2D basic graphs      | :white_check_mark: |       |       |        |       |
|                           | 2D advanced graphs   |        |       |       |        |       |
|                           | 3D graphs            | :white_check_mark: |       |       |        |       |
| **Math**                  | OMML support         | :white_check_mark: |       |       |        |       |
|                           | MathML support       |        | :white_check_mark: |       |        |       |
| **Bonus**                 | Encryption           |        |       |       |        |       |
|                           | Protection           |        |       |       |        |       |

### Readers


| Features                  |                      | OOXML  | DOC   | ODF   | RTF   | HTML  |
|---------------------------|----------------------|--------|-------|-------|-------|-------|
| **Document Properties**   | Standard             | :white_check_mark: |       |       |       |       |
|                           | Custom               | :white_check_mark: |       |       |       |       |
| **Element Type**          | Text                 | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: | :white_check_mark: |
|                           | Text Run             | :white_check_mark: |       |       |       |       |
|                           | Title                | :white_check_mark: |       | :white_check_mark: |       |       |
|                           | Link                 | :white_check_mark: | :white_check_mark: |       |       |       |
|                           | Preserve Text        | :white_check_mark: |       |       |       |       |
|                           | Text Break           | :white_check_mark: | :white_check_mark: |       |       |       |
|                           | Page Break           | :white_check_mark: |       |       |       |       |
|                           | List                 | :white_check_mark: |       | :white_check_mark: |       | :white_check_mark: |
|                           | Table                | :white_check_mark: |       |       |       | :white_check_mark: |
|                           | Image                | :white_check_mark: | :white_check_mark: |       |       |       |
|                           | Object               |        |       |       |       |       |
|                           | Watermark            |        |       |       |       |       |
|                           | Table of Contents    |        |       |       |       |       |
|                           | Header               | :white_check_mark: |       |       |       |       |
|                           | Footer               | :white_check_mark: |       |       |       |       |
|                           | Footnote             | :white_check_mark: |       |       |       |       |
|                           | Endnote              | :white_check_mark: |       |       |       |       |
|                           | Comments             | :white_check_mark: |       |       |       |       |
| **Graphs**                | 2D basic graphs      |        |       |       |       |       |
|                           | 2D advanced graphs   |        |       |       |       |       |
|                           | 3D graphs            |        |       |       |       |       |
| **Math**                  | OMML support         | :white_check_mark: |       |       |       |       |
|                           | MathML support       |        | :white_check_mark: |       |       |       |
| **Bonus**                 | Encryption           |        |       |       |       |       |
|                           | Protection           |        |       |       |       |       |


## Contributing

We welcome everyone to contribute to PHPWord. Below are some of the things that you can do to contribute:

-  Read [our contributing guide](https://github.com/PHPOffice/PHPWord/blob/master/CONTRIBUTING.md)
-  [Fork us](https://github.com/PHPOffice/PHPWord/fork) and [request a pull](https://github.com/PHPOffice/PHPWord/pulls) to the [master](https://github.com/PHPOffice/PHPWord/tree/master) branch
-  Submit [bug reports or feature requests](https://github.com/PHPOffice/PHPWord/issues) to GitHub
-  Follow [@PHPOffice](https://twitter.com/PHPOffice) on Twitter
