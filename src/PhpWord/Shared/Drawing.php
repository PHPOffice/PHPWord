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
 * Common drawing functions
 */
class Drawing
{
    /**
     * Convert pixels to EMU
     *
     * @param integer $value Value in pixels
     * @return double Value in EMU
     */
    public static function pixelsToEMU($value = 0)
    {
        return round($value * 9525);
    }

    /**
     * Convert EMU to pixels
     *
     * @param integer $value Value in EMU
     * @return integer Value in pixels
     */
    public static function emuToPixels($value = 0)
    {
        if ($value != 0) {
            return round($value / 9525);
        } else {
            return 0;
        }
    }

    /**
     * Convert pixels to points
     *
     * @param integer $value Value in pixels
     * @return double Value in points
     */
    public static function pixelsToPoints($value = 0)
    {
        return $value * 0.75;
    }

    /**
     * Convert points width to pixels
     *
     * @param integer $value Value in points
     * @return integer Value in pixels
     */
    public static function pointsToPixels($value = 0)
    {
        if ($value != 0) {
            return $value * 1.333333333;
        } else {
            return 0;
        }
    }

    /**
     * Convert degrees to angle
     *
     * @param integer $value Degrees
     * @return integer Angle
     */
    public static function degreesToAngle($value = 0)
    {
        return (integer)round($value * 60000);
    }

    /**
     * Convert angle to degrees
     *
     * @param integer $value Angle
     * @return integer Degrees
     */
    public static function angleToDegrees($value = 0)
    {
        if ($value != 0) {
            return round($value / 60000);
        } else {
            return 0;
        }
    }

    /**
     * Convert pixels to centimeters
     *
     * @param integer $value Value in pixels
     * @return double Value in centimeters
     */
    public static function pixelsToCentimeters($value = 0)
    {
        return $value * 0.026458333;
    }

    /**
     * Convert centimeters width to pixels
     *
     * @param integer $value Value in centimeters
     * @return integer Value in pixels
     */
    public static function centimetersToPixels($value = 0)
    {
        if ($value != 0) {
            return $value / 0.026458333;
        } else {
            return 0;
        }
    }

    /**
     * Convert HTML hexadecimal to RGB
     *
     * @param string $value HTML Color in hexadecimal
     * @return array Value in RGB
     */
    public static function htmlToRGB($value)
    {
        if ($value[0] == '#') {
            $value = substr($value, 1);
        }

        if (strlen($value) == 6) {
            list($red, $green, $blue) = array($value[0] . $value[1], $value[2] . $value[3], $value[4] . $value[5]);
        } elseif (strlen($value) == 3) {
            list($red, $green, $blue) = array($value[0] . $value[0], $value[1] . $value[1], $value[2] . $value[2]);
        } else {
            return false;
        }

        $red = hexdec($red);
        $green = hexdec($green);
        $blue = hexdec($blue);

        return array($red, $green, $blue);
    }
}
