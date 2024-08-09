# Writers

## HTML
The name of the writer is `HTML`.

``` php
<?php

$writer = IOFactory::createWriter($oPhpWord, 'HTML');
$writer->save(__DIR__ . '/sample.html');
```


When generating html/pdf, you can alter the default handling of white space (normal), and/or supply a fallback generic font as follows:

```php
$writer = IOFactory::createWriter($oPhpWord, 'HTML');
$writer->setDefaultGenericFont('serif');
$writer->setDefaultWhiteSpace('pre-wrap');
$writer->save(__DIR__ . '/sample.html');
```

## ODText
The name of the writer is `ODText`.

``` php
<?php

$writer = IOFactory::createWriter($oPhpWord, 'ODText');
$writer->save(__DIR__ . '/sample.docx');
```

## PDF
The name of the writer is `PDF`.

``` php
<?php

$writer = IOFactory::createWriter($oPhpWord, 'PDF');
$writer->save(__DIR__ . '/sample.pdf');
```

To generate a PDF, the PhpWord object passes through HTML before generating the PDF.
This HTML can be modified using a callback.

``` php
<?php

$writer = IOFactory::createWriter($oPhpWord, 'PDF');
$writer->setEditCallback('cbEditHTML');
$writer->save(__DIR__ . '/sample.pdf');

/**
 * Add a meta tag generator
 */
function cbEditHTML(string $inputHTML): string
{
    $beforeBody = '<meta name="generator" content="PHPWord" />';
    $needle = '</head>';

    $pos = strpos($inputHTML, $needle);
    if ($pos !== false) {
        $inputHTML = (string) substr_replace($inputHTML, "$beforeBody\n$needle", $pos, strlen($needle));
    }

    return $inputHTML;
}
```

### Options

You can define options like :
* `font`: default font

Options must be defined before creating the writer.

``` php
<?php

use PhpOffice\PhpWord\Settings;

Settings::setPdfRendererOptions([
    'font' => 'Arial'
]);

$writer = IOFactory::createWriter($oPhpWord, 'PDF');
$writer->save(__DIR__ . '/sample.pdf');
```

#### Specify the PDF Renderer

Before PHPWord can write a PDF, you **must** specify the renderer to use and the path to it.
Currently, three renderers are supported: 

- [DomPDF](https://github.com/dompdf/dompdf)
- [MPDF](https://mpdf.github.io/)
- [TCPDF](https://tcpdf.org/)

To specify the renderer you use two static `Settings` functions:

- `setPdfRendererName`: This sets the name of the renderer library to use.
  Provide one of [`Settings`' three `PDF_` constants](https://github.com/PHPOffice/PHPWord/blob/master/src/PhpWord/Settings.php#L39-L41) to the function call.
- `setPdfRendererPath`: This sets the path to the renderer library. 
  This directory is the renderer's package directory within Composer's _vendor_ directory.

In the code below, you can see an example of setting MPDF as the desired PDF renderer.

```php
Settings::setPdfRendererName(Settings::PDF_RENDERER_MPDF);
Settings::setPdfRendererPath(__DIR__ . '/../vendor/mpdf/mpdf');
```

## RTF
The name of the writer is `RTF`.

``` php
<?php

$writer = IOFactory::createWriter($oPhpWord, 'RTF');
$writer->save(__DIR__ . '/sample.rtf');
```

## Word2007
The name of the writer is `Word2007`.

``` php
<?php

$writer = IOFactory::createWriter($oPhpWord, 'Word2007');
$writer->save(__DIR__ . '/sample.docx');
```

### ZIP Adapter
You can change the ZIP Adapter for the writer. By default, the ZIP Adapter is `ZipArchiveAdapter`.

``` php
<?php

use PhpOffice\Common\Adapter\Zip\PclZipAdapter;
use PhpOffice\Common\Adapter\Zip\ZipArchiveAdapter;

$writer = IOFactory::createWriter($oPhpWord, 'Word2007');
$writer->setZipAdapter(new PclZipAdapter());
$writer->save(__DIR__ . '/sample.docx');
```
