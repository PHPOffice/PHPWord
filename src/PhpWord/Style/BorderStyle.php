<?php
declare(strict_types=1);

namespace PhpOffice\PhpWord\Style;

use PhpOffice\PhpWord\Exception\Exception;

class BorderStyle
{
    /**
     * Taken from http://officeopenxml.com/WPtableCellProperties-Borders.php
     * @var array
     */
    private static $allowedStyles = array(
        'single'                 => true,
        'dashDotStroked'         => true,
        'dashed'                 => true,
        'dashSmallGap'           => true,
        'dotDash'                => true,
        'dotDotDash'             => true,
        'dotted'                 => true,
        'double'                 => true,
        'doubleWave'             => true,
        'inset'                  => true,
        'nil'                    => true,
        'none'                   => true,
        'outset'                 => true,
        'thick'                  => true,
        'thickThinLargeGap'      => true,
        'thickThinMediumGap'     => true,
        'thickThinSmallGap'      => true,
        'thinThickLargeGap'      => true,
        'thinThickMediumGap'     => true,
        'thinThickSmallGap'      => true,
        'thinThickThinLargeGap'  => true,
        'thinThickThinMediumGap' => true,
        'thinThickThinSmallGap'  => true,
        'threeDEmboss'           => true,
        'threeDEngrave'          => true,
        'triple'                 => true,
        'wave'                   => true,
    );

    private $style;

    public function __construct(string $style)
    {
        if (!static::isValid($style)) {
            throw new Exception(sprintf("Provided border style must be valid. '%s' provided. Allowed: '%s'", $style, implode('\', \'', array_keys(self::$allowedStyles))));
        }

        $this->style = $style;
    }

    public function getStyle()
    {
        return $this->style;
    }

    public static function isValid(string $style): bool
    {
        return array_key_exists($style, self::$allowedStyles);
    }

    public static function fromMixed($style = null): self
    {
        if (!static::isValid($style)) {
            trigger_error(sprintf('Border style `%s` is not a valid option', $style), E_USER_WARNING);

            return new self('single');
        }

        return new self($style);
    }
}
