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
 * Common font functions
 */
class Font
{
    /**
     * Calculate an (approximate) pixel size, based on a font points size
     *
     * @param int $fontSizeInPoints Font size (in points)
     * @return int Font size (in pixels)
     */
    public static function fontSizeToPixels($fontSizeInPoints = 12)
    {
        return ((16 / 12) * $fontSizeInPoints);
    }

    /**
     * Calculate an (approximate) pixel size, based on inch size
     *
     * @param int $sizeInInch Font size (in inch)
     * @return int Size (in pixels)
     */
    public static function inchSizeToPixels($sizeInInch = 1)
    {
        return ($sizeInInch * 96);
    }

    /**
     * Calculate an (approximate) pixel size, based on centimeter size
     *
     * @param int $sizeInCm Font size (in centimeters)
     * @return double Size (in pixels)
     */
    public static function centimeterSizeToPixels($sizeInCm = 1)
    {
        return ($sizeInCm * 37.795275591);
    }

    /**
     * Convert centimeter to twip
     *
     * @param int $sizeInCm
     * @return double
     */
    public static function centimeterSizeToTwips($sizeInCm = 1)
    {
        return ($sizeInCm * 565.217);
    }

    /**
     * Convert inch to twip
     *
     * @param int $sizeInInch
     * @return double
     */
    public static function inchSizeToTwips($sizeInInch = 1)
    {
        return self::centimeterSizeToTwips($sizeInInch * 2.54);
    }

    /**
     * Convert pixel to twip
     *
     * @param int $sizeInPixel
     * @return double
     */
    public static function pixelSizeToTwips($sizeInPixel = 1)
    {
        return self::centimeterSizeToTwips($sizeInPixel / 37.795275591);
    }

    /**
     * Calculate twip based on point size, used mainly for paragraph spacing
     *
     * @param integer $sizeInPoint Size in point
     * @return integer Size (in twips)
     */
    public static function pointSizeToTwips($sizeInPoint = 1)
    {
        return ($sizeInPoint * 20);
    }
}
