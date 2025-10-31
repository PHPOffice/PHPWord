# Shading Styles

``` php
<?php

use PhpOffice\PhpWord\Style\Shading;
$phpWord->addParagraphStyle('pStyle', ['shading' => ['pattern' => Shading::PATTERN_SOLID, 'fill' => FF0000]]);
$section->addText('Hello, World!', null, 'pStyle');

```

## Constants
- `PATTERN_CLEAR`. No pattern.
- `PATTERN_SOLID`. 100% fill pattern.
- `PATTERN_HSTRIPE`. Horizontal stripe pattern.
- `PATTERN_VSTRIPE`. Vertical stripe pattern.
- `PATTERN_DSTRIPE`. Diagonal stripe pattern.
- `PATTERN_HCROSS`. Horizontal cross pattern.
- `PATTERN_DCROSS`. Diagonal cross pattern.

## Options
- `color`. Shading pattern color, e.g. *FF0000*.
- `fill`. Shading background color, e.g. *FF0000*.
- `pattern`. Shading pattern.
   * See constants above for possible values.

## Used In
- [`Style > Cell`](../styles/table.md#cell-options).
- [`Style > Font`](../styles/font.md).
- [`Style > Paragraph`](../styles/paragraph.md).
- [`Style > Table`](../styles/table.md).
