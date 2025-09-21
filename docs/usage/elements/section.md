#Section

Sections can be added using the `addSection` method. Sections are the basic containers for all the elements of a document.

``` php
<?php

$phpWord = new PhpWord();
$section = $phpWord->addSection([$sectionStyle]);
```

- `$sectionStyle`. See [`Styles > Section`](../styles/section.md).

For available styling options, see [`Styles > Section`](../styles/section.md).
