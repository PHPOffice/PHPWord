# Table

To add tables, rows, and cells, use the ``addTable``, ``addRow``, and ``addCell`` methods:

``` php
<?php

$table = $section->addTable([$tableStyle]);
$table->addRow([$height], [$rowStyle]);
$cell = $table->addCell($width, [$cellStyle]);
```

Table style can be defined with ``addTableStyle``:

``` php
<?php

$tableStyle = array(
    'borderColor' => '006699',
    'borderSize'  => 6,
    'cellMargin'  => 50
);
$firstRowStyle = array('bgColor' => '66BBFF');
$phpWord->addTableStyle('myTable', $tableStyle, $firstRowStyle);
$table = $section->addTable('myTable');
```

For available styling options see [`Styles > Table`](../styles/table.md).

## Cell span

You can span a cell on multiple columns by using ``gridSpan`` or multiple rows by using ``vMerge``.

``` php
<?php

$cell = $table->addCell(200);
$cell->getStyle()->setGridSpan(5);
```

See ``Sample_09_Tables.php`` for more code sample.