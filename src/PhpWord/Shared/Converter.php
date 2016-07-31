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
 * @copyright   2010-2016 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Shared;

/**
 * Common converter functions
 */
class Converter
{
    const INCH_TO_CM        = 2.54;
    const INCH_TO_TWIP      = 1440;
    const INCH_TO_PIXEL     = 96;
    const INCH_TO_POINT     = 72;
    const PIXEL_TO_EMU      = 9525;
    const DEGREE_TO_ANGLE   = 60000;

    /**
     * Convert centimeter to twip
     *
     * @param int $centimeter
     * @return float
     */
    public static function cmToTwip($centimeter = 1)
    {
        return $centimeter / self::INCH_TO_CM * self::INCH_TO_TWIP;
    }

    /**
     * Convert centimeter to inch
     *
     * @param int $centimeter
     * @return float
     */
    public static function cmToInch($centimeter = 1)
    {
        return $centimeter / self::INCH_TO_CM;
    }

    /**
     * Convert centimeter to pixel
     *
     * @param int $centimeter
     * @return float
     */
    public static function cmToPixel($centimeter = 1)
    {
        return $centimeter / self::INCH_TO_CM * self::INCH_TO_PIXEL;
    }

    /**
     * Convert centimeter to point
     *
     * @param int $centimeter
     * @return float
     */
    public static function cmToPoint($centimeter = 1)
    {
        return $centimeter / self::INCH_TO_CM * self::INCH_TO_POINT;
    }

    /**
     * Convert centimeter to EMU
     *
     * @param int $centimeter
     * @return int
     */
    public static function cmToEmu($centimeter = 1)
    {
        return round($centimeter / self::INCH_TO_CM * self::INCH_TO_PIXEL * self::PIXEL_TO_EMU);
    }

    /**
     * Convert inch to twip
     *
     * @param int $inch
     * @return int
     */
    public static function inchToTwip($inch = 1)
    {
        return $inch * self::INCH_TO_TWIP;
    }

    /**
     * Convert inch to centimeter
     *
     * @param int $inch
     * @return float
     */
    public static function inchToCm($inch = 1)
    {
        return $inch * self::INCH_TO_CM;
    }

    /**
     * Convert inch to pixel
     *
     * @param int $inch
     * @return int
     */
    public static function inchToPixel($inch = 1)
    {
        return $inch * self::INCH_TO_PIXEL;
    }

    /**
     * Convert inch to point
     *
     * @param int $inch
     * @return int
     */
    public static function inchToPoint($inch = 1)
    {
        return $inch * self::INCH_TO_POINT;
    }

    /**
     * Convert inch to EMU
     *
     * @param int $inch
     * @return int
     */
    public static function inchToEmu($inch = 1)
    {
        return round($inch * self::INCH_TO_PIXEL * self::PIXEL_TO_EMU);
    }

    /**
     * Convert pixel to twip
     *
     * @param int $pixel
     * @return int
     */
    public static function pixelToTwip($pixel = 1)
    {
        return $pixel / self::INCH_TO_PIXEL * self::INCH_TO_TWIP;
    }

    /**
     * Convert pixel to centimeter
     *
     * @param int $pixel
     * @return float
     */
    public static function pixelToCm($pixel = 1)
    {
        return $pixel / self::INCH_TO_PIXEL * self::INCH_TO_CM;
    }

    /**
     * Convert pixel to point
     *
     * @param int $pixel
     * @return float
     */
    public static function pixelToPoint($pixel = 1)
    {
        return $pixel / self::INCH_TO_PIXEL * self::INCH_TO_POINT;
    }

    /**
     * Convert pixel to EMU
     *
     * @param int $pixel
     * @return int
     */
    public static function pixelToEmu($pixel = 1)
    {
        return round($pixel * self::PIXEL_TO_EMU);
    }

    /**
     * Convert point to twip unit
     *
     * @param int $point
     * @return int
     */
    public static function pointToTwip($point = 1)
    {
        return $point / self::INCH_TO_POINT * self::INCH_TO_TWIP;
    }

    /**
     * Convert point to pixel
     *
     * @param int $point
     * @return float
     */
    public static function pointToPixel($point = 1)
    {
        return $point / self::INCH_TO_POINT * self::INCH_TO_PIXEL;
    }

    /**
     * Convert point to EMU
     *
     * @param int $point
     * @return int
     */
    public static function pointToEmu($point = 1)
    {
        return round($point / self::INCH_TO_POINT * self::INCH_TO_PIXEL * self::PIXEL_TO_EMU);
    }

    /**
     * Convert EMU to pixel
     *
     * @param int $emu
     * @return int
     */
    public static function emuToPixel($emu = 1)
    {
        return round($emu / self::PIXEL_TO_EMU);
    }

    /**
     * Convert degree to angle
     *
     * @param int $degree
     * @return int
     */
    public static function degreeToAngle($degree = 1)
    {
        return (int)round($degree * self::DEGREE_TO_ANGLE);
    }

    /**
     * Convert angle to degrees
     *
     * @param int $angle
     * @return int
     */
    public static function angleToDegree($angle = 1)
    {
        return round($angle / self::DEGREE_TO_ANGLE);
    }

    /**
     * Convert HTML hexadecimal to RGB
     *
     * @param string $value HTML Color in hexadecimal
     * @return array Value in RGB
     */
    public static function htmlToRgb($value)
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
