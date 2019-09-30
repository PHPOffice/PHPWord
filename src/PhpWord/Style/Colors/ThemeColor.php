<?php
declare(strict_types=1);

namespace PhpOffice\PhpWord\Style\Colors;

use PhpOffice\PhpWord\Exception\Exception;

final class ThemeColor extends BasicColor implements NamedColorInterface
{
    /**
     * Taken from https://docs.microsoft.com/en-us/dotnet/api/documentformat.openxml.drawing.colorscheme?view=openxml-2.8.1
     * Should match list in
     * \PhpOffice\PhpWord\Style\Theme\ColorScheme
     * @todo Check if 'extLst' (Extension List) can add additional colors to this list.
     * @var array
     */
    private static $allowedColors = array(
        'dk1'      => true,
        'dk2'      => true,
        'lt1'      => true,
        'lt2'      => true,
        'accent1'  => true,
        'accent2'  => true,
        'accent3'  => true,
        'accent4'  => true,
        'accent5'  => true,
        'accent6'  => true,
        'hlink'    => true,
        'folHlink' => true,
    );

    private $name;

    public function __construct(string $name)
    {
        if (!static::isValid($name)) {
            throw new Exception(sprintf("Provided color must be a valid theme color. '%s' provided. Allowed: %s", $name, implode(', ', array_keys(self::$allowedColors))));
        }

        $this->name = $name;
    }

    public function isSpecified(): bool
    {
        return true;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public static function isValid(string $color): bool
    {
        return array_key_exists($color, self::$allowedColors);
    }
}
