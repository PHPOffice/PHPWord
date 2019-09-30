<?php
declare(strict_types=1);

namespace PhpOffice\PhpWord\Style\Colors;

use PhpOffice\PhpWord\Exception\Exception;

final class SystemColor extends SpecialColor implements NamedColorInterface
{
    /**
     * Taken from http://www.datypic.com/sc/ooxml/t-a_ST_SystemColorVal.html
     * @var array
     */
    private static $allowedColors = array(
        'scrollBar'               => true,
        'background'              => true,
        'activeCaption'           => true,
        'inactiveCaption'         => true,
        'menu'                    => true,
        'window'                  => true,
        'windowFrame'             => true,
        'menuText'                => true,
        'windowText'              => true,
        'captionText'             => true,
        'activeBorder'            => true,
        'inactiveBorder'          => true,
        'appWorkspace'            => true,
        'highlight'               => true,
        'highlightText'           => true,
        'btnFace'                 => true,
        'btnShadow'               => true,
        'grayText'                => true,
        'btnText'                 => true,
        'inactiveCaptionText'     => true,
        'btnHighlight'            => true,
        '3dDkShadow'              => true,
        '3dLight'                 => true,
        'infoText'                => true,
        'infoBk'                  => true,
        'hotLight'                => true,
        'gradientActiveCaption'   => true,
        'gradientInactiveCaption' => true,
        'menuHighlight'           => true,
        'menuBar'                 => true,
    );

    private $name;
    private $lastColor;

    public function __construct(string $name, StaticColorInterface $lastColor)
    {
        if (!static::isValid($name)) {
            throw new Exception(sprintf("Provided system color must be a valid system color. '%s' provided. Allowed: %s", $name, implode(', ', array_keys(self::$allowedColors))));
        }

        $this->name = $name;
        $this->lastColor = clone $lastColor;
    }

    public function isSpecified(): bool
    {
        return true;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLastColor(): StaticColorInterface
    {
        return clone $this->lastColor;
    }

    public static function isValid(string $color): bool
    {
        return array_key_exists($color, self::$allowedColors);
    }
}
