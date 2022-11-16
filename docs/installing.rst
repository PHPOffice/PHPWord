.. _setup:

Installing/configuring
======================

Requirements
------------

Mandatory:

- composer
- PHP 7.1+
- `XML Parser <http://www.php.net/manual/en/xml.installation.php>`__ extension

Optional:

- `Zip <http://php.net/manual/en/book.zip.php>`__ extension
- `GD <http://php.net/manual/en/book.image.php>`__ extension
- `XMLWriter <http://php.net/manual/en/book.xmlwriter.php>`__ extension
- `XSL <http://php.net/manual/en/book.xsl.php>`__ extension
- `dompdf <https://github.com/dompdf/dompdf>`__ library

Installation
------------

PHPWord is installed via `Composer <https://getcomposer.org/>`__.
You just need to `add dependency <https://getcomposer.org/doc/04-schema.md#package-links>`__ on PHPWord into your package.

Example:

.. code-block:: bash

    composer require phpoffice/phpword

If you are a developer or if you want to help us with testing then fetch the latest branch for developers.
Notice: all contributions must be done against the developer branch.

Example:

.. code-block:: bash

    composer require phpoffice/phpword:dev-master

Using samples
-------------

More examples are provided in the ``samples`` directory.
For an easy access to those samples launch ``php -S localhost:8000`` in the samples directory then browse to http://localhost:8000 to view the samples.
