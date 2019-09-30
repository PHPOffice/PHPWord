<?php
declare(strict_types=1);

namespace PhpOffice\PhpWord\Style\Colors;

interface StaticColorInterface
{
    public function toRgb();

    public function toHex(bool $includeHash = false);
}
