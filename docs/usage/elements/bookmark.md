# Bookmark

Bookmarks identify a position in a document so that fields and links can refer to it.

``` php
<?php

$section->addBookmark('introduction');
```

The ODText writer serializes bookmarks as native ODF point bookmarks. Bookmark ranges and cross-reference fields are separate features.
