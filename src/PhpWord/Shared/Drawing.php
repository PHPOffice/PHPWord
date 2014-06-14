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
 * Common drawing functions; replaced by `Converter`
 *
 * @deprecated 0.12.0
 * @codeCoverageIgnore
 */
class Drawing extends Converter
{
    /**
     * Convert pixels to EMU
     *
     * @param integer $value Value in pixels
     * @return double Value in EMU
     */
    public static function pixelsToEMU($value = 0)
    {
        return self::pixelToEmu($value);
    }

    /**
     * Convert EMU to pixels
     *
     * @param integer $value Value in EMU
     * @return integer Value in pixels
     */
    public static function emuToPixels($value = 0)
    {
        return self::emuToPixel($value);
    }

    /**
     * Convert pixels to points
     *
     * @param integer $value Value in pixels
     * @return double Value in points
     */
    public static function pixelsToPoints($value = 0)
    {
        return self::pixelToPoint($value);
    }

    /**
     * Convert points width to pixels
     *
     * @param integer $value Value in points
     * @return integer Value in pixels
     */
    public static function pointsToPixels($value = 0)
    {
        return self::pointToPixel($value);
    }

    /**
     * Convert degrees to angle
     *
     * @param integer $value Degrees
     * @return integer Angle
     */
    public static function degreesToAngle($value = 0)
    {
        return self::degreeToAngle($value);
    }

    /**
     * Convert angle to degrees
     *
     * @param integer $value Angle
     * @return integer Degrees
     */
    public static function angleToDegrees($value = 0)
    {
        return self::angleToDegree($value);
    }

    /**
     * Convert pixels to centimeters
     *
     * @param integer $value Value in pixels
     * @return double Value in centimeters
     */
    public static function pixelsToCentimeters($value = 0)
    {
        return self::pixelToCm($value);
    }

    /**
     * Convert centimeters width to pixels
     *
     * @param integer $value Value in centimeters
     * @return integer Value in pixels
     */
    public static function centimetersToPixels($value = 0)
    {
        return self::cmToPixel($value);
    }
}
