# Installation

## Requirements

Mandatory:

-  PHP 7.1+
-  PHP [DOM extension](http://php.net/manual/en/book.dom.php)
-  PHP [JSON extension](http://php.net/manual/en/book.json.php)
-  PHP [XML Parser extension](http://www.php.net/manual/en/xml.installation.php)
-  PHP [XMLWriter extension](http://php.net/manual/en/book.xmlwriter.php)


## Installation

There are two ways to install PHPWord, i.e. via [Composer](http://getcomposer.org) or manually by downloading the library.

### Using Composer

To install via Composer, add the following lines to your `composer.json`:

``` json
{
    "require": {
        "phpoffice/phpword": "dev-master"
    }
}
```


### Using manual install
To install manually:

* [download PHPOffice\PHPWord package from GitHub](https://github.com/PHPOffice/PHPWord/archive/master.zip)
* [download PHPOffice\Common package from GitHub](https://github.com/PHPOffice/Common/archive/master.zip)
* extract the package and put the contents to your machine.


``` php
<?php

require_once 'path/to/PHPWord/src/PhpWord/Autoloader.php';
\PhpOffice\PhpWord\Autoloader::register();

require_once 'path/to/PhpOffice/Common/src/Common/Autoloader.php';
\PhpOffice\Common\Autoloader::register();

```

## Samples

After installation, you can browse and use the samples that we've provided, either by command line or using browser. If you can access your PhpWord library folder using browser, point your browser to the `samples` folder, e.g. `http://localhost/PhpWord/samples/`.
