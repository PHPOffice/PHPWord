# Spacing Styles

``` php
<?php

$phpWord->addParagraphStyle('pStyle', ['space' => ['before' => 120, 'after' => 240]]);
$section->addText('Hello, World!', null, 'pStyle');

```

See [`Sample_08_ParagraphPagination`](/samples/Sample_08_ParagraphPagination.php) for more code samples.

## Options
- `after`. Space after paragraph in *twip*. 20twip = 1pt.
- `before`. Space before paragraph in *twip*. 20twip = 1pt.
- `line`. Space between lines within a paragraph in *twip*. If `lineRule` is *auto*, 240 (height of 1 line) will be added, so if you want a double line height, set this to 240. If `lineRule` is *exact* or *atLeast*, 20twip = 1pt.
- `lineRule`. Line Spacing Rule. *auto*, *exact*, *atLeast*
   * See [`SimpleType > LineSpacingRule`](../simpletypes/linespacingrule.md) class constants for possible values.

## Used In
- [`Style > Paragraph`](../styles/paragraph.md).
