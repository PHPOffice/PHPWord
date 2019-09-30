<?php
declare(strict_types=1);

namespace PhpOffice\PhpWord\Style\Lengths;

class Percent extends Length
{
    private $percent = null;

    public function __construct(float $percent = null)
    {
        $this->percent = $percent;
    }

    public function isSpecified(): bool
    {
        return $this->percent !== null;
    }

    public function toInt()
    {
        $float = $this->toFloat();

        return $float === null ? null : (int) round($float);
    }

    public function toFloat()
    {
        return $this->percent;
    }

    public static function fromMixed($value = null): self
    {
        if ($value instanceof self) {
            return clone $value;
        }

        // The 2006 version of the OOXML standard specified that the value was to be a decimal. When type="pct", the value was interpretted as fifths of a percent, so 4975=99.5%, and no % symbol was included in the attribute. In the 2011 version the value can be either a decimal or a percent, so a % symbol should be included when type="pct".
        // @see http://officeopenxml.com/WPtableCellProperties-Width.php
        $divideBy50 = true;

        if (!is_string($value)) {
            $float = $value;
        } elseif (preg_match('/^([0-9]+\\.?|[0-9]*\\.[0-9]+)%$/', $value)) {
            $divideBy50 = false;
            $float = (float) substr($value, 0, -1);
        } elseif (!preg_match('/[^\d\.\,]/', $value)) {
            $float = (float) $value;
        } else {
            $float = $value;
        }
        if (!is_numeric($float)) {
            $float = null;
        }

        if ($float === null) {
            if ($value !== null) {
                trigger_error(sprintf('Percent length `%s` could not be converted to a float', $value), E_USER_WARNING);
            }

            return new self(null);
        }

        return new self($divideBy50 ? $float / 50 : $float);
    }
}
