.. _templates-processing:

Templates processing
====================

You can create an OOXML document template with included search-patterns (macros) which can be replaced by any value you wish. Only single-line values can be replaced.
Macros are defined like this: ``${search-pattern}``.
To load a template file, create a new instance of the TemplateProcessor.

.. code-block:: php

    $templateProcessor = new TemplateProcessor('Template.docx');

setValue
""""""""
Given a template containing

.. code-block:: clean

    Hello ${name}!

The following will replace ``${name}`` with ``World``. The resulting document will now contain ``Hello World!``

.. code-block:: php

    $templateProcessor->setValue('name', 'World');

setImageValue
"""""""""""""
The search-pattern model for images can be like:
    - ``${search-image-pattern}``
    - ``${search-image-pattern:[width]:[height]:[ratio]}``
    - ``${search-image-pattern:[width]x[height]}``
    - ``${search-image-pattern:size=[width]x[height]}``
    - ``${search-image-pattern:width=[width]:height=[height]:ratio=false}``

Where:
    - [width] and [height] can be just numbers or numbers with measure, which supported by Word (cm, mm, in, pt, pc, px, %, em, ex)
    - [ratio] uses only for ``false``, ``-`` or ``f`` to turn off respect aspect ration of image. By default template image size uses as 'container' size.

Example:

.. code-block:: clean

    ${CompanyLogo}
    ${UserLogo:50:50} ${Name} - ${City} - ${Street}

.. code-block:: php

    $templateProcessor = new TemplateProcessor('Template.docx');
    $templateProcessor->setValue('Name', 'John Doe');
    $templateProcessor->setValue(array('City', 'Street'), array('Detroit', '12th Street'));

    $templateProcessor->setImageValue('CompanyLogo', 'path/to/company/logo.png');
    $templateProcessor->setImageValue('UserLogo', array('path' => 'path/to/logo.png', 'width' => 100, 'height' => 100, 'ratio' => false));

cloneBlock
""""""""""
Given a template containing
See ``Sample_23_TemplateBlock.php`` for an example.

.. code-block:: clean

    ${block_name}
    Customer: ${customer_name}
    Address: ${customer_address}
    ${/block_name}

The following will duplicate everything between ``${block_name}`` and ``${/block_name}`` 3 times.

.. code-block:: php

    $templateProcessor->cloneBlock('block_name', 3, true, true);

The last parameter will rename any macro defined inside the block and add #1, #2, #3 ... to the macro name.
The result will be

.. code-block:: clean

    Customer: ${customer_name#1}
    Address: ${customer_address#1}
    
    Customer: ${customer_name#2}
    Address: ${customer_address#2}
    
    Customer: ${customer_name#3}
    Address: ${customer_address#3}

It is also possible to pass an array with the values to replace the marcros with.
If an array with replacements is passed, the ``count`` argument is ignored, it is the size of the array that counts.

.. code-block:: php

    $replacements = array(
        array('customer_name' => 'Batman', 'customer_address' => 'Gotham City'),
        array('customer_name' => 'Superman', 'customer_address' => 'Metropolis'),
    );
    $templateProcessor->cloneBlock('block_name', 0, true, false, $replacements);

The result will then be

.. code-block:: clean

    Customer: Batman
    Address: Gotham City
    
    Customer: Superman
    Address: Metropolis

replaceBlock
""""""""""""
Given a template containing

.. code-block:: clean

    ${block_name}
    This block content will be replaced
    ${/block_name}

The following will replace everything between``${block_name}`` and ``${/block_name}`` with the value passed.

.. code-block:: php

    $templateProcessor->replaceBlock('block_name', 'This is the replacement text.');

deleteBlock
"""""""""""
Same as previous, but it deletes the block

.. code-block:: php

    $templateProcessor->deleteBlock('block_name');

cloneRow
""""""""
Clones a table row in a template document.
See ``Sample_07_TemplateCloneRow.php`` for an example.

.. code-block:: clean

    ------------------------------
    | ${userId} | ${userName}    |
    |           |----------------|
    |           | ${userAddress} |
    ------------------------------

.. code-block:: php

    $templateProcessor->cloneRow('userId', 2);

Will result in

.. code-block:: clean

    ----------------------------------
    | ${userId#1} | ${userName#1}    |
    |             |------------------|
    |             | ${userAddress#1} |
    ---------------------------------|
    | ${userId#2} | ${userName#2}    |
    |             |------------------|
    |             | ${userAddress#2} |
    ----------------------------------

applyXslStyleSheet
""""""""""""""""""
Applies the XSL stylesheet passed to header part, footer part and main part

.. code-block:: php

    $xslDomDocument = new \DOMDocument();
    $xslDomDocument->load('/path/to/my/stylesheet.xsl');
    $templateProcessor->applyXslStyleSheet($xslDomDocument);
