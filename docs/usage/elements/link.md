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