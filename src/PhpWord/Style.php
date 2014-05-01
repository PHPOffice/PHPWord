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
use PhpOffice\PhpWord\Style\Numbering;

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
        self::setStyleValues($styleName, new Paragraph(), $styles);
    }

    /**
     * Add font style
     *
     * @param string $styleName
     * @param array $fontStyle
     * @param array $paragraphStyle
     */
    public static function addFontStyle($styleName, $fontStyle, $paragraphStyle = null)
    {
        self::setStyleValues($styleName, new Font('text', $paragraphStyle), $fontStyle);
    }

    /**
     * Add link style
     *
     * @param string $styleName
     * @param array $styles
     */
    public static function addLinkStyle($styleName, $styles)
    {
        self::setStyleValues($styleName, new Font('link'), $styles);
    }

    /**
     * Add table style
     *
     * @param string $styleName
     * @param array $styleTable
     * @param array|null $styleFirstRow
     */
    public static function addTableStyle($styleName, $styleTable, $styleFirstRow = null)
    {
        self::setStyleValues($styleName, new Table($styleTable, $styleFirstRow), null);
    }

    /**
     * Add title style
     *
     * @param int $titleCount
     * @param array $fontStyle
     * @param array $paragraphStyle
     */
    public static function addTitleStyle($titleCount, $fontStyle, $paragraphStyle = null)
    {
        self::setStyleValues("Heading_{$titleCount}", new Font('title', $paragraphStyle), $fontStyle);
    }

    /**
     * Add numbering style
     *
     * @param string $styleName
     * @param array $styleValues
     * @return Numbering
     * @since 0.10.0
     */
    public static function addNumberingStyle($styleName, $styleValues)
    {
        self::setStyleValues($styleName, new Numbering(), $styleValues);
    }

    /**
     * Count styles
     *
     * @return integer
     * @since 0.10.0
     */
    public static function countStyles()
    {
        return count(self::$styles);
    }

    /**
     * Reset styles
     * @since 0.10.0
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
     * @return array
     */
    public static function getStyles()
    {
        return self::$styles;
    }

    /**
     * Get style by name
     *
     * @param string $styleName
     * @return Paragraph|Font|Table|Numbering|null
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
     * Set style values and put it to static style collection
     *
     * @param string $styleName
     * @param Paragraph|Font|Table|Numbering $styleObject
     * @param array|null $styleValues
     */
    private static function setStyleValues($styleName, $styleObject, $styleValues = null)
    {
        if (!array_key_exists($styleName, self::$styles)) {
            if (!is_null($styleValues) && is_array($styleValues)) {
                foreach ($styleValues as $key => $value) {
                    $styleObject->setStyleValue($key, $value);
                }
            }
            $styleObject->setStyleName($styleName);
            $styleObject->setIndex(self::countStyles() + 1); // One based index
            self::$styles[$styleName] = $styleObject;
        }
    }
}
