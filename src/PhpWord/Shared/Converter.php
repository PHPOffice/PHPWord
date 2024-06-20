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
 * @see         https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2018 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Shared;

/**
 * Common converter functions.
 */
class Converter
{
    const INCH_TO_CM = 2.54;
    const INCH_TO_TWIP = 1440;
    const INCH_TO_PIXEL = 96;
    const INCH_TO_POINT = 72;
    const INCH_TO_PICA = 6;
    const TWIP_TO_INCH = 0.0006944444;
    const PIXEL_TO_EMU = 9525;
    const DEGREE_TO_ANGLE = 60000;

    /**
     * Convert centimeter to twip.
     *
     * @param float $centimeter
     *
     * @return float
     */
    public static function cmToTwip($centimeter = 1)
    {
        return (float)bcmul(bcdiv($centimeter, self::INCH_TO_CM, 10), self::INCH_TO_TWIP, 10);
    }

    /**
     * Convert centimeter to inch.
     *
     * @param float $centimeter
     *
     * @return float
     */
    public static function cmToInch($centimeter = 1)
    {
        return (float)bcdiv($centimeter, self::INCH_TO_CM, 10);
    }

    /**
     * Convert centimeter to pixel.
     *
     * @param float $centimeter
     *
     * @return float
     */
    public static function cmToPixel($centimeter = 1)
    {
        return (float)bcmul(bcdiv($centimeter, self::INCH_TO_CM, 10), self::INCH_TO_PIXEL, 10);
    }

    /**
     * Convert centimeter to point.
     *
     * @param float $centimeter
     *
     * @return float
     */
    public static function cmToPoint($centimeter = 1)
    {
        return (float)bcmul(bcdiv($centimeter, self::INCH_TO_CM, 10), self::INCH_TO_POINT, 10);
    }

    /**
     * Convert centimeter to EMU.
     *
     * @param float $centimeter
     *
     * @return float
     */
    public static function cmToEmu($centimeter = 1)
    {
        return round((float)bcmul(bcmul(
            bcdiv($centimeter, self::INCH_TO_CM, 10),
            self::INCH_TO_PIXEL,
            10
        ), self::PIXEL_TO_EMU, 10));
    }

    /**
     * Convert inch to twip.
     *
     * @param float $inch
     *
     * @return float
     */
    public static function inchToTwip($inch = 1)
    {
        return (float)bcmul($inch, self::INCH_TO_TWIP, 10);
    }

    /**
     * Convert inch to centimeter.
     *
     * @param float $inch
     *
     * @return float
     */
    public static function inchToCm($inch = 1)
    {
        return (float)bcmul($inch, self::INCH_TO_CM, 10);
    }

    /**
     * Convert inch to pixel.
     *
     * @param float $inch
     *
     * @return float
     */
    public static function inchToPixel($inch = 1)
    {
        return (float)bcmul($inch, self::INCH_TO_PIXEL, 10);
    }

    /**
     * Convert inch to point.
     *
     * @param float $inch
     *
     * @return float
     */
    public static function inchToPoint($inch = 1)
    {
        return (float)bcmul($inch, self::INCH_TO_POINT, 10);
    }

    /**
     * Convert inch to EMU.
     *
     * @param float $inch
     *
     * @return int
     */
    public static function inchToEmu($inch = 1)
    {
        return (int)bcmul(bcmul($inch, self::INCH_TO_PIXEL, 10), self::PIXEL_TO_EMU, 10);
    }

    /**
     * Convert pixel to twip.
     *
     * @param float $pixel
     *
     * @return float
     */
    public static function pixelToTwip($pixel = 1)
    {
        return (float)bcmul(bcdiv($pixel, self::INCH_TO_PIXEL, 10), self::INCH_TO_TWIP, 10);
    }

    /**
     * Convert pixel to centimeter.
     *
     * @param float $pixel
     *
     * @return float
     */
    public static function pixelToCm($pixel = 1)
    {
        return (float)bcmul(bcdiv($pixel, self::INCH_TO_PIXEL, 10), self::INCH_TO_CM, 10);
    }

