<?php
declare(strict_types=1);

namespace PhpOffice\PhpWord\Style\Lengths;

use PhpOffice\PhpWord\Exception\Exception;

class Absolute extends Length
{
    private $twips = null;

    private static $conversions = array(
        'twip' => 1,

        // 20 twips in a point
        // Eop = 1/8 point
        'eop' => 2.5,

        // 20 twips in a point
        'pt' => 20,

        // 20 twips in a point
        // 2 hpt (half point) in a point
        'hpt' => 10,

        // 20 twips in a point
        // 12 points in a pica
        'pc' => 240,

        // 1440 twips in an inch
        'in' => 1440,

        // 1440 twips in an inch
        // 2.54 centimeters in an inch
        'cm' => 1440 / 2.54,

        // 1440 twips in an inch
        // 25.4 millimeters in an inch
        'mm' => 1440 / 25.4,

        // 20 twips in a point
        // 12700 emus in a point
        'emu' => 20 / 12700,
    );

    public function __construct(float $twips = null)
    {
        $this->twips = $twips;
    }

    public function isSpecified(): bool
    {
        return $this->twips !== null;
    }

    public static function from(string $unit, float $length = null): self
    {
        if ($length === null) {
            return new static(null);
        }

        return new static($length * self::getConversion($unit));
    }

    public static function fromMixed(string $unit, $value = null): self
    {
        if ($value instanceof self) {
            return clone $value;
        }

        if (is_string($value) && (preg_match('/[^\d\.\,]/', $value) == 0)) {
            $float = (float) $value;
        } else {
            $float = $value;
        }
        if (!is_numeric($float)) {
            $float = null;
        }

        if ($float === null) {
            if ($value !== null) {
                trigger_error(sprintf('Border size `%s` could not be converted to a float', $value), E_USER_WARNING);
            }

            return new self(null);
        }

        return self::from($unit, $float);
    }

    public function toInt(string $unit)
    {
        $float = $this->toFloat($unit);

        return $float === null ? null : (int) round($float);
    }

    public function toFloat(string $unit)
    {
        return $this->twips === null
            ? null
            : $this->twips / self::getConversion($unit);
    }

    public static function fromPixels(Dpi $dpi, float $pixels)
    {
        return self::from('in', $pixels / $dpi->getDpi());
    }

    public function toPixels(Dpi $dpi)
    {
        $inches = $this->toFloat('in');

        return $inches === null
            ? null
            : round($inches * $dpi->getDpi());
    }

    private static function getConversion(string $unit): float
    {
        if (!array_key_exists($unit, self::$conversions)) {
            throw new Exception(sprintf('Cannot convert from unit `%s`', $unit));
        }

        return self::$conversions[$unit];
    }
}
