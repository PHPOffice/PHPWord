# Watermark

To add a watermark (or page background image), your section needs a
header reference. After creating a header, you can use the
``addWatermark`` method to add a watermark.

``` php
<?php

$section = $phpWord->addSection();
$header = $section->addHeader();
$header->addWatermark('resources/_earth.jpg', array('marginTop' => 200, 'marginLeft' => 55));
```

The ODText writer preserves the watermark image in the section's ODF master
page header as a page-anchored drawing frame, including its size and margins.
ODF master-page headers provide the native background-like placement; exact
WordprocessingML layering semantics are not portable across ODF consumers.
