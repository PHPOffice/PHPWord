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
* `tempDir`: writable directory for mPDF's temporary files (MPDF renderer only, defaults to the system temporary directory)

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

or you can edit settings in phpword.ini ( or phpword.ini.dist) file.

``` ini
pdfRendererName       = MPDF    ;DomPDF, TCPDF, MPDF
pdfRendererPath       = /path/to/your/renderer/folder
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

## Microsoft Works (WPS)

The `WPS` writer produces a native Microsoft Works 7/8 compound document with
an OLE `CONTENTS` stream. It does not rename an RTF or Word document to `.wps`.

```php
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$section = $phpWord->addSection();
$section->addText('Bonjour — café', ['bold' => true, 'size' => 12]);
\PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'WPS')->save('example.wps');
```

This writer supports text, text runs, titles, empty paragraphs, inline line
breaks, tabs, page breaks and sections sharing one page layout. It writes font
names, sizes, colors, bold, italic, strikethrough, superscript, subscript and
single/double underline. Paragraph alignment and indentation, page dimensions,
orientation and margins are preserved. Named font, title and paragraph styles
are resolved when saving. Text encoding requires either mbstring or iconv.

Other elements, including tables, images, lists, links, headers and footers,
raise an exception. Different page layouts between sections also raise an
exception. The writer serializes the document before opening the destination,
so unsupported elements do not overwrite an existing file. Advanced style
properties such as paragraph spacing, tab stops, borders and highlighting are
not yet exported. Review these limits before using WPS for complex documents.

`php samples/Sample_46_WPS.php` generates a demonstration document. The
integration tests can reopen output with the independent [libwps](https://libwps.sourceforge.net/)
importer, including a 1,200-paragraph document that spans multiple formatting
and chunk index pages:

```sh
PHPWORD_WPS2RAW=/path/to/wps2raw php vendor/bin/phpunit --no-coverage \
  tests/PhpWordTests/Writer/WPSTest.php \
  tests/PhpWordTests/Writer/WPS/CompoundFileTest.php
```

Without `PHPWORD_WPS2RAW`, independent import tests are skipped; the compound
file and destination-preservation tests still run. Output has been checked
with libwps 0.4.14. Native Microsoft Works and other application versions have
not been tested, so this does not establish compatibility with every WPS reader.
