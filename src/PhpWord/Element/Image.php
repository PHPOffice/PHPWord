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
        $this->source = $source;
        $this->setIsWatermark($isWatermark);
        $this->style = $this->setStyle(new ImageStyle(), $style, true);

        $this->checkImage($source);
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
     * Check memory image, supported type, image functions, and proportional width/height
     *
     * @param string $source
     */
    private function checkImage($source)
    {
        $isArchive = strpos($source, 'zip://') !== false;

        // Check is memory image
        if (stripos(strrev($source), strrev('.php')) === 0) {
            $this->isMemImage = true;
        } elseif ($isArchive) {
            $this->isMemImage = false;
        } else {
            $this->isMemImage = (filter_var($source, FILTER_VALIDATE_URL) !== false);
        }

        // Define supported types
        if ($this->isMemImage) {
            $supportedTypes = array(
                IMAGETYPE_JPEG, IMAGETYPE_GIF, IMAGETYPE_PNG
            );
        } else {
            $supportedTypes = array(
                IMAGETYPE_JPEG, IMAGETYPE_GIF, IMAGETYPE_PNG,
                IMAGETYPE_BMP, IMAGETYPE_TIFF_II, IMAGETYPE_TIFF_MM
            );
        }

        // Check from zip file or actual file
        if ($isArchive) {
            $imageData = $this->getArchivedImageSize($source);
        } else {
            $imageData = @getimagesize($source);
        }

        // Check if image exists by detecting image data
        if (!is_array($imageData)) {
            throw new InvalidImageException();
        }
        // Put image data into variables
        list($actualWidth, $actualHeight, $imageType) = $imageData;
        // Check if image type is supported
        if (!in_array($imageType, $supportedTypes)) {
            throw new UnsupportedImageTypeException();
        }

        // Define image functions
        $this->imageType = image_type_to_mime_type($imageType);
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
                $this->imageCreateFunc = 'imagecreatefromjpeg';
                $this->imageFunc = 'imagejpeg';
                $this->imageExtension = 'jpg';
                break;
            case 'image/bmp':
            case 'image/x-ms-bmp':
                $this->imageType = 'image/bmp';
                $this->imageExtension = 'bmp';
                break;
            case 'image/tiff':
                $this->imageExtension = 'tif';
                break;
        }

        // Check image width & height
        $styleWidth = $this->style->getWidth();
        $styleHeight = $this->style->getHeight();
        if (!($styleWidth && $styleHeight)) {
            if ($styleWidth == null && $styleHeight == null) {
                $this->style->setWidth($actualWidth);
                $this->style->setHeight($actualHeight);
            } elseif ($styleWidth) {
                $this->style->setHeight($actualHeight * ($styleWidth / $actualWidth));
            } else {
                $this->style->setWidth($actualWidth * ($styleHeight / $actualHeight));
            }
        }

    }

    /**
     * Get image size from archive
     *
     * @param string $source
     * @return array|null
     */
    private function getArchivedImageSize($source)
    {
        $imageData = null;
        $source = substr($source, 6);
        list($zipFilename, $imageFilename) = explode('#', $source);
        $tempFilename = tempnam(sys_get_temp_dir(), 'PHPWordImage');

        $zip = new \ZipArchive();
        if ($zip->open($zipFilename) !== false) {
            if ($zip->locateName($imageFilename)) {
                $imageContent = $zip->getFromName($imageFilename);
                if ($imageContent !== false) {
                    file_put_contents($tempFilename, $imageContent);
                    $imageData = @getimagesize($tempFilename);
                    unlink($tempFilename);
                }
            }
            $zip->close();
        }

        return $imageData;
    }
}
