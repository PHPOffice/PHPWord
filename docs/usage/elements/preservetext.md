# Preserve text

The ``addPreserveText`` method is used to add a page number or page count to headers or footers.

``` php
<?php

$footer->addPreserveText('Page {PAGE} of {NUMPAGES}.');
```

The ODText writer maps ``{PAGE}``, ``{NUMPAGES}``, ``{DATE}``, and ``{FILENAME}`` to native ODF fields. Other placeholders remain literal text.
