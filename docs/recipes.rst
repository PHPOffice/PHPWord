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
        'height' => 40
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
