# Checkbox

Checkbox elements can be added to sections or table cells by using ``addCheckBox``.

``` php
<?php

$section->addCheckBox($name, $text, [$fontStyle], [$paragraphStyle]);
```

- ``$name``. Name of the check box.
- ``$text``. Text to be displayed in the document.
- ``$fontStyle``. See [`Styles > Font`](../styles/font.md).
- ``$paragraphStyle``. See [`Styles > Paragraph`](../styles/paragraph.md).

When written with the `ODText` writer, the checkbox is represented by a native
ODF form control and retains its name and visible label.
