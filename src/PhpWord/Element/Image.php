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
class Image extends AbstractElement
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
     * Is watermark
     *
     * @var boolean
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
     * @var boolean
     */
    private $isMemImage;

    /**
     * Create new image element
     *
     * @param string $source
     * @param mixed $style
     * @param boolean $isWatermark
     * @throws InvalidImageException
     * @throws UnsupportedImageTypeException
     */
    public function __construct($source, $style = null, $isWatermark = false)
    {
        // Detect if it's a memory image, by .php ext or by URL
        if (stripos(strrev($source), strrev('.php')) === 0) {
            $this->isMemImage = true;
        } else {
            $this->isMemImage = (filter_var($source, FILTER_VALIDATE_URL) !== false);
        }

        // Check supported types
        if ($this->isMemImage) {
            $supportedTypes = array('image/jpeg', 'image/gif', 'image/png');
            $imgData = @getimagesize($source);
            if (!is_array($imgData)) {
                throw new InvalidImageException();
            }
            $this->imageType = $imgData['mime']; // string
            if (!in_array($this->imageType, $supportedTypes)) {
                throw new UnsupportedImageTypeException();
            }
        } else {
            $supportedTypes = array(
                IMAGETYPE_JPEG, IMAGETYPE_GIF,
                IMAGETYPE_PNG, IMAGETYPE_BMP,
                IMAGETYPE_TIFF_II, IMAGETYPE_TIFF_MM
            );
            if (!file_exists($source)) {
                throw new InvalidImageException();
            }
            $imgData = getimagesize($source);
            if (function_exists('exif_imagetype')) {
                $this->imageType = exif_imagetype($source);
            } else {
                // @codeCoverageIgnoreStart
                $tmp = getimagesize($source);
                $this->imageType = $tmp[2];
                // @codeCoverageIgnoreEnd
            }
            if (!in_array($this->imageType, $supportedTypes)) {
                throw new UnsupportedImageTypeException();
            }
            $this->imageType = image_type_to_mime_type($this->imageType);
        }

        // Set private properties
        $this->source = $source;
        $this->isWatermark = $isWatermark;
        $this->style = $this->setStyle(new ImageStyle(), $style, true);
        $styleWidth = $this->style->getWidth();
        $styleHeight = $this->style->getHeight();
        if (!($styleWidth && $styleHeight)) {
            if ($styleWidth == null && $styleHeight == null) {
                $this->style->setWidth($imgData[0]);
                $this->style->setHeight($imgData[1]);
            } else if ($styleWidth) {
                $this->style->setHeight($imgData[1] * ($styleWidth / $imgData[0]));
            } else {
                $this->style->setWidth($imgData[0] * ($styleHeight / $imgData[1]));
            }
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
     * @return boolean
     */
    public function getIsWatermark()
    {
        return $this->isWatermark;
    }

    /**
     * Set is watermark
     *
     * @param boolean $pValue
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
