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

use PhpOffice\PhpWord\Exceptions\InvalidImageException;
use PhpOffice\PhpWord\Exceptions\UnsupportedImageTypeException;

/**
 * Class PHPWord_Section_Image
 */
class PHPWord_Section_Image
{
    /**
     * Image Src
     *
     * @var string
     */
    private $_src;

    /**
     * Image Style
     *
     * @var PHPWord_Style_Image
     */
    private $_style;

    /**
     * Image Relation ID
     *
     * @var string
     */
    private $_rId;

    /**
     * Is Watermark
     *
     * @var bool
     */
    private $_isWatermark;

    /**
     * Create a new Image
     *
     * @param string $src
     * @param mixed $style
     * @param bool $isWatermark
     * @throws InvalidImageException|UnsupportedImageTypeException
     */
    public function __construct($src, $style = null, $isWatermark = false)
    {
        if (!file_exists($src)) {
            throw new InvalidImageException;
        }

        if (!PHPWord_Shared_File::imagetype($src)) {
            throw new UnsupportedImageTypeException;
        }

        $this->_src = $src;
        $this->_isWatermark = $isWatermark;
        $this->_style = new PHPWord_Style_Image();

        if (!is_null($style) && is_array($style)) {
            foreach ($style as $key => $value) {
                if (substr($key, 0, 1) != '_') {
                    $key = '_' . $key;
                }
                $this->_style->setStyleValue($key, $value);
            }
        }

        if (isset($style['wrappingStyle'])) {
            $this->_style->setWrappingStyle($style['wrappingStyle']);
        }

        if ($this->_style->getWidth() == null && $this->_style->getHeight() == null) {
            $imgData = getimagesize($this->_src);
            $this->_style->setWidth($imgData[0]);
            $this->_style->setHeight($imgData[1]);
        }
    }

    /**
     * Get Image style
     *
     * @return PHPWord_Style_Image
     */
    public function getStyle()
    {
        return $this->_style;
    }

    /**
     * Get Image Relation ID
     *
     * @return int
     */
    public function getRelationId()
    {
        return $this->_rId;
    }

    /**
     * Set Image Relation ID
     *
     * @param int $rId
     */
    public function setRelationId($rId)
    {
        $this->_rId = $rId;
    }

    /**
     * Get Image Source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->_src;
    }

    /**
     * Get Image Media ID
     *
     * @return string
     */
    public function getMediaId()
    {
        return md5($this->_src);
    }

    /**
     * Get IsWatermark
     *
     * @return int
     */
    public function getIsWatermark()
    {
        return $this->_isWatermark;
    }

    /**
     * Set IsWatermark
     *
     * @param bool $pValue
     */
    public function setIsWatermark($pValue)
    {
        $this->_isWatermark = $pValue;
    }
}
