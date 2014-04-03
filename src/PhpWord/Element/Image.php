<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Exception\InvalidImageException;
use PhpOffice\PhpWord\Exception\UnsupportedImageTypeException;
use PhpOffice\PhpWord\Style\Image as ImageStyle;

/**
 * Image element
 */
class Image extends Element
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
     * @var ImageStyle
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
     * @throws \PhpOffice\PhpWord\Exception\InvalidImageException
     * @throws \PhpOffice\PhpWord\Exception\UnsupportedImageTypeException
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
            if (function_exists('exif_imagetype')) {
                $this->imageType = exif_imagetype($source);
            } else {
                $tmp = getimagesize($source);
                $this->imageType = $tmp[2];
            }
            if (!in_array($this->imageType, $supportedTypes)) {
                throw new UnsupportedImageTypeException;
            }
            $this->imageType = \image_type_to_mime_type($this->imageType);
        }

        // Set private properties
        $this->source = $source;
        $this->isWatermark = $isWatermark;
        $this->style = $this->setStyle(new ImageStyle(), $style, true);
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
     * @return ImageStyle
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
