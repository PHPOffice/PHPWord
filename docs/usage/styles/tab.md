# Tab Style

``` php
<?php

use PhpOffice\PhpWord\Style\Tab as TabStyle;
$phpWord->addParagraphStyle(
  'tabStyle',
  [
  'tabs' => [
      new TabStyle(TabStyle::TAB_STOP_LEFT, 1440, TabStyle::TAB_LEADER_DOT),
      new TabStyle(TabStyle::TAB_STOP_CENTER, 5000),
      new TabStyle(TabStyle::TAB_STOP_RIGHT, 9340),
    ],
  ]
);

```

See [`Sample_02_TabStops`](/samples/Sample_02_TabStops.php) for more code samples.

## Constants
- Tab Stop
  * `TAB_STOP_CLEAR`. Removes an existing tab.
  * `TAB_STOP_LEFT`.
  * `TAB_STOP_CENTER`.
  * `TAB_STOP_RIGHT`.
  * `TAB_STOP_DECIMAL`. All text is aligned around the first decimal character found within the text.
  * `TAB_STOP_BAR`. Does not result in a tab stop but instead a vertical bar is drawn at the location.
  * `TAB_STOP_NUM`. This is for backward compatibility and should be avoided in favor of paragraph indentation.

- Tab Leader
  * `TAB_LEADER_NONE`.
  * `TAB_LEADER_DOT`.
  * `TAB_LEADER_HYPHEN`.
  * `TAB_LEADER_UNDERSCORE`.
  * `TAB_LEADER_HEAVY`.
  * `TAB_LEADER_MIDDLEDOT`.

## Options
- `type`. Stop type. `TAB_STOP_CLEAR` is default.
   * See constants above for possible values.
- `leader`. Tab leader character. `TAB_LEADER_NONE` is default.
   * See constants above for possible values.
- `position`. Tab stop position in *twip*.

## Used In
- [`Style > Paragraph`](../styles/paragraph.md).
