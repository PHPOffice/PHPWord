.. _templates:

Templates
=========

You can create a docx template with included search-patterns that can be
replaced by any value you wish. Only single-line values can be replaced.
To load a template file, use the ``loadTemplate`` method. After loading
the docx template, you can use the ``setValue`` method to change the
value of a search pattern. The search-pattern model is:
``${search-pattern}``. It is not possible to add new PHPWord elements to
a loaded template file.

Example:

.. code-block:: php

    $template = $phpWord->loadTemplate('Template.docx');
    $template->setValue('Name', 'Somebody someone');
    $template->setValue('Street', 'Coming-Undone-Street 32');

See ``Sample_07_TemplateCloneRow.php`` for more code sample, including
how to create multirow from a single row in a template by using
``cloneRow``.
