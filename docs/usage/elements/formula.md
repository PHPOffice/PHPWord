# Formula

Formula can be added using

``` php
<?php

use PhpOffice\Math\Element;
use PhpOffice\Math\Math;

$fraction = new Element\Fraction();
$fraction
    ->setDenominator(new Element\Numeric(2))
    ->setNumerator(new Element\Identifier('π'))
;

$math = new Math();
$math->add($fraction);

$formula = $section->addFormula($math);
```