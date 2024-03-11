# Track Changes

Track changes can be set on text elements. There are 2 ways to set the change information on an element.
Either by calling the `setChangeInfo()`, or by setting the `TrackChange` instance on the element with `setTrackChange()`.

``` php
<?php

$phpWord = new \PhpOffice\PhpWord\PhpWord();

// New portrait section
$section = $phpWord->addSection();
$textRun = $section->addTextRun();

$text = $textRun->addText('Hello World! Time to ');

$text = $textRun->addText('wake ', array('bold' => true));
$text->setChangeInfo(TrackChange::INSERTED, 'Fred', time() - 1800);

$text = $textRun->addText('up');
$text->setTrackChange(new TrackChange(TrackChange::INSERTED, 'Fred'));

$text = $textRun->addText('go to sleep');
$text->setChangeInfo(TrackChange::DELETED, 'Barney', new \DateTime('@' . (time() - 3600)));
```