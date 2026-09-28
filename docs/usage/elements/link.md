# Link

You can add Hyperlinks to the document by using the function addLink:

``` php
<?php

$section->addLink($linkSrc, [$linkName], [$fontStyle], [$paragraphStyle]);
```

- ``$linkSrc``. The URL of the link.
- ``$linkName``. Placeholder of the URL that appears in the document.
- ``$fontStyle``. See [`Styles > Font`](../styles/font.md).
- ``$paragraphStyle``. See [`Styles > Paragraph`](../styles/paragraph.md).

A link can have a tooltip, the text shown when the pointer rests on it. Screen readers read it, and LibreOffice exports it as the description of the link in a tagged PDF.

``` php
<?php

$section->addLink('https://github.com/PHPOffice/PHPWord', 'PHPWord')->setTooltip('The PHPWord repository');
```

The tooltip is written and read by the Word2007 writer and reader.
