.. _general:

General usage
=============

Basic example
-------------

The following is a basic example of the PHPWord library. More examples
are provided in the `samples
folder <https://github.com/PHPOffice/PHPWord/tree/master/samples/>`__.

.. code-block:: php

    <?php
    require_once 'bootstrap.php';

    // Creating the new document...
    $phpWord = new \PhpOffice\PhpWord\PhpWord();

    /* Note: any element you append to a document must reside inside of a Section. */

    // Adding an empty Section to the document...
    $section = $phpWord->addSection();
    // Adding Text element to the Section having font styled by default...
    $section->addText(
        '"Learn from yesterday, live for today, hope for tomorrow. '
            . 'The important thing is not to stop questioning." '
            . '(Albert Einstein)'
    );

    /*
     * Note: it's possible to customize font style of the Text element you add in three ways:
     * - inline;
     * - using named font style (new font style object will be implicitly created);
     * - using explicitly created font style object.
     */

    // Adding Text element with font customized inline...
    $section->addText(
        '"Great achievement is usually born of great sacrifice, '
            . 'and is never the result of selfishness." '
            . '(Napoleon Hill)',
        array('name' => 'Tahoma', 'size' => 10)
    );

    // Adding Text element with font customized using named font style...
    $fontStyleName = 'oneUserDefinedStyle';
    $phpWord->addFontStyle(
        $fontStyleName,
        array('name' => 'Tahoma', 'size' => 10, 'color' => '1B2232', 'bold' => true)
    );
    $section->addText(
        '"The greatest accomplishment is not in never falling, '
            . 'but in rising again after you fall." '
            . '(Vince Lombardi)',
        $fontStyleName
    );

    // Adding Text element with font customized using explicitly created font style object...
    $fontStyle = new \PhpOffice\PhpWord\Style\Font();
    $fontStyle->setBold(true);
    $fontStyle->setName('Tahoma');
    $fontStyle->setSize(13);
    $myTextElement = $section->addText('"Believe you can and you\'re halfway there." (Theodor Roosevelt)');
    $myTextElement->setFontStyle($fontStyle);

    // Saving the document as OOXML file...
    $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
    $objWriter->save('helloWorld.docx');

    // Saving the document as ODF file...
    $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'ODText');
    $objWriter->save('helloWorld.odt');

    // Saving the document as HTML file...
    $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
    $objWriter->save('helloWorld.html');

    /* Note: we skip RTF, because it's not XML-based and requires a different example. */
    /* Note: we skip PDF, because "HTML-to-PDF" approach is used to create PDF documents. */

Settings
--------

The ``PhpOffice\PhpWord\Settings`` class provides some options that will
affect the behavior of PHPWord. Below are the options.

XML Writer compatibility
~~~~~~~~~~~~~~~~~~~~~~~~

This option sets
`XMLWriter::setIndent <http://www.php.net/manual/en/function.xmlwriter-set-indent.php>`__
and
`XMLWriter::setIndentString <http://www.php.net/manual/en/function.xmlwriter-set-indent-string.php>`__.
The default value of this option is ``true`` (compatible), which is
`required for
OpenOffice <https://github.com/PHPOffice/PHPWord/issues/103>`__ to
render OOXML document correctly. You can set this option to ``false``
during development to make the resulting XML file easier to read.

.. code-block:: php

    \PhpOffice\PhpWord\Settings::setCompatibility(false);

Zip class
~~~~~~~~~

By default, PHPWord uses `Zip extension <http://php.net/manual/en/book.zip.php>`__
to deal with ZIP compressed archives and files inside them. If you can't have
Zip extension installed on your server, you can use pure PHP library
alternative, `PclZip <http://www.phpconcept.net/pclzip/>`__, which
included with PHPWord.

.. code-block:: php

    \PhpOffice\PhpWord\Settings::setZipClass(\PhpOffice\PhpWord\Settings::PCLZIP);

Output escaping
~~~~~~~~~~~~~~~

Writing documents of some formats, especially XML-based, requires correct output escaping.
Without it your document may become broken when you put special characters like ampersand, quotes, and others in it.

Escaping can be performed in two ways: outside of the library by a software developer and inside of the library by built-in mechanism.
By default, the built-in mechanism is disabled for backward compatibility with versions prior to v0.13.0.
To turn it on set ``outputEscapingEnabled`` option to ``true`` in your PHPWord configuration file or use the following instruction at runtime:

.. code-block:: php

    \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);

Default font
~~~~~~~~~~~~

By default, every text appears in Arial 10 point. You can alter the
default font by using the following two functions:

.. code-block:: php

    $phpWord->setDefaultFontName('Times New Roman');
    $phpWord->setDefaultFontSize(12);

Document information
--------------------

You can set the document information such as title, creator, and company
name. Use the following functions:

.. code-block:: php

    $properties = $phpWord->getDocInfo();
    $properties->setCreator('My name');
    $properties->setCompany('My factory');
    $properties->setTitle('My title');
    $properties->setDescription('My description');
    $properties->setCategory('My category');
    $properties->setLastModifiedBy('My name');
    $properties->setCreated(mktime(0, 0, 0, 3, 12, 2014));
    $properties->setModified(mktime(0, 0, 0, 3, 14, 2014));
    $properties->setSubject('My subject');
    $properties->setKeywords('my, key, word');

Measurement units
-----------------

The base length unit in Open Office XML is twip. Twip means "TWentieth
of an Inch Point", i.e. 1 twip = 1/1440 inch.

You can use PHPWord helper functions to convert inches, centimeters, or
points to twips.

.. code-block:: php

    // Paragraph with 6 points space after
    $phpWord->addParagraphStyle('My Style', array(
        'spaceAfter' => \PhpOffice\PhpWord\Shared\Converter::pointToTwip(6))
    );

    $section = $phpWord->addSection();
    $sectionStyle = $section->getStyle();
    // half inch left margin
    $sectionStyle->setMarginLeft(\PhpOffice\PhpWord\Shared\Converter::inchToTwip(.5));
    // 2 cm right margin
    $sectionStyle->setMarginRight(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(2));
