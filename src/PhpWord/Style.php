<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord;

use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Style\Paragraph;
use PhpOffice\PhpWord\Style\Table;

/**
 * Style
 */
class Style
{
    /**
     * Style register
     *
     * @var array
     */
    private static $_styleElements = array();

    /**
     * Add paragraph style
     *
     * @param string $styleName
     * @param array $styles
     */
    public static function addParagraphStyle($styleName, $styles)
    {
        if (!array_key_exists($styleName, self::$_styleElements)) {
            $style = new Paragraph();
            foreach ($styles as $key => $value) {
                if (substr($key, 0, 1) != '_') {
                    $key = '_' . $key;
                }
                $style->setStyleValue($key, $value);
            }

            self::$_styleElements[$styleName] = $style;
        }
    }

    /**
     * Add font style
     *
     * @param string $styleName
     * @param array $styleFont
     * @param array $styleParagraph
     */
    public static function addFontStyle($styleName, $styleFont, $styleParagraph = null)
    {
        if (!array_key_exists($styleName, self::$_styleElements)) {
            $font = new Font('text', $styleParagraph);
            foreach ($styleFont as $key => $value) {
                if (substr($key, 0, 1) != '_') {
                    $key = '_' . $key;
                }
                $font->setStyleValue($key, $value);
            }
            self::$_styleElements[$styleName] = $font;
        }
    }

    /**
     * Add link style
     *
     * @param string $styleName
     * @param array $styles
     */
    public static function addLinkStyle($styleName, $styles)
    {
        if (!array_key_exists($styleName, self::$_styleElements)) {
            $style = new Font('link');
            foreach ($styles as $key => $value) {
                if (substr($key, 0, 1) != '_') {
                    $key = '_' . $key;
                }
                $style->setStyleValue($key, $value);
            }

            self::$_styleElements[$styleName] = $style;
        }
    }

    /**
     * Add table style
     *
     * @param string $styleName
     * @param array $styleTable
     * @param array $styleFirstRow
     */
    public static function addTableStyle($styleName, $styleTable, $styleFirstRow = null)
    {
        if (!array_key_exists($styleName, self::$_styleElements)) {
            $style = new Table($styleTable, $styleFirstRow);

            self::$_styleElements[$styleName] = $style;
        }
    }

    /**
     * Add title style
     *
     * @param int $titleCount
     * @param array $styleFont
     * @param array $styleParagraph
     */
    public static function addTitleStyle($titleCount, $styleFont, $styleParagraph = null)
    {
        $styleName = 'Heading_' . $titleCount;
        if (!array_key_exists($styleName, self::$_styleElements)) {
            $font = new Font('title', $styleParagraph);
            foreach ($styleFont as $key => $value) {
                if (substr($key, 0, 1) != '_') {
                    $key = '_' . $key;
                }
                $font->setStyleValue($key, $value);
            }

            self::$_styleElements[$styleName] = $font;
        }
    }

    /**
     * Set default paragraph style
     *
     * @param array $styles Paragraph style definition
     */
    public static function setDefaultParagraphStyle($styles)
    {
        self::addParagraphStyle('Normal', $styles);
    }

    /**
     * Get all styles
     *
     * @return \PhpOffice\PhpWord\Style\Font[]
     */
    public static function getStyles()
    {
        return self::$_styleElements;
    }

    /**
     * Get style by name
     *
     * @param string $styleName
     */
    public static function getStyle($styleName)
    {
        if (array_key_exists($styleName, self::$_styleElements)) {
            return self::$_styleElements[$styleName];
        } else {
            return null;
        }
    }
}
