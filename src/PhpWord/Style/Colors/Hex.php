<?php
declare(strict_types=1);

namespace PhpOffice\PhpWord\Style\Colors;

use PhpOffice\PhpWord\Exception\Exception;

class Hex extends BasicColor implements StaticColorInterface
{
    private $hex;

    public function __construct(string $hex = null)
    {
        if ($hex === null) {
            $this->hex = null;

            return;
        }

        $hex = strtoupper($hex);

        if (!static::isValid($hex)) {
            throw new Exception(sprintf('Hex value must match `([0-9a-f]{3}){1,2}`. `%s` provided', $hex));
        }

        if (strlen($hex) === 3) {
            // If #abc format is provided, expand to #aabbcc
            $this->hex = '';
            foreach (str_split($hex) as $ch) {
                $this->hex .= strtoupper($ch . $ch);
            }
        } else {
            $this->hex = strtoupper($hex);
        }
    }

    public function isSpecified(): bool
    {
        return $this->hex !== null;
    }

    public function toRgb()
    {
        if ($this->hex === null) {
            return null;
        }

        $rgb = array();
        foreach (str_split($this->hex, 2) as $c) {
            $rgb[] = hexdec($c);
        }

        return $rgb;
    }

    public function toHex(bool $includeHash = false)
    {
        if ($this->hex === null) {
            return null;
        }

        return ($includeHash ? '#' : '') . $this->hex;
    }

    public static function isValid(string $hex): bool
    {
        return preg_match('/^(?:[0-9a-fA-F]{3}){1,2}$/', $hex) ? true : false;
    }
}
