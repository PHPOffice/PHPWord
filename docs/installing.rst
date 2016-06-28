.. _setup:

Installing/configuring
======================

Requirements
------------

Mandatory:

- PHP 5.3.3+
- `XML Parser <http://www.php.net/manual/en/xml.installation.php>`__ extension
- `Zend\\Escaper <http://framework.zend.com/manual/current/en/modules/zend.escaper.introduction.html>`__ component
- Zend\\Stdlib component
- `Zend\\Validator <http://framework.zend.com/manual/current/en/modules/zend.validator.html>`__ component

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

.. code-block:: json

    {
        "require": {
           "phpoffice/phpword": "v0.13.*"
        }
    }

If you are a developer or if you want to help us with testing then fetch the latest branch for developers.
Notice: all contributions must be done against the developer branch.

Example:

.. code-block:: json

    {
        "require": {
           "phpoffice/phpword": "dev-develop"
        }
    }


Using samples
-------------

After installation, you can browse and use the samples that we've
provided, either by command line or using browser. If you can access
your PHPWord library folder using browser, point your browser to the
``samples`` folder, e.g. ``http://localhost/PhpWord/samples/``.
