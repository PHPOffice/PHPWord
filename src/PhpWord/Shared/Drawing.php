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
 * Common drawing functions
 */
class Drawing
{
    /**
     * Convert pixels to EMU
     *
     * @param integer $pValue Value in pixels
     * @return double Value in EMU
     */
    public static function pixelsToEMU($pValue = 0)
    {
        return round($pValue * 9525);
    }

    /**
     * Convert EMU to pixels
     *
     * @param integer $pValue Value in EMU
     * @return integer Value in pixels
     */
    public static function emuToPixels($pValue = 0)
    {
        if ($pValue != 0) {
            return round($pValue / 9525);
        } else {
            return 0;
        }
    }

    /**
     * Convert pixels to points
     *
     * @param integer $pValue Value in pixels
     * @return double Value in points
     */
    public static function pixelsToPoints($pValue = 0)
    {
        return $pValue * 0.67777777;
    }

    /**
     * Convert points width to pixels
     *
     * @param integer $pValue Value in points
     * @return integer Value in pixels
     */
    public static function pointsToPixels($pValue = 0)
    {
        if ($pValue != 0) {
            return $pValue * 1.333333333;
        } else {
            return 0;
        }
    }

    /**
     * Convert degrees to angle
     *
     * @param integer $pValue Degrees
     * @return integer Angle
     */
    public static function degreesToAngle($pValue = 0)
    {
        return (integer)round($pValue * 60000);
    }

    /**
     * Convert angle to degrees
     *
     * @param integer $pValue Angle
     * @return integer Degrees
     */
    public static function angleToDegrees($pValue = 0)
    {
        if ($pValue != 0) {
            return round($pValue / 60000);
        } else {
            return 0;
        }
    }

    /**
     * Convert pixels to centimeters
     *
     * @param integer $pValue Value in pixels
     * @return double Value in centimeters
     */
    public static function pixelsToCentimeters($pValue = 0)
    {
        return $pValue * 0.028;
    }

    /**
     * Convert centimeters width to pixels
     *
     * @param integer $pValue Value in centimeters
     * @return integer Value in pixels
     */
    public static function centimetersToPixels($pValue = 0)
    {
        if ($pValue != 0) {
            return $pValue / 0.028;
        } else {
            return 0;
        }
    }

    /**
     * Convert centimeters width to twips
     *
     * @param integer $pValue
     */
    public static function centimetersToTwips($pValue = 0)
    {
        if ($pValue != 0) {
            return $pValue * 566.928;
        } else {
            return 0;
        }
    }

    /**
     * Convert twips width to centimeters
     *
     * @param integer $pValue
     */
    public static function twipsToCentimeters($pValue = 0)
    {
        if ($pValue != 0) {
            return $pValue / 566.928;
        } else {
            return 0;
        }
    }

    /**
     * Convert inches width to twips
     *
     * @param integer $pValue
     */
    public static function inchesToTwips($pValue = 0)
    {
        if ($pValue != 0) {
            return $pValue * 1440;
        } else {
            return 0;
        }
    }

    /**
     * Convert twips width to inches
     *
     * @param integer $pValue
     */
    public static function twipsToInches($pValue = 0)
    {
        if ($pValue != 0) {
            return $pValue / 1440;
        } else {
            return 0;
        }
    }

    /**
     * Convert twips width to pixels
     *
     * @param integer $pValue
     */
    public static function twipsToPixels($pValue = 0)
    {
        if ($pValue != 0) {
            return round($pValue / 15.873984);
        } else {
            return 0;
        }
    }

    /**
     * Convert HTML hexadecimal to RGB
     *
     * @param string $pValue HTML Color in hexadecimal
     * @return array Value in RGB
     */
    public static function htmlToRGB($pValue)
    {
        if ($pValue[0] == '#') {
            $pValue = substr($pValue, 1);
        }

        if (strlen($pValue) == 6) {
            list($colorR, $colorG, $colorB) = array($pValue[0] . $pValue[1], $pValue[2] . $pValue[3], $pValue[4] . $pValue[5]);
        } elseif (strlen($pValue) == 3) {
            list($colorR, $colorG, $colorB) = array($pValue[0] . $pValue[0], $pValue[1] . $pValue[1], $pValue[2] . $pValue[2]);
        } else {
            return false;
        }

        $colorR = hexdec($colorR);
        $colorG = hexdec($colorG);
        $colorB = hexdec($colorB);

        return array($colorR, $colorG, $colorB);
    }
}
