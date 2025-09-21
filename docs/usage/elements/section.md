# Section

Sections are the basic containers for most elements within a document. Sections can be added using the `addSection` method.

``` php
<?php

$phpWord = new PhpWord();
$section = $phpWord->addSection([$sectionStyle]);
$section->addText('Hello, World!');
```

For available styling options, see [`Styles > Section`](../styles/section.md).

See [`Sample_03_Sections`](/samples/Sample_03_Sections.php) for more code samples.
