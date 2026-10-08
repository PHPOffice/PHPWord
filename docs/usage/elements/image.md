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
- ``$altText``. Description of the image used by screen readers. The ODText writer writes it as the `svg:desc` of the frame.

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
```

## Supported formats

- JPEG, GIF, PNG
- BMP, TIFF (only for local files and archives)
- WMF, EMF & EMF+ (Windows metafiles), if the optional library [phpoffice/wmf](https://github.com/PHPOffice/WMF) and the GD extension are installed

``` sh
composer require phpoffice/wmf
```

Windows metafiles are stored as is in Word2007 and ODText documents.
For the writers which don't support them (HTML, PDF, RTF), they are converted to PNG.

``` php
<?php

$section->addImage('/path/to/my/images/drawing.wmf');
$section->addImage('/path/to/my/images/chart.emf', ['width' => 300]);
```
