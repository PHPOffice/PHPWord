<?php
declare(strict_types=1);

namespace PhpOffice\PhpWord\Style\Colors;

/**
 * A color that can be used as a color, background, border color, etc.
 * Should be an instance of StaticColorInterface or NamedColorInterface (for ThemeColor).
 */
abstract class BasicColor extends SpecialColor
{
    public static function fromMixed($value = null): self
    {
        if ($value instanceof self) {
            return $value;
        } elseif ($value === null || $value === '' || $value === 'auto') {
            return new Hex(null);
        } elseif (is_string($value)) {
            if (Hex::isValid($value)) {
                return new Hex($value);
            } elseif (ThemeColor::isValid($value)) {
                return new ThemeColor($value);
            }
        }

        trigger_error(sprintf('Color `%s` is not a valid color', $value), E_USER_WARNING);

        return new Hex(null);
    }
}
