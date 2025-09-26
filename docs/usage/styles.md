# Styles

Styles can be added to various elements either by including them with the element or by adding the style to the PhpWord document.

## Defining Styles

``` php
<?php

// Creating the new document...
$phpWord = new \PhpOffice\PhpWord\PhpWord();

use PhpOffice\PhpWord\SimpleType\Jc as JcType;

// Add a font style with optionally included paragraph styling
$phpWord->addFontStyle('Subtitle',
    array('name' => 'Tahoma', 'size' => 16, 'color' => '1B2232', 'bold' => true),
    array('spacing' => 240, 'lineHeight' => 1.5, 'align' => JcType::Both),
);

// Add a title style by level with optionally included paragraph styling
$phpWord->addTitleStyle(0,
    array('name' => 'Arial', 'size' => 24, 'color' => '1B2232', 'bold' => true),
    array('spacing' => 480, 'lineHeight' => 2, 'align' => JcType::Both),
);

```

## PHPWord Style Functions

## PHPWord Style Classes
- [`Style > Border`](styles/border.md).

