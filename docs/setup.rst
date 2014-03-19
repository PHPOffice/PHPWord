.. _setup:

Installing
==========

Requirements
------------

Mandatory:

-  PHP 5.3+
-  PHP `Zip`_ extension
-  PHP `XML Parser`_ extension

Optional PHP extensions:

-  `GD`_
-  `XMLWriter`_
-  `XSL`_

.. _Zip: http://php.net/manual/en/book.zip.php
.. _XML Parser: http://www.php.net/manual/en/xml.installation.php
.. _GD: http://php.net/manual/en/book.image.php
.. _XMLWriter: http://php.net/manual/en/book.xmlwriter.php
.. _XSL: http://php.net/manual/en/book.xsl.php

Installation
------------

There are two ways to install PHPWord, i.e. via `Composer`_ or manually
by downloading the library.

Composer
~~~~~~~~

To install via Composer, add the following lines to your
``composer.json``:

.. code:: json

    {
        "require": {
           "phpoffice/phpword": "dev-master"
        }
    }

.. _Composer: http://getcomposer.org/

Manual installation
~~~~~~~~~~~~~~~~~~~

To install manually, `download PHPWord package from github`_. Extract
the package and put the contents to your machine. To use the library,
include ``Classes/PHPWord.php`` in your script like below.

.. code:: php

    require_once '/path/to/PHPWord/Classes/PHPWord.php';

.. _download PHPWord package from github: https://github.com/PHPOffice/PHPWord/archive/master.zip

Using samples
-------------

After installation, you can browse and use the samples that we’ve
provided, either by command line or using browser. If you can access
your PHPWord library folder using browser, point your browser to the
``samples`` folder, e.g. ``http://localhost/PHPWord/samples/``.
