# Indentation Styles

``` php
<?php

$phpWord->addParagraphStyle('pStyle', ['indentation' => ['left' => 240, 'right' => 120]]);
$section->addText('Hello, World!', null, 'pStyle');

```

See [`Sample_08_ParagraphPagination`](/samples/Sample_08_ParagraphPagination.php) for more code samples.

## Options
- `left`. Left indentation in *twip*.
- `right`. Right indentation in *twip*.
- `firstLine`. Additional first line indentation in *twip*.
- `firstLineChars`. Additional first line chars indentation in *twip*.
- `hanging`. Indentation removed from first line *twip*.

*Warning*: `firstLine[Chars]` and `hanging` cannot be used together.

## Used In
- [`Style > Paragraph`](../styles/paragraph.md).
