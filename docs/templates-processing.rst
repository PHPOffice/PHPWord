.. _templates-processing:

Templates processing
====================

You can create a .docx document template with included search-patterns which can be replaced by any value you wish. Only single-line values can be replaced.

To deal with a template file, use ``new TemplateProcessor`` statement. After TemplateProcessor instance creation the document template is copied into the temporary directory. Then you can use ``TemplateProcessor::setValue`` method to change the value of a search pattern. The search-pattern model is: ``${search-pattern}``.

Example:

.. code-block:: php

    $templateProcessor = new TemplateProcessor('Template.docx');
    $templateProcessor->setValue('Name', 'Somebody someone');
    $templateProcessor->setValue('Street', 'Coming-Undone-Street 32');

It is not possible to directly add new OOXML elements to the template file being processed, but it is possible to transform main document part of the template using XSLT (see ``TemplateProcessor::applyXslStyleSheet``).

See ``Sample_07_TemplateCloneRow.php`` for example on how to create
multirow from a single row in a template by using ``TemplateProcessor::cloneRow``.

See ``Sample_23_TemplateBlock.php`` for example on how to clone a block
of text using ``TemplateProcessor::cloneBlock`` and delete a block of text using
``TemplateProcessor::deleteBlock``.



Merge field processing
======================

NOTE: This method has been successfully tested on a limited number of Word template documents. However, due to the complex nature of the underlying XML format, it may not work in all circumstances.

This method was designed to find and replace text using the standard Word "mail merge fields" in a document (including body, headers, and footers). When viewing a document in Word, these typically appear encapsulated as &laquo;FIELD_NAME&raquo;.

Values for all merge fields within a document are passed as an array. The methods 'getMergeSuccess' and 'getMergeFailure' each return an array showing the status of the merge replacements throughout the document sections.

Example:

.. code-block:: php

	$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('Template.docx');
	$templateProcessor->setMergeData(array(
		'FIELD_NAME'		=> 'My Name',
		'OTHER_VALUE'		=> $value
	));
    $templateProcessor->doMerge();
	print_r( $templateProcessor->getMergeSuccess() );
	print_r( $templateProcessor->getMergeFailure() );
