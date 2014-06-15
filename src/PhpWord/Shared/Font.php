<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Shared;

/**
 * DEPRECATED: Common font functions; Use 'Converter'
 *
 * @deprecated 0.12.0
 * @codeCoverageIgnore
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
        return Converter::pointToPixel($fontSizeInPoints);
    }

    /**
     * Calculate an (approximate) pixel size, based on inch size
     *
     * @param int $sizeInInch Font size (in inch)
     * @return int Size (in pixels)
     */
    public static function inchSizeToPixels($sizeInInch = 1)
    {
        return Converter::inchToPixel($sizeInInch);
    }

    /**
     * Calculate an (approximate) pixel size, based on centimeter size
     *
     * @param int $sizeInCm Font size (in centimeters)
     * @return double Size (in pixels)
     */
    public static function centimeterSizeToPixels($sizeInCm = 1)
    {
        return Converter::cmToPixel($sizeInCm);
    }

    /**
     * Convert centimeter to twip
     *
     * @param int $sizeInCm
     * @return double
     */
    public static function centimeterSizeToTwips($sizeInCm = 1)
    {
        return Converter::cmToTwip($sizeInCm);
    }

    /**
     * Convert inch to twip
     *
     * @param int $sizeInInch
     * @return double
     */
    public static function inchSizeToTwips($sizeInInch = 1)
    {
        return Converter::inchToTwip($sizeInInch);
    }

    /**
     * Convert pixel to twip
     *
     * @param int $sizeInPixel
     * @return double
     */
    public static function pixelSizeToTwips($sizeInPixel = 1)
    {
        return Converter::pixelToTwip($sizeInPixel);
    }

    /**
     * Calculate twip based on point size, used mainly for paragraph spacing
     *
     * @param integer $sizeInPoint Size in point
     * @return integer Size (in twips)
     */
    public static function pointSizeToTwips($sizeInPoint = 1)
    {
        return Converter::pointToTwip($sizeInPoint);
    }
}
