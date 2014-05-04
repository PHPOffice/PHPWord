<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Shared;

/**
 * Common string functions
 */
class String
{
    /**
     * Control characters array
     *
     * @var string[]
     */
    private static $controlCharacters = array();

    /**
     * Convert from OpenXML escaped control character to PHP control character
     *
     * @param string $value Value to unescape
     * @return string
     */
    public static function controlCharacterOOXML2PHP($value = '')
    {
        if (empty(self::$controlCharacters)) {
            self::buildControlCharacters();
        }

        return str_replace(array_keys(self::$controlCharacters), array_values(self::$controlCharacters), $value);
    }

    /**
     * Convert from PHP control character to OpenXML escaped control character
     *
     * @param string $value Value to escape
     * @return string
     */
    public static function controlCharacterPHP2OOXML($value = '')
    {
        if (empty(self::$controlCharacters)) {
            self::buildControlCharacters();
        }

        return str_replace(array_values(self::$controlCharacters), array_keys(self::$controlCharacters), $value);
    }

    /**
     * Check if a string contains UTF-8 data
     *
     * @param string $value
     * @return boolean
     */
    public static function isUTF8($value = '')
    {
        return $value === '' || preg_match('/^./su', $value) === 1;
    }

    /**
     * Return UTF8 encoded value
     *
     * @param string $value
     * @return string
     */
    public static function toUTF8($value = '')
    {
        if (!is_null($value) && !self::isUTF8($value)) {
            $value = utf8_encode($value);
        }

        return $value;
    }

    /**
     * Return name without underscore for < 0.10.0 variable name compatibility
     *
     * @param string $value
     * @return string
     */
    public static function removeUnderscorePrefix($value)
    {
        if (!is_null($value)) {
            if (substr($value, 0, 1) == '_') {
                $value = substr($value, 1);
            }
        }

        return $value;
    }

    /**
     * Build control characters array
     */
    private static function buildControlCharacters()
    {
        for ($i = 0; $i <= 19; ++$i) {
            if ($i != 9 && $i != 10 && $i != 13) {
                $find = '_x' . sprintf('%04s', strtoupper(dechex($i))) . '_';
                $replace = chr($i);
                self::$controlCharacters[$find] = $replace;
            }
        }
    }
}
