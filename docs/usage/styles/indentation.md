# Indentation Styles

``` php
<?php

$phpWord->addParagraphStyle('line', ['indentation' => ['left' => 240, 'right' => 120]]);
$section->addText('Hello, World!', null, 'line');

```

See [`Sample_08_ParagraphPagination`](/samples/Sample_08_ParagraphPagination.php) for more code samples.

## Options
- `left`. Left indentation in *twip*.
- `right`. Right indentation in *twip*.
- `firstLine`. Additional first line indentation in *twip*.
- `firstLineChars`. Additional first line chars indentation in *twip*.
- `hanging`. Indentation removed from first line *twip*.

## Used In
- [`Style > Paragraph`](../styles/paragraph.md).
