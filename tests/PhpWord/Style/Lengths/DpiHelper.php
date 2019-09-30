<?php
declare(strict_types=1);

namespace PhpOffice\PhpWord\Style\Lengths;

class DpiHelper implements Dpi
{
    private $dpi;

    public function __construct(float $dpi)
    {
        $this->dpi = $dpi;
    }

    public function getDpi(): float
    {
        return $this->dpi;
    }
}
