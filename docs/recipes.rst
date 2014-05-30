.. _recipes:

Recipes
=======

Create float left image
-----------------------

Use absolute positioning relative to margin horizontally and to line
vertically.

.. code-block:: php

    $imageStyle = array(
        'width' => 40,
        'height' => 40,
        'wrappingStyle' => 'square',
        'positioning' => 'absolute',
        'posHorizontalRel' => 'margin',
        'posVerticalRel' => 'line',
    );
    $textrun->addImage('resources/_earth.jpg', $imageStyle);
    $textrun->addText($lipsumText);

Download the produced file automatically
----------------------------------------

Use ``php://output`` as the filename.

.. code-block:: php

    $phpWord = new \PhpOffice\PhpWord\PhpWord();
    $section = $phpWord->createSection();
    $section->addText('Hello World!');
    $file = 'HelloWorld.docx';
    header("Content-Description: File Transfer");
    header('Content-Disposition: attachment; filename="' . $file . '"');
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Expires: 0');
    $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
    $xmlWriter->save("php://output");

Create numbered headings
------------------------

Define a numbering style and title styles, and match the two styles (with ``pStyle`` and ``numStyle``) like below.

.. code-block:: php

    $phpWord->addNumberingStyle(
        'hNum',
        array('type' => 'multilevel', 'levels' => array(
            array('pStyle' => 'Heading1', 'format' => 'decimal', 'text' => '%1'),
            array('pStyle' => 'Heading2', 'format' => 'decimal', 'text' => '%1.%2'),
            array('pStyle' => 'Heading3', 'format' => 'decimal', 'text' => '%1.%2.%3'),
            )
        )
    );
    $phpWord->addTitleStyle(1, array('size' => 16), array('numStyle' => 'hNum', 'numLevel' => 0));
    $phpWord->addTitleStyle(2, array('size' => 14), array('numStyle' => 'hNum', 'numLevel' => 1));
    $phpWord->addTitleStyle(3, array('size' => 12), array('numStyle' => 'hNum', 'numLevel' => 2));

    $section->addTitle('Heading 1', 1);
    $section->addTitle('Heading 2', 2);
    $section->addTitle('Heading 3', 3);
