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
    public static function PHPWord_imagetype($filename)
    {
        if ((list($width, $height, $type, $attr) = getimagesize($filename)) !== false) {
            return $type;
        }
        return false;
    }

    /**
     * Return the Image Type from a file
     *
     * @param string $filename
     * @return int|bool
     */
    public static function imagetype($filename)
    {
        if (function_exists('exif_imagetype')) {
            return exif_imagetype($filename);
        } else {
            return self::PHPWord_imagetype($filename);
        }
        return false;
    }
}