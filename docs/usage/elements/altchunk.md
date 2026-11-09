# AltChunk

An existing Word document can be inserted into a section with the ``addAltChunk`` method.

``` php
<?php

$section->addText('Main document');
$section->addPageBreak();
$section->addAltChunk('annex.docx');
```

The file is stored in the package as is, and a ``w:altChunk`` element is written at this place of the body. Word imports the content of the inserted document when the file is opened: its direct formatting, numbering, images and tables are kept, while a style that also exists in the main document (for example ``Normal``) takes the main document's definition.

- The source must be an existing ``.docx`` file.
- The element can only be added to a section.
- Section breaks inside the inserted document are kept, but not its last section properties: the end of the inserted content takes the properties of the section it is placed in. If the inserted document has its own page layout (margins, orientation), add it to a new section so that it does not change the layout of the main document.
- It is only written by the Word2007 writer. Other writers skip it, and the inserted content is not visible to the Word2007 reader either, since it is imported by Word at opening time.
