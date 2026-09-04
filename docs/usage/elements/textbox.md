# TextBox

The ODText writer serializes text boxes as native ODF drawing frames. The
frame preserves supported dimensions, background and border colors, inner
margins, and rich text content:

```php
$textBox = $section->addTextBox([
    'width' => 400,
    'height' => 150,
    'bgColor' => '#eeeeee',
    'borderSize' => 1,
    'borderColor' => '#333333',
]);
$textBox->addText('Text box content.');
```

The generated ODF uses `draw:frame` with a nested `draw:text-box`. Positioning
options that have no direct standard ODF equivalent are not approximated.
