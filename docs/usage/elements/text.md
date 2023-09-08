# Text


Text can be added by using ``addText`` and ``addTextRun`` methods. ``addText`` is used for creating simple paragraphs that only contain texts with the same style. ``addTextRun`` is used for creating complex paragraphs that contain text with different style (some bold, other italics, etc) or other elements, e.g. images or links. The syntaxes are as follow:

``` php
<?php

$section->addText($text, [$fontStyle], [$paragraphStyle]);
$textrun = $section->addTextRun([$paragraphStyle]);
```

- ``$text``. Text to be displayed in the document.
- ``$fontStyle``. See [`Styles > Font`](../styles/font.md).
- ``$paragraphStyle``. See [`Styles > Paragraph`](../styles/paragraph.md).

For available styling options, see [`Styles > Font`](../styles/font.md) and [`Styles > Paragraph`](../styles/paragraph.md).

If you want to enable track changes on added text you can mark it as INSERTED or DELETED by a specific user at a given time:

``` php
<?php

$text = $section->addText('Hello World!');
$text->setChanged(\PhpOffice\PhpWord\Element\ChangedElement::TYPE_INSERTED, 'Fred', (new \DateTime()));
```