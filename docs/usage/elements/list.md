# List

Lists can be added by using ``addListItem`` and ``addListItemRun`` methods. ``addListItem`` is used for creating lists that only contain plain text. ``addListItemRun`` is used for creating complex list items that contains texts with different style (some bold, other italics, etc) or other elements, e.g. images or links. The syntaxes are as follow:

Basic usage:

``` php
<?php

$section->addListItem($text, [$depth], [$fontStyle], [$listStyle], [$paragraphStyle]);
$listItemRun = $section->addListItemRun([$depth], [$listStyle], [$paragraphStyle])
``` 

Parameters:

- ``$text``. Text that appears in the document.
- ``$depth``. Depth of list item.
- ``$fontStyle``. See [`Styles > Font`](../styles/font.md)..
- ``$listStyle``. List style of the current element TYPE\_NUMBER,
  TYPE\_ALPHANUM, TYPE\_BULLET\_FILLED, etc. See list of constants in PHPWord\\Style\\ListItem.
- ``$paragraphStyle``. See [`Styles > Paragraph`](../styles/paragraph.md)..

See ``Sample_14_ListItem.php`` for more code sample.

Advanced usage:

You can also create your own numbering style by changing the ``$listStyle`` parameter with the name of your numbering style.

``` php
<?php

$phpWord->addNumberingStyle(
    'multilevel',
    array(
        'type' => 'multilevel',
        'levels' => array(
            array('format' => 'decimal', 'text' => '%1.', 'left' => 360, 'hanging' => 360, 'tabPos' => 360),
            array('format' => 'upperLetter', 'text' => '%2.', 'left' => 720, 'hanging' => 360, 'tabPos' => 720),
        )
    )
);
$section->addListItem('List Item I', 0, null, 'multilevel');
$section->addListItem('List Item I.a', 1, null, 'multilevel');
$section->addListItem('List Item I.b', 1, null, 'multilevel');
$section->addListItem('List Item II', 0, null, 'multilevel');
``` 

For available styling options see [`Styles > Numbering Level`](../styles/numberinglevel.md).