# Writers

## HTML
The name of the writer is `HTML`.

``` php
<?php

$writer = IOFactory::createWriter($oPhpWord, 'HTML');
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
