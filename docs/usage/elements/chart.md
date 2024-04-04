# Chart

Charts can be added using

``` php
<?php

$categories = array('A', 'B', 'C', 'D', 'E');
$series = array(1, 3, 2, 5, 4);
$chart = $section->addChart('line', $categories, $series, $style);
```

For available styling options, see [`Styles > Chart`](../styles/chart.md).

Check out the Sample_32_Chart.php for more options and styling.