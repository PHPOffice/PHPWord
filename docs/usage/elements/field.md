# Field

Currently the following fields are supported:

- PAGE
- NUMPAGES
- DATE
- XE
- INDEX
- FILENAME
- REF

``` php
<?php

$section->addField($fieldType, [$properties], [$options], [$fieldText], [$fontStyle])
```

- ``$fontStyle``. See [`Styles > Font`](../styles/font.md).

See ``\PhpOffice\PhpWord\Element\Field`` for list of properties and options available for each field type.
Options which are not specifically defined can be added. Those must start with a ``\``.

For instance for the INDEX field, you can do the following (See `Index Field for list of available options <https://support.office.com/en-us/article/Field-codes-Index-field-adafcf4a-cb30-43f6-85c7-743da1635d9e?ui=en-US&rs=en-US&ad=US>`_ ):

``` php
<?php

// the $fieldText can be either a simple string
$fieldText = 'The index value';

// or a 'TextRun', to be able to format the text you want in the index
$fieldText = new TextRun();
$fieldText->addText('My ');
$fieldText->addText('bold index', ['bold' => true]);
$fieldText->addText(' entry');
$section->addField('XE', array(), array(), $fieldText);

// this actually adds the index
$section->addField('INDEX', array(), array('\\e "	" \\h "A" \\c "3"'), 'right click to update index');

// Adding reference to a bookmark
$fieldText->addField('REF', [
    'name' => 'bookmark'
], [
    'InsertParagraphNumberRelativeContext',
    'CreateHyperLink',
]);
```
