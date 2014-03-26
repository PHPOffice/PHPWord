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

namespace PhpOffice\PhpWord\Section;

use PhpOffice\PhpWord\Exceptions\InvalidImageException;
use PhpOffice\PhpWord\Exceptions\UnsupportedImageTypeException;

/**
 * Image element
 */
class Image
{
    /**
     * Image source
     *
     * @var string
     */
    private $source;

    /**
     * Image style
     *
     * @var \PhpOffice\PhpWord\Style\Image
     */
    private $style;

    /**
     * Image relation ID specific only for DOCX
     *
     * @var string
     */
    private $rId;

    /**
     * Is watermark
     *
     * @var bool
     */
    private $isWatermark;

    /**
     * Image type
     *
     * @var string
     */
    private $imageType;

    /**
     * Image create function
     *
     * @var string
     */
    private $imageCreateFunc;

    /**
     * Image function
     *
     * @var string
     */
    private $imageFunc;

    /**
     * Image extension
     *
     * @var string
     */
    private $imageExtension;

    /**
     * Is memory image
     *
     * @var string
     */
    private $isMemImage;

    /**
     * Create new image element
     *
     * @param string $source
     * @param mixed $style
     * @param bool $isWatermark
     * @throws \PhpOffice\PhpWord\Exceptions\InvalidImageException
     * @throws \PhpOffice\PhpWord\Exceptions\UnsupportedImageTypeException
     */
    public function __construct($source, $style = null, $isWatermark = false)
    {
        // Detect if it's a memory image, by .php ext or by URL
        if (stripos(strrev($source), strrev('.php')) === 0) {
            $this->isMemImage = true;
        } else {
            $this->isMemImage = (filter_var($source, \FILTER_VALIDATE_URL) !== false);
        }

        // Check supported types
        if ($this->isMemImage) {
            $supportedTypes = array('image/jpeg', 'image/gif', 'image/png');
            $imgData = getimagesize($source);
            $this->imageType = $imgData['mime']; // string
            if (!in_array($this->imageType, $supportedTypes)) {
                throw new UnsupportedImageTypeException;
            }
        } else {
            $supportedTypes = array(
                \IMAGETYPE_JPEG, \IMAGETYPE_GIF,
                \IMAGETYPE_PNG, \IMAGETYPE_BMP,
                \IMAGETYPE_TIFF_II, \IMAGETYPE_TIFF_MM
            );
            if (!\file_exists($source)) {
                throw new InvalidImageException;
            }
            $imgData = getimagesize($source);
            $this->imageType = exif_imagetype($source);
            if (!in_array($this->imageType, $supportedTypes)) {
                throw new UnsupportedImageTypeException;
            }
            $this->imageType = \image_type_to_mime_type($this->imageType);
        }

        // Set private properties
        $this->source = $source;
        $this->isWatermark = $isWatermark;
        $this->style = new \PhpOffice\PhpWord\Style\Image();
        if (!is_null($style) && is_array($style)) {
            foreach ($style as $key => $value) {
                if (substr($key, 0, 1) != '_') {
                    $key = '_' . $key;
                }
                $this->style->setStyleValue($key, $value);
            }
        }
        if (isset($style['wrappingStyle'])) {
            $this->style->setWrappingStyle($style['wrappingStyle']);
        }
        if ($this->style->getWidth() == null && $this->style->getHeight() == null) {
            $this->style->setWidth($imgData[0]);
            $this->style->setHeight($imgData[1]);
        }
        $this->setImageFunctions();
    }

    /**
     * Get Image style
     *
     * @return \PhpOffice\PhpWord\Style\Image
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Get image relation ID
     *
     * @return int
     */
    public function getRelationId()
    {
        return $this->rId;
    }

    /**
     * Set image relation ID
     *
     * @param int $rId
     */
    public function setRelationId($rId)
    {
        $this->rId = $rId;
    }

    /**
     * Get image source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Get image media ID
     *
     * @return string
     */
    public function getMediaId()
    {
        return md5($this->source);
    }

    /**
     * Get is watermark
     *
     * @return int
     */
    public function getIsWatermark()
    {
        return $this->isWatermark;
    }

    /**
     * Set is watermark
     *
     * @param bool $pValue
     */
    public function setIsWatermark($pValue)
    {
        $this->isWatermark = $pValue;
    }

    /**
     * Get image type
     *
     * @return string
     */
    public function getImageType()
    {
        return $this->imageType;
    }

    /**
     * Get image create function
     *
     * @return string
     */
    public function getImageCreateFunction()
    {
        return $this->imageCreateFunc;
    }

    /**
     * Get image function
     *
     * @return string
     */
    public function getImageFunction()
    {
        return $this->imageFunc;
    }

    /**
     * Get image extension
     *
     * @return string
     */
    public function getImageExtension()
    {
        return $this->imageExtension;
    }

    /**
     * Get is memory image
     *
     * @return boolean
     */
    public function getIsMemImage()
    {
        return $this->isMemImage;
    }

    /**
     * Set image functions
     */
    private function setImageFunctions()
    {
        switch ($this->imageType) {
            case 'image/png':
                $this->imageCreateFunc = 'imagecreatefrompng';
                $this->imageFunc = 'imagepng';
                $this->imageExtension = 'png';
                break;
            case 'image/gif':
                $this->imageCreateFunc = 'imagecreatefromgif';
                $this->imageFunc = 'imagegif';
                $this->imageExtension = 'gif';
                break;
            case 'image/jpeg':
            case 'image/jpg':
                $this->imageCreateFunc = 'imagecreatefromjpeg';
                $this->imageFunc = 'imagejpeg';
                $this->imageExtension = 'jpg';
                break;
            case 'image/x-ms-bmp':
            case 'image/bmp':
                $this->imageType = 'image/bmp';
                $this->imageExtension = 'bmp';
                break;
            case 'image/tiff':
                $this->imageExtension = 'tif';
                break;
        }
    }
}
