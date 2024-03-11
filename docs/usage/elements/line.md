# Line

Line elements can be added to sections by using ``addLine``.

``` php
<?php

$lineStyle = array('weight' => 1, 'width' => 100, 'height' => 0, 'color' => 635552);
$section->addLine($lineStyle);
```

Available line style attributes:

- ``weight``. Line width in *twip*.
- ``color``. Defines the color of stroke.
- ``dash``. Line types: dash, rounddot, squaredot, dashdot, longdash, longdashdot, longdashdotdot.
- ``beginArrow``. Start type of arrow: block, open, classic, diamond, oval.
- ``endArrow``. End type of arrow: block, open, classic, diamond, oval.
- ``width``. Line-object width in *pt*.
- ``height``. Line-object height in *pt*.
- ``flip``. Flip the line element: true, false.