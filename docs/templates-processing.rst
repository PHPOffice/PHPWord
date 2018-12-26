.. _templates-processing:

Templates processing
====================

You can create an OOXML document template with included search-patterns (macros) which can be replaced by any value you wish. Only single-line values can be replaced.

To deal with a template file, use ``new TemplateProcessor`` statement. After TemplateProcessor instance creation the document template is copied into the temporary directory. Then you can use ``TemplateProcessor::setValue`` method to change the value of a search pattern. The search-pattern model is: ``${search-pattern}``.

The search-pattern model for images can be like:
- ``${search-image-pattern}``
- ``${search-image-pattern:[width]:[height]:[ratio]}``
- ``${search-image-pattern:[width]x[height]}``
- ``${search-image-pattern:size=[width]x[height]}``
- ``${search-image-pattern:width=[width]:height=[height]:ratio=false}``
Where:
- [width] and [height] can be just numbers or numbers with measure, which supported by Word (cm|mm|in|pt|pc|px|%|em|ex)
- [ratio] uses only for ``false``, ``-`` or ``f`` to turn off respect aspect ration of image. By default template image size uses as 'container' size.

Example:

.. code-block:: doc

	${CompanyLogo}
    ${UserLogo:50:50} ${Name} - ${City} - ${Street}

.. code-block:: php

    $templateProcessor = new TemplateProcessor('Template.docx');
    $templateProcessor->setValue('Name', 'John Doe');
    $templateProcessor->setValue(array('City', 'Street'), array('Detroit', '12th Street'));

	$templateProcessor->setImageValue('CompanyLogo', 'path/to/company/logo.png');
	$templateProcessor->setImageValue('UserLogo', array('path' => 'path/to/logo.png', 'width' => 100, 'height' => 100, 'ratio' => false));

It is not possible to directly add new OOXML elements to the template file being processed, but it is possible to transform headers, main document part, and footers of the template using XSLT (see ``TemplateProcessor::applyXslStyleSheet``).

See ``Sample_07_TemplateCloneRow.php`` for example on how to create
multirow from a single row in a template by using ``TemplateProcessor::cloneRow``.

See ``Sample_23_TemplateBlock.php`` for example on how to clone a block
of text using ``TemplateProcessor::cloneBlock`` and delete a block of text using
``TemplateProcessor::deleteBlock``.
