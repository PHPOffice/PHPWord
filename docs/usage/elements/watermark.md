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