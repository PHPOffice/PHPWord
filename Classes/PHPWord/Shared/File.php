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
 * @category   PHPWord
 * @package    PHPWord
 * @copyright  Copyright (c) 2014 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.8.0
 */

/**
 * Class PHPWord_Shared_File
 */
class PHPWord_Shared_File
{
    const IMAGETYPE_JPEG = 'jpg';
    const IMAGETYPE_GIF = 'gif';
    const IMAGETYPE_PNG = 'png';
    const IMAGETYPE_BMP = 'bmp';
    const IMAGETYPE_TIFF = 'tif';

    /**
     * Verify if a file exists
     *
     * @param string $pFilename Filename
     * @return bool
     */
    public static function file_exists($pFilename)
    {
        return file_exists($pFilename);
    }

    /**
     * Returns canonicalized absolute pathname, also for ZIP archives
     *
     * @param string $pFilename
     * @return string
     */
    public static function realpath($pFilename)
    {
        $returnValue = realpath($pFilename);

        if (!$returnValue) {
            $pathArray = explode('/', $pFilename);
            while (in_array('..', $pathArray) && $pathArray[0] !== '..') {
                for ($i = 0; $i < count($pathArray); ++$i) {
                    if ($pathArray[$i] === '..' && $i > 0) {
                        unset($pathArray[$i]);
                        unset($pathArray[$i - 1]);
                        break;
                    }
                }
            }
            $returnValue = implode('/', $pathArray);
        }

        return $returnValue;
    }

    /**
     * PHP Words version of exif_imagetype to return the Image Type from a file
     *
     * @param string $filename
     * @return int|bool
     */
    private static function fallbackImagetype($filename)
    {
        if ((list($width, $height, $type, $attr) = getimagesize($filename)) !== false) {
            if ($type === 2) {
                return self::IMAGETYPE_JPEG;
            } elseif ($type === 1) {
                return self::IMAGETYPE_GIF;
            } elseif ($type === 3) {
                return self::IMAGETYPE_PNG;
            } elseif ($type === 6) {
                return self::IMAGETYPE_BMP;
            } elseif ($type === 7 || $type === 8) {
                return self::IMAGETYPE_TIFF;
            }
        }
        return false;
    }

    /**
     * Return the Image Type from a file
     *
     * @param string $filename
     * @param bool $userFallbackFunction
     * @return int|bool
     */
    public static function imagetype($filename, $userFallbackFunction = false)
    {
        if ($userFallbackFunction || !function_exists('exif_imagetype')) {
            return self::fallbackImagetype($filename);
        }

        $imagetype = exif_imagetype($filename);
        if ($imagetype === IMAGETYPE_JPEG) {
            return self::IMAGETYPE_JPEG;
        } elseif ($imagetype === IMAGETYPE_GIF) {
            return self::IMAGETYPE_GIF;
        } elseif ($imagetype === IMAGETYPE_PNG) {
            return self::IMAGETYPE_PNG;
        } elseif ($imagetype === IMAGETYPE_BMP) {
            return self::IMAGETYPE_BMP;
        } elseif ($imagetype === IMAGETYPE_TIFF_II || $imagetype === IMAGETYPE_TIFF_MM) {
            return self::IMAGETYPE_TIFF;
        }
        return false;
    }
}