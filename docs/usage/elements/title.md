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

It's necessary to add a title style to your document because otherwise the title won't be detected as a real title.