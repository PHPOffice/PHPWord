<?php
declare(strict_types=1);

namespace PhpOffice\PhpWord\Style\Theme;

use PhpOffice\PhpWord\Exception\Exception;

abstract class Fonts
{
    private $latin;
    private $eastAsian;
    private $complexScript;
    private $fonts = array(
        'Jpan' => null,
        'Hang' => null,
        'Hans' => null,
        'Hant' => null,
        'Arab' => null,
        'Hebr' => null,
        'Thai' => null,
        'Ethi' => null,
        'Beng' => null,
        'Gujr' => null,
        'Khmr' => null,
        'Knda' => null,
        'Guru' => null,
        'Cans' => null,
        'Cher' => null,
        'Yiii' => null,
        'Tibt' => null,
        'Thaa' => null,
        'Deva' => null,
        'Telu' => null,
        'Taml' => null,
        'Syrc' => null,
        'Orya' => null,
        'Mlym' => null,
        'Laoo' => null,
        'Sinh' => null,
        'Mong' => null,
        'Viet' => null,
        'Uigh' => null,
    );
    protected static $defaultFonts = array();

    public function __construct(array $fonts = array())
    {
        $this->latin = $this->readFont('Latin', $fonts);
        $this->eastAsian = $this->readFont('EastAsian', $fonts);
        $this->complexScript = $this->readFont('ComplexScript', $fonts);
        foreach ($this->fonts as $script => $null) {
            $this->fonts[$script] = $this->readFont($script, $fonts);
        }

        foreach ($fonts as $script => $null) {
            if ($script === 'Latin' || $script === 'EastAsian' || $script === 'ComplexScript') {
                continue;
            }

            if (!array_key_exists($script, $this->fonts)) {
                throw new Exception(sprintf("Invalid script '%s' provided", $script));
            }
        }
    }

    protected function readFont(string $script, array $fonts): string
    {
        $font = $fonts[$script] ?? $this->getDefaultFont($script);
        if (!is_string($font)) {
            throw new Exception(sprintf("Font name expected, '%s' provided", gettype($font)));
        }

        return $font;
    }

    public function getLatin(): string
    {
        return $this->latin;
    }

    public function getEastAsian(): string
    {
        return $this->eastAsian;
    }

    public function getComplexScript(): string
    {
        return $this->complexScript;
    }

    public function getFonts(): array
    {
        return $this->fonts;
    }

    public function getFont(string $script): string
    {
        if (!array_key_exists($script, $this->fonts)) {
            throw new Exception(sprintf("No font found for script '%s' in color scheme '%s'", $script, get_class($this)));
        }

        return $this->fonts[$script];
    }

    public static function getDefaultFont(string $script): string
    {
        if (!array_key_exists($script, static::$defaultFonts)) {
            throw new Exception(sprintf("No font found for script '%s' in color scheme '%s'", $script, get_class()));
        }

        return static::$defaultFonts[$script];
    }
}