    /**
     * Convert pixel to point.
     *
     * @param float $pixel
     *
     * @return float
     */
    public static function pixelToPoint($pixel = 1)
    {
        return (float)bcmul(bcdiv($pixel, self::INCH_TO_PIXEL, 10), self::INCH_TO_POINT, 10);
    }

    /**
     * Convert pixel to EMU.
     *
     * @param float $pixel
     *
     * @return int
     */
    public static function pixelToEmu($pixel = 1)
    {
        return (int)bcmul($pixel, self::PIXEL_TO_EMU, 10);
    }

    /**
     * Convert point to twip unit.
     *
     * @param float $point
     *
     * @return float
     */
    public static function pointToTwip($point = 1)
    {
        return (float)bcmul(bcdiv($point, self::INCH_TO_POINT, 10), self::INCH_TO_TWIP, 10);
    }

    /**
     * Convert point to pixel.
     *
     * @param float $point
     *
     * @return float
     */
    public static function pointToPixel($point = 1)
    {
        return (float)bcmul(bcdiv($point, self::INCH_TO_POINT, 10), self::INCH_TO_PIXEL, 10);
    }

    /**
     * Convert point to EMU.
     *
     * @param float $point
     *
     * @return float
     */
    public static function pointToEmu($point = 1)
    {
        return (float)bcmul(bcdiv($point, self::INCH_TO_POINT, 10), self::PIXEL_TO_EMU, 10);
    }

    /**
     * Convert point to cm.
     *
     * @param float $point
     *
     * @return float
     */
    public static function pointToCm($point = 1)
    {
        return (float)bcmul(bcdiv($point, self::INCH_TO_POINT, 10), self::INCH_TO_CM, 10);
    }

    /**
     * Convert EMU to pixel.
     *
     * @param float $emu
     *
     * @return float
     */
    public static function emuToPixel($emu = 1)
    {
        return (float)bcdiv($emu, self::PIXEL_TO_EMU, 10);
    }

    /**
     * Convert pica to point.
     *
     * @param float $pica
     *
     * @return float
     */
    public static function picaToPoint($pica = 1)
    {
        return (float)bcmul(bcdiv($pica, self::INCH_TO_PICA, 10), self::INCH_TO_POINT, 10);
    }

    /**
     * Convert twip to inch.
     *
     * @param float $twip
     * @return float
     * @since 0.19.0
     */
    public static function twipToInch($twip = 1)
    {
        return (float)bcmul($twip, self::TWIP_TO_INCH, 10);
    }

    /**
     * Convert degree to angle.
     *
     * @param float $degree
     *
     * @return int
     */
    public static function degreeToAngle($degree = 1)
    {
        return (int)round(bcmul($degree, self::DEGREE_TO_ANGLE, 10));
    }

    /**
     * Convert angle to degrees.
     *
     * @param float $angle
     *
     * @return int
     */
    public static function angleToDegree($angle = 1)
    {
        return (int)round(bcdiv($angle, self::DEGREE_TO_ANGLE, 10));
    }

