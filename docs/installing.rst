.. _setup:

Installing/configuring
======================

Requirements
------------

Mandatory:

-  PHP 5.3+
-  PHP `Zip <http://php.net/manual/en/book.zip.php>`__ extension
-  PHP `XML
   Parser <http://www.php.net/manual/en/xml.installation.php>`__
   extension

Optional PHP extensions:

-  `GD <http://php.net/manual/en/book.image.php>`__
-  `XMLWriter <http://php.net/manual/en/book.xmlwriter.php>`__
-  `XSL <http://php.net/manual/en/book.xsl.php>`__

Installation
------------

There are two ways to install PHPWord, i.e. via
`Composer <http://getcomposer.org/>`__ or manually by downloading the
library.

Using Composer
~~~~~~~~~~~~~~

To install via Composer, add the following lines to your
``composer.json``:

.. code-block:: json

    {
        "require": {
           "phpoffice/phpword": "dev-master"
        }
    }

Manual install
~~~~~~~~~~~~~~

To install manually, `download PHPWord package from
github <https://github.com/PHPOffice/PHPWord/archive/master.zip>`__.
Extract the package and put the contents to your machine. To use the
library, include ``src/PhpWord/Autoloader.php`` in your script and
invoke ``Autoloader::register``.

.. code-block:: php

    require_once '/path/to/src/PhpWord/Autoloader.php';
    \PhpOffice\PhpWord\Autoloader::register();

Using samples
-------------

After installation, you can browse and use the samples that we've
provided, either by command line or using browser. If you can access
your PHPWord library folder using browser, point your browser to the
``samples`` folder, e.g. ``http://localhost/PhpWord/samples/``.
