<?php

include_once 'Sample_Header.php';

// New Word Document
echo date('H:i:s') , ' Create new PhpWord object' , EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// A comment
$comment = new \PhpOffice\PhpWord\Element\Comment('Authors name', new \DateTime(), 'my_initials');
$comment->addText('Test', ['bold' => true]);
$phpWord->addComment($comment);

$section = $phpWord->addSection();

$textrun = $section->addTextRun();
$textrun->addText('This ');
$text = $textrun->addText('is');
$text->setCommentRangeStart($comment);
$textrun->addText(' a test');

$section->addTextBreak(2);

// Let's create a comment that we will link to a start element and an end element
$commentWithStartAndEnd = new \PhpOffice\PhpWord\Element\Comment('Foo Bar', new \DateTime());
$commentWithStartAndEnd->addText('A comment with a start and an end');
$phpWord->addComment($commentWithStartAndEnd);

$textrunWithEnd = $section->addTextRun();
$textrunWithEnd->addText('This ');
$textToStartOn = $textrunWithEnd->addText('is', ['bold' => true]);
$textToStartOn->setCommentRangeStart($commentWithStartAndEnd);
$textrunWithEnd->addText(' another', ['italic' => true]);
$textToEndOn = $textrunWithEnd->addText(' test');
$textToEndOn->setCommentRangeEnd($commentWithStartAndEnd);

$section->addTextBreak(2);

// Let's add a comment on an image
$commentOnImage = new \PhpOffice\PhpWord\Element\Comment('Mr Smart', new \DateTime());
$imageComment = $commentOnImage->addTextRun();
$imageComment->addText('Hey, Mars does look ');
$imageComment->addText('red', ['color' => 'FF0000']);
$phpWord->addComment($commentOnImage);
$image = $section->addImage(__DIR__ . '/resources/_mars.jpg');
$image->setCommentRangeStart($commentOnImage);

$section->addTextBreak(2);

// We can also do things the other way round, link the comment to the element
$anotherText = $section->addText('another text');

$comment1 = new \PhpOffice\PhpWord\Element\Comment('Authors name', new \DateTime(), 'my_initials');
$comment1->addText('Test', ['bold' => true]);
$comment1->setStartElement($anotherText);
$comment1->setEndElement($anotherText);
$phpWord->addComment($comment1);

// We can also do things the other way round, link the comment to the element
$lastText = $section->addText('with a last text and two comments');

$comment1 = new \PhpOffice\PhpWord\Element\Comment('Authors name', new \DateTime(), 'my_initials');
$comment1->addText('Comment 1', ['bold' => true]);
$comment1->setStartElement($lastText);
$comment1->setEndElement($lastText);
$phpWord->addComment($comment1);

$comment2 = new \PhpOffice\PhpWord\Element\Comment('Authors name', new \DateTime(), 'my_initials');
$comment2->addText('Comment 2', ['bold' => true]);
$comment2->setStartElement($lastText);
$comment2->setEndElement($lastText);
$phpWord->addComment($comment2);

// Save file
echo write($phpWord, basename(__FILE__, '.php'), $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}
