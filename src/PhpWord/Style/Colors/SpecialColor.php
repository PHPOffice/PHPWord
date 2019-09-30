<?php
declare(strict_types=1);

namespace PhpOffice\PhpWord\Style\Colors;

use PhpOffice\PhpWord\Exception\Exception;

/**
 * A color that can be used anywhere that accepts SystemColors.
 * Typically, BasicColor is preferred over this.
 */
abstract class SpecialColor
{
    final public function toHexOrName(bool $includeHash = false)
    {
        if ($this instanceof NamedColorInterface) {
            return $this->getName();
        }
        if ($this instanceof StaticColorInterface) {
            return $this->toHex($includeHash);
        }
        throw new Exception(sprintf('All colors must implement NamedColorInterface or StaticColorInterface. \'%s\' does not implement either.', get_class($this)));
    }

    abstract public function isSpecified(): bool;
}
