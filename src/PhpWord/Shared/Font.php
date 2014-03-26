<?php
/**
 * PHPWord
 *
 * Copyright (c) 2014 PHPWord
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @copyright  Copyright (c) 2014 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.9.0
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
     * @param    int $fontSizeInPoints Font size (in points)
     * @return    int        Font size (in pixels)
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
     * @return int Size (in pixels)
     */
    public static function centimeterSizeToPixels($sizeInCm = 1)
    {
        return ($sizeInCm * 37.795275591);
    }

    /**
     * Convert centimeter to twip
     *
     * @param int $sizeInCm
     * @return int
     */
    public static function centimeterSizeToTwips($sizeInCm = 1)
    {
        return ($sizeInCm * 565.217);
    }

    /**
     * Convert inch to twip
     *
     * @param int $sizeInInch
     * @return int
     */
    public static function inchSizeToTwips($sizeInInch = 1)
    {
        return self::centimeterSizeToTwips($sizeInInch * 2.54);
    }

    /**
     * Convert pixel to twip
     *
     * @param int $sizeInPixel
     * @return int
     */
    public static function pixelSizeToTwips($sizeInPixel = 1)
    {
        return self::centimeterSizeToTwips($sizeInPixel / 37.795275591);
    }

    /**
     * Calculate twip based on point size, used mainly for paragraph spacing
     *
     * @param   int|float   $sizeInPoint Size in point
     * @return  int|float   Size (in twips)
     */
    public static function pointSizeToTwips($sizeInPoint = 1)
    {
        return ($sizeInPoint * 20);
    }
}
