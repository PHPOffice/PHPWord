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
