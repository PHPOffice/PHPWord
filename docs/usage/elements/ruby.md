# Ruby

Ruby (phonetic guide) text can be added by using the ``addRuby`` method. Ruby elements require a ``RubyProperties`` object, a ``TextRun`` for the base text, and a ``TextRun`` for the actual ruby (phonetic guide) text.

Here is one example for a complete ruby element setup:

``` php
<?php
$phpWord = new PhpWord();

$section = $phpWord->addSection();
$properties = new RubyProperties();
$properties->setAlignment(RubyProperties::ALIGNMENT_RIGHT_VERTICAL);
$properties->setFontFaceSize(10);
$properties->setFontPointsAboveBaseText(4);
$properties->setFontSizeForBaseText(18);
$properties->setLanguageId('ja-JP');

$baseTextRun = new TextRun(null);
$baseTextRun->addText('私');
$rubyTextRun = new TextRun(null);
$rubyTextRun->addText('わたし');

$section->addRuby($baseTextRun, $rubyTextRun, $properties);
```

- ``$baseTextRun``. ``TextRun`` to be used for the base text.
- ``$rubyTextRun``. ``TextRun`` to be used for the ruby text.
- ``$properties``. ``RubyProperties`` properties object for the ruby text.

A title with a phonetic guide is a little more complex, but still possible. Make sure you add the appropraite title style to your document.

```php
$phpWord = new PhpWord();
$fontStyle = new Font();
$fontStyle->setAllCaps(true);
$fontStyle->setBold(true);
$fontStyle->setSize(24);
$phpWord->addTitleStyle(1, ['name' => 'Arial', 'size' => 24, 'bold' => true, 'color' => '990000']);

$section = $phpWord->addSection();
$properties = new RubyProperties();
$properties->setAlignment(RubyProperties::ALIGNMENT_RIGHT_VERTICAL);
$properties->setFontFaceSize(10);
$properties->setFontPointsAboveBaseText(4);
$properties->setFontSizeForBaseText(18);
$properties->setLanguageId('ja-JP');

$baseTextRun = new TextRun(null);
$baseTextRun->addText('私');
$rubyTextRun = new TextRun(null);
$rubyTextRun->addText('わたし');

$textRun = new TextRun();
$textRun->addRuby($baseTextRun, $rubyTextRun, $properties);
$section->addTitle($textRun, 1);
```