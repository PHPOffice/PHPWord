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

namespace PhpOffice\PhpWord\Element;

use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\Exception\InvalidImageException;
use PhpOffice\PhpWord\Exception\UnsupportedImageTypeException;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Shared\ZipArchive;
use PhpOffice\PhpWord\Style\Image as ImageStyle;

/**
 * Image element
 */
class Image extends AbstractElement
{
    /**
     * Image source type constants
     */
    const SOURCE_LOCAL = 'local'; // Local images
    const SOURCE_GD = 'gd'; // Generated using GD
    const SOURCE_ARCHIVE = 'archive'; // Image in archives zip://$archive#$image

    /**
     * Image source
     *
     * @var string
     */
    private $source;

    /**
     * Source type: local|gd|archive
     *
     * @var string
     */
    private $sourceType;

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
    private $watermark;

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
    private $memoryImage;

    /**
     * Image target file name
     *
     * @var string
     */
    private $target;

    /**
     * Image media index
     *
     * @var integer
     */
    private $mediaIndex;

    /**
     * Has media relation flag; true for Link, Image, and Object
     *
     * @var bool
     */
    protected $mediaRelation = true;

    /**
     * Create new image element
     *
     * @param string $source
     * @param mixed $style
     * @param boolean $watermark
     *
     * @throws \PhpOffice\PhpWord\Exception\InvalidImageException
     * @throws \PhpOffice\PhpWord\Exception\UnsupportedImageTypeException
     */
    public function __construct($source, $style = null, $watermark = false)
    {
        $this->source = $source;
        $this->setIsWatermark($watermark);
        $this->style = $this->setNewStyle(new ImageStyle(), $style, true);

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
     * Get image source type
     *
     * @return string
     */
    public function getSourceType()
    {
        return $this->sourceType;
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
    public function isWatermark()
    {
        return $this->watermark;
    }

    /**
     * Set is watermark
     *
     * @param boolean $value
     */
    public function setIsWatermark($value)
    {
        $this->watermark = $value;
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
    public function isMemImage()
    {
        return $this->memoryImage;
    }

    /**
     * Get target file name
     *
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Set target file name.
     *
     * @param string $value
     * @return void
     */
    public function setTarget($value)
    {
        $this->target = $value;
    }

    /**
     * Get media index
     *
     * @return integer
     */
    public function getMediaIndex()
    {
        return $this->mediaIndex;
    }

    /**
     * Set media index.
     *
     * @param integer $value
     * @return void
     */
    public function setMediaIndex($value)
    {
        $this->mediaIndex = $value;
    }

    /**
     * Get image string data
     *
     * @param bool $base64
     * @return string|null
     * @since 0.11.0
     */
    public function getImageStringData($base64 = false)
    {
        $source = $this->source;
        $actualSource = null;
        $imageBinary = null;
        $imageData = null;
        $isTemp = false;

        // Get actual source from archive image or other source
        // Return null if not found
        if ($this->sourceType == self::SOURCE_ARCHIVE) {
            $source = substr($source, 6);
            list($zipFilename, $imageFilename) = explode('#', $source);

            $zip = new ZipArchive();
            if ($zip->open($zipFilename) !== false) {
                if ($zip->locateName($imageFilename)) {
                    $isTemp = true;
                    $zip->extractTo(Settings::getTempDir(), $imageFilename);
                    $actualSource = Settings::getTempDir() . DIRECTORY_SEPARATOR . $imageFilename;
                }
            }
            $zip->close();
        } else {
            $actualSource = $source;
        }

        // Can't find any case where $actualSource = null hasn't captured by
        // preceding exceptions. Please uncomment when you find the case and
        // put the case into Element\ImageTest.
        // if ($actualSource === null) {
        //     return null;
        // }

        // Read image binary data and convert to hex/base64 string
        if ($this->sourceType == self::SOURCE_GD) {
            $imageResource = call_user_func($this->imageCreateFunc, $actualSource);
            ob_start();
            call_user_func($this->imageFunc, $imageResource);
            $imageBinary = ob_get_contents();
            ob_end_clean();
        } else {
            $fileHandle = fopen($actualSource, 'rb', false);
            if ($fileHandle !== false) {
                $imageBinary = fread($fileHandle, filesize($actualSource));
                fclose($fileHandle);
            }
        }
        if ($imageBinary !== null) {
            if ($base64) {
                $imageData = chunk_split(base64_encode($imageBinary));
            } else {
                $imageData = chunk_split(bin2hex($imageBinary));
            }
        }

        // Delete temporary file if necessary
        if ($isTemp === true) {
            @unlink($actualSource);
        }

        return $imageData;
    }

    /**
     * Check memory image, supported type, image functions, and proportional width/height.
     *
     * @param string $source
     *
     * @return void
     *
     * @throws \PhpOffice\PhpWord\Exception\InvalidImageException
     * @throws \PhpOffice\PhpWord\Exception\UnsupportedImageTypeException
     */
    private function checkImage($source)
    {
        $this->setSourceType($source);

        // Check image data
        if ($this->sourceType == self::SOURCE_ARCHIVE) {
            $imageData = $this->getArchiveImageSize($source);
        } else {
            $imageData = @getimagesize($source);
        }
        if (!is_array($imageData)) {
            throw new InvalidImageException(sprintf('Invalid image: %s', $source));
        }
        list($actualWidth, $actualHeight, $imageType) = $imageData;

        // Check image type support
        $supportedTypes = array(IMAGETYPE_JPEG, IMAGETYPE_GIF, IMAGETYPE_PNG);
        if ($this->sourceType != self::SOURCE_GD) {
            $supportedTypes = array_merge($supportedTypes, array(IMAGETYPE_BMP, IMAGETYPE_TIFF_II, IMAGETYPE_TIFF_MM));
        }
        if (!in_array($imageType, $supportedTypes)) {
            throw new UnsupportedImageTypeException();
        }

        // Define image functions
        $this->imageType = image_type_to_mime_type($imageType);
        $this->setFunctions();
        $this->setProportionalSize($actualWidth, $actualHeight);
    }

    /**
     * Set source type.
     *
     * @param string $source
     * @return void
     */
    private function setSourceType($source)
    {
        if (stripos(strrev($source), strrev('.php')) === 0) {
            $this->memoryImage = true;
            $this->sourceType = self::SOURCE_GD;
        } elseif (strpos($source, 'zip://') !== false) {
            $this->memoryImage = false;
            $this->sourceType = self::SOURCE_ARCHIVE;
        } else {
            $this->memoryImage = (filter_var($source, FILTER_VALIDATE_URL) !== false);
            $this->sourceType = $this->memoryImage ? self::SOURCE_GD : self::SOURCE_LOCAL;
        }
    }

    /**
     * Get image size from archive
     *
     * @since 0.12.0 Throws CreateTemporaryFileException.
     *
     * @param string $source
     *
     * @return array|null
     *
     * @throws \PhpOffice\PhpWord\Exception\CreateTemporaryFileException
     */
    private function getArchiveImageSize($source)
    {
        $imageData = null;
        $source = substr($source, 6);
        list($zipFilename, $imageFilename) = explode('#', $source);

        $tempFilename = tempnam(Settings::getTempDir(), 'PHPWordImage');
        if (false === $tempFilename) {
            throw new CreateTemporaryFileException();
        }

        $zip = new ZipArchive();
        if ($zip->open($zipFilename) !== false) {
            if ($zip->locateName($imageFilename)) {
                $imageContent = $zip->getFromName($imageFilename);
                if ($imageContent !== false) {
                    file_put_contents($tempFilename, $imageContent);
                    $imageData = getimagesize($tempFilename);
                    unlink($tempFilename);
                }
            }
            $zip->close();
        }

        return $imageData;
    }

    /**
     * Set image functions and extensions.
     *
     * @return void
     */
    private function setFunctions()
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
            case 'image/bmp':
            case 'image/x-ms-bmp':
                $this->imageType = 'image/bmp';
                $this->imageExtension = 'bmp';
                break;
            case 'image/tiff':
                $this->imageExtension = 'tif';
                break;
        }
    }

    /**
     * Set proportional width/height if one dimension not available.
     *
     * @param integer $actualWidth
     * @param integer $actualHeight
     * @return void
     */
    private function setProportionalSize($actualWidth, $actualHeight)
    {
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
     * Get is watermark
     *
     * @deprecated 0.10.0
     *
     * @codeCoverageIgnore
     */
    public function getIsWatermark()
    {
        return $this->isWatermark();
    }

    /**
     * Get is memory image
     *
     * @deprecated 0.10.0
     *
     * @codeCoverageIgnore
     */
    public function getIsMemImage()
    {
        return $this->isMemImage();
    }
}
