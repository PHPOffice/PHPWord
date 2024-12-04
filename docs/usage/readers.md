# Readers

## HTML
The name of the reader is `HTML`.

``` php
<?php

$reader = IOFactory::createReader('HTML');
$reader->load(__DIR__ . '/sample.html');
```

## MsDoc
The name of the reader is `MsDoc`.

``` php
<?php

$reader = IOFactory::createReader('MsDoc');
$reader->load(__DIR__ . '/sample.doc');
```

## ODText
The name of the reader is `ODText`.

``` php
<?php

$reader = IOFactory::createReader('ODText');
$reader->load(__DIR__ . '/sample.odt');
```

## RTF
The name of the reader is `RTF`.

``` php
<?php

$reader = IOFactory::createReader('RTF');
$reader->load(__DIR__ . '/sample.rtf');
```

## Word2007
The name of the reader is `Word2007`.

``` php
<?php

$reader = IOFactory::createReader('Word2007');
$reader->load(__DIR__ . '/sample.docx');
```