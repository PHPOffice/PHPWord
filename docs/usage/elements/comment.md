# Comment

Comments can be added to a document by using ``addComment``.
The comment can contain formatted text. Once the comment has been added, it can be linked to any element with ``setCommentRangeStart``.

``` php
<?php

// first create a comment
$comment= new \PhpOffice\PhpWord\Element\Comment('Authors name', new \DateTime(), 'my_initials');
$comment->addText('Test', array('bold' => true));

// add it to the document
$phpWord->addComment($comment);

$textrun = $section->addTextRun();
$textrun->addText('This ');
$text = $textrun->addText('is');
// link the comment to the text you just created
$text->setCommentRangeStart($comment);
$textrun->addText(' a test');
```

If no end is set for a comment using the ``setCommentRangeEnd``, the comment will be ended automatically at the end of the element it is started on.