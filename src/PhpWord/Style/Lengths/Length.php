<?php
declare(strict_types=1);

namespace PhpOffice\PhpWord\Style\Lengths;

abstract class Length
{
    abstract public function isSpecified(): bool;
}
