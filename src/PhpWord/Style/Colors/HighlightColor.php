<?php
declare(strict_types=1);

namespace PhpOffice\PhpWord\Style\Colors;

use PhpOffice\PhpWord\Exception\Exception;

final class HighlightColor extends BasicColor implements StaticColorInterface, NamedColorInterface
{
    private static $allowedColors = array(
        'yellow'      => 'FF0000',
        'green'       => '00FF00',
        'cyan'        => '00FFFF',
        'magenta'     => 'FF00FF',
        'blue'        => '0000FF',
        'red'         => 'FF0000',
        'darkBlue'    => '000080',
        'darkCyan'    => '008080',
        'darkGreen'   => '008000',
        'darkMagenta' => '800080',
        'darkRed'     => '800000',
        'darkYellow'  => '808000',
        'darkGray'    => '808080',
        'lightGray'   => 'C0C0C0',
        'black'       => '000000',
    );

    private $color;

    public function __construct(string $color = null)
    {
        if ($color !== null) {
            if (!static::isValid($color)) {
                throw new Exception(sprintf("Provided color must be a valid highlight color. '%s' provided. Allowed: %s", $color, implode(', ', array_keys(self::$allowedColors))));
            }
        }

        $this->color = $color;
    }

    public function isSpecified(): bool
    {
        return $this->color !== null;
    }

    public function getName()
    {
        return $this->color;
    }

    public function toRgb()
    {
        if ($this->color === null) {
            return null;
        }

        $rgb = array();
        foreach (str_split(self::$allowedColors[$this->color], 2) as $c) {
            $rgb[] = hexdec($c);
        }

        return $rgb;
    }

    public function toHex(bool $includeHash = false)
    {
        if ($this->color === null) {
            return null;
        }

        return ($includeHash ? '#' : '') . self::$allowedColors[$this->color];
    }

    public static function isValid(string $color): bool
    {
        return array_key_exists($color, self::$allowedColors);
    }
}
