# Title

If you want to structure your document or build table of contents, you need titles or headings.
To add a title to the document, use the ``addTitleStyle`` and ``addTitle`` method.
If `depth` is 0, a Title will be inserted, otherwise a Heading1, Heading2, ...

``` php
<?php

$phpWord->addTitleStyle($depth, [$fontStyle], [$paragraphStyle]);
$section->addTitle($text, $depth, $pageNumber);
```

`addTitleStyle` :
- ``$depth``
- ``$fontStyle``: See [`Styles > Font`](../styles/font.md).
- ``$paragraphStyle``: See [`Styles > Paragraph`](../styles/paragraph.md).

`addTitle` :
- ``$text``. Text to be displayed in the document. This can be `string` or a `\PhpOffice\PhpWord\Element\TextRun`
- ``$depth``
- ``$pageNumber`` : Number of the page

Without a title style, the Word2007 writer still gives the title a `Title` or `HeadingN` style, with no formatting of its own.
A heading style carries its outline level (`w:outlineLvl`), so the heading shows in the navigation pane and in the table of contents, and a tagged PDF made from the document tags it as a heading.