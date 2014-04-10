.. _general:

General usage
=============

Basic example
-------------

The following is a basic example of the PHPWord library. More examples
are provided in the `samples folder <https://github.com/PHPOffice/PHPWord/tree/master/samples/>`__.

.. code-block:: php

    require_once 'src/PhpWord/Autoloader.php';
    PhpOffice\PhpWord\Autoloader::register();

    $phpWord = new \PhpOffice\PhpWord\PhpWord();

    // Every element you want to append to the word document is placed in a section.
    // To create a basic section:
    $section = $phpWord->createSection();

    // After creating a section, you can append elements:
    $section->addText('Hello world!');

    // You can directly style your text by giving the addText function an array:
    $section->addText('Hello world! I am formatted.',
        array('name'=>'Tahoma', 'size'=>16, 'bold'=>true));

    // If you often need the same style again you can create a user defined style
    // to the word document and give the addText function the name of the style:
    $phpWord->addFontStyle('myOwnStyle',
        array('name'=>'Verdana', 'size'=>14, 'color'=>'1B2232'));
    $section->addText('Hello world! I am formatted by a user defined style',
        'myOwnStyle');

    // You can also put the appended element to local object like this:
    $fontStyle = new \PhpOffice\PhpWord\Style\Font();
    $fontStyle->setBold(true);
    $fontStyle->setName('Verdana');
    $fontStyle->setSize(22);
    $myTextElement = $section->addText('Hello World!');
    $myTextElement->setFontStyle($fontStyle);

    // Finally, write the document:
    $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
    $objWriter->save('helloWorld.docx');

    $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'ODText');
    $objWriter->save('helloWorld.odt');

    $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'RTF');
    $objWriter->save('helloWorld.rtf');

Default font
------------

By default, every text appears in Arial 10 point. You can alter the
default font by using the following two functions:

.. code-block:: php

    $phpWord->setDefaultFontName('Times New Roman');
    $phpWord->setDefaultFontSize(12);

Document properties
-------------------

You can set the document properties such as title, creator, and company
name. Use the following functions:

.. code-block:: php

    $properties = $phpWord->getDocumentProperties();
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
        'spaceAfter' => \PhpOffice\PhpWord\Shared\Font::pointSizeToTwips(6))
    );

    $section = $phpWord->createSection();
    $sectionStyle = $section->getSettings();
    // half inch left margin
    $sectionStyle->setMarginLeft(\PhpOffice\PhpWord\Shared\Font::inchSizeToTwips(.5));
    // 2 cm right margin
    $sectionStyle->setMarginRight(\PhpOffice\PhpWord\Shared\Font::centimeterSizeToTwips(2));