    /**
     * Convert colorname as string to RGB.
     *
     * @param string $value color name
     *
     * @return string color as hex RGB string, or original value if unknown
     */
    public static function stringToRgb($value)
    {
        switch ($value) {
            case \PhpOffice\PhpWord\Style\Font::FGCOLOR_YELLOW:
                return 'FFFF00';
            case \PhpOffice\PhpWord\Style\Font::FGCOLOR_LIGHTGREEN:
                return '90EE90';
            case \PhpOffice\PhpWord\Style\Font::FGCOLOR_CYAN:
                return '00FFFF';
            case \PhpOffice\PhpWord\Style\Font::FGCOLOR_MAGENTA:
                return 'FF00FF';
            case \PhpOffice\PhpWord\Style\Font::FGCOLOR_BLUE:
                return '0000FF';
            case \PhpOffice\PhpWord\Style\Font::FGCOLOR_RED:
                return 'FF0000';
            case \PhpOffice\PhpWord\Style\Font::FGCOLOR_DARKBLUE:
                return '00008B';
            case \PhpOffice\PhpWord\Style\Font::FGCOLOR_DARKCYAN:
                return '008B8B';
            case \PhpOffice\PhpWord\Style\Font::FGCOLOR_DARKGREEN:
                return '006400';
            case \PhpOffice\PhpWord\Style\Font::FGCOLOR_DARKMAGENTA:
                return '8B008B';
            case \PhpOffice\PhpWord\Style\Font::FGCOLOR_DARKRED:
                return '8B0000';
            case \PhpOffice\PhpWord\Style\Font::FGCOLOR_DARKYELLOW:
                return '8B8B00';
            case \PhpOffice\PhpWord\Style\Font::FGCOLOR_DARKGRAY:
                return 'A9A9A9';
            case \PhpOffice\PhpWord\Style\Font::FGCOLOR_LIGHTGRAY:
                return 'D3D3D3';
            case \PhpOffice\PhpWord\Style\Font::FGCOLOR_BLACK:
                return '000000';
        }

        return $value;
    }

    /**
     * Convert HTML hexadecimal to RGB.
     *
     * @param string $value HTML Color in hexadecimal
     *
     * @return array|false Value in RGB
     */
    public static function htmlToRgb($value)
    {
        if ($value[0] == '#') {
            $value = substr($value, 1);
        } else {
            $value = self::stringToRgb($value);
        }

        if (strlen($value) == 6) {
            [$red, $green, $blue] = [$value[0] . $value[1], $value[2] . $value[3], $value[4] . $value[5]];
        } elseif (strlen($value) == 3) {
            [$red, $green, $blue] = [$value[0] . $value[0], $value[1] . $value[1], $value[2] . $value[2]];
        } else {
            return false;
        }

        $red = ctype_xdigit($red) ? hexdec($red) : 0;
        $green = ctype_xdigit($green) ? hexdec($green) : 0;
        $blue = ctype_xdigit($blue) ? hexdec($blue) : 0;

        return [$red, $green, $blue];
    }

    /**
     * Transforms a size in CSS format (eg. 10px, 10px, ...) to points.
     *
     * @param string $value
     *
     * @return ?float
     */
    public static function cssToPoint($value)
    {
        if ($value == '0') {
            return 0;
        }
        $matches = [];
        if (preg_match('/^[+-]?([0-9]+\.?[0-9]*)?(px|em|ex|%|in|cm|mm|pt|pc)$/i', $value, $matches)) {
            $size = $matches[1];
            $unit = $matches[2];

            switch ($unit) {
                case 'pt':
                    return $size;
                case 'px':
                    return self::pixelToPoint($size);
                case 'cm':
                    return self::cmToPoint($size);
                case 'mm':
                    return self::cmToPoint($size / 10);
                case 'in':
                    return self::inchToPoint($size);
                case 'pc':
                    return self::picaToPoint($size);
            }
        }

        return null;
    }

    /**
     * Transforms a size in CSS format (eg. 10px, 10px, ...) to twips.
     *
     * @param string $value
     *
     * @return float
     */
    public static function cssToTwip($value)
    {
        return self::pointToTwip(self::cssToPoint($value));
    }

    /**
     * Transforms a size in CSS format (eg. 10px, 10px, ...) to pixel.
     *
     * @param string $value
     *
     * @return float
     */
    public static function cssToPixel($value)
    {
        return self::pointToPixel(self::cssToPoint($value));
    }

    /**
     * Transforms a size in CSS format (eg. 10px, 10px, ...) to cm.
     *
     * @param string $value
     *
     * @return float
     */
    public static function cssToCm($value)
    {
        return self::pointToCm(self::cssToPoint($value));
    }

    /**
     * Transforms a size in CSS format (eg. 10px, 10px, ...) to emu.
     *
     * @param string $value
     *
     * @return float
     */
    public static function cssToEmu($value)
    {
        return self::pointToEmu(self::cssToPoint($value));
    }
}
