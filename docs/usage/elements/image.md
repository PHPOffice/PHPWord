# Image

To add an image, use the ``addImage`` method to sections, headers, footers, textruns, or table cells.

``` php
<?php

$section->addImage($src, [$style], [$isWatermark], [$name], [$altText]);
```

- ``$src``. String path to a local image, URL of a remote image or the image data, as a string. Warning: Do not pass user-generated strings here, as that would allow an attacker to read arbitrary files or perform server-side request forgery by passing file paths or URLs instead of image data.
- ``$style``. See [`Styles > Image`](../styles/image.md).
- ``$isWatermark``. Used by [`Elements > Watermark`](./watermark.md).
- ``$name``. Name of the image.
- ``$altText``. Description of the image used by screen readers.

Examples:

``` php
<?php

$section = $phpWord->addSection();
$section->addImage(
    'mars.jpg',
    array(
        'width'         => 100,
        'height'        => 100,
        'marginTop'     => -1,
        'marginLeft'    => -1,
        'wrappingStyle' => 'behind'
    )
);
$footer = $section->addFooter();
$footer->addImage('http://example.com/image.php');
$textrun = $section->addTextRun();
$textrun->addImage('http://php.net/logo.jpg', null, false, null, 'PHP logo');
$source = file_get_contents('/path/to/my/images/earth.jpg');
$image = $textrun->addImage($source);
$image->setAltText('Picture of the entire earth taken from space');
```