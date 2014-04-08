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
 * Style collection
 */
class Style
{
    /**
     * Style register
     *
     * @var array
     */
    private static $styles = array();

    /**
     * Add paragraph style
     *
     * @param string $styleName
     * @param array $styles
     */
    public static function addParagraphStyle($styleName, $styles)
    {
        self::setStyleValues($styleName, $styles, new Paragraph());
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
        self::setStyleValues($styleName, $styleFont, new Font('text', $styleParagraph));
    }

    /**
     * Add link style
     *
     * @param string $styleName
     * @param array $styles
     */
    public static function addLinkStyle($styleName, $styles)
    {
        self::setStyleValues($styleName, $styles, new Font('link'));
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
        if (!array_key_exists($styleName, self::$styles)) {
            $style = new Table($styleTable, $styleFirstRow);

            self::$styles[$styleName] = $style;
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
        self::setStyleValues("Heading_{$titleCount}", $styleFont, new Font('title', $styleParagraph));
    }

    /**
     * Reset styles
     */
    public static function resetStyles()
    {
        self::$styles = array();
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
     * @return Font[]
     */
    public static function getStyles()
    {
        return self::$styles;
    }

    /**
     * Get style by name
     *
     * @param string $styleName
     */
    public static function getStyle($styleName)
    {
        if (array_key_exists($styleName, self::$styles)) {
            return self::$styles[$styleName];
        } else {
            return null;
        }
    }

    /**
     * Set style values
     *
     * @param string $styleName
     * @param array $styleValues
     * @param mixed $styleObject
     */
    private static function setStyleValues($styleName, $styleValues, $styleObject)
    {
        if (!array_key_exists($styleName, self::$styles)) {
            if (is_array($styleValues)) {
                foreach ($styleValues as $key => $value) {
                    if (substr($key, 0, 1) == '_') {
                        $key = substr($key, 1);
                    }
                    $styleObject->setStyleValue($key, $value);
                }
            }

            self::$styles[$styleName] = $styleObject;
        }
    }
}
