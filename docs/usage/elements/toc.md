# Table of contents

To add a table of contents (TOC), you can use the ``addTOC`` method.
Your TOC can only be generated if you have add at least one title (See "[Title](title.md)").

``` php
<?php

$section->addTOC([$fontStyle], [$tocStyle], [$minDepth], [$maxDepth]);
```

- ``$fontStyle``. See font style section.
- ``$tocStyle``. See available options below.
- ``$minDepth``. Minimum depth of header to be shown. Default 1.
- ``$maxDepth``. Maximum depth of header to be shown. Default 9.

Options for ``$tocStyle``:

- ``tabLeader``. Fill type between the title text and the page number. Use the defined constants in ``\PhpOffice\PhpWord\Style\TOC``.
- ``tabPos``. The position of the tab where the page number appears in *twip*.
- ``indent``. The indent factor of the titles in *twip*.