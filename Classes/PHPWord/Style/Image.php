<?php
/**
 * PHPWord
 *
 * Copyright (c) 2013 PHPWord
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
 * @copyright  Copyright (c) 2013 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.7.0
 */

/**
 * Class PHPWord_Style_Image
 */
class PHPWord_Style_Image
{
    const WRAPPING_STYLE_INLINE = 'inline';
    const WRAPPING_STYLE_SQUARE = 'square';
    const WRAPPING_STYLE_TIGHT = 'tight';
    const WRAPPING_STYLE_BEHIND = 'behind';
    const WRAPPING_STYLE_INFRONT = 'infront';

    private $_width;
    private $_height;
    private $_align;
    private $wrappingStyle;

    /**
     * Margin Top
     *
     * @var int
     */
    private $_marginTop;

    /**
     * Margin Left
     *
     * @var int
     */
    private $_marginLeft;

    public function __construct()
    {
        $this->_width = null;
        $this->_height = null;
        $this->_align = null;
        $this->_marginTop = null;
        $this->_marginLeft = null;
        $this->setWrappingStyle(self::WRAPPING_STYLE_INLINE);
    }

    public function setStyleValue($key, $value)
    {
        $this->$key = $value;
    }

    public function getWidth()
    {
        return $this->_width;
    }

    public function setWidth($pValue = null)
    {
        $this->_width = $pValue;
    }

    public function getHeight()
    {
        return $this->_height;
    }

    public function setHeight($pValue = null)
    {
        $this->_height = $pValue;
    }

    public function getAlign()
    {
        return $this->_align;
    }

    public function setAlign($pValue = null)
    {
        $this->_align = $pValue;
    }

    /**
     * Get Margin Top
     *
     * @return int
     */
    public function getMarginTop()
    {
        return $this->_marginTop;
    }

    /**
     * Set Margin Top
     *
     * @param int $pValue
     * @return $this
     */
    public function setMarginTop($pValue = null)
    {
        $this->_marginTop = $pValue;
        return $this;
    }

    /**
     * Get Margin Left
     *
     * @return int
     */
    public function getMarginLeft()
    {
        return $this->_marginLeft;
    }

    /**
     * Set Margin Left
     *
     * @param int $pValue
     * @return $this
     */
    public function setMarginLeft($pValue = null)
    {
        $this->_marginLeft = $pValue;
        return $this;
    }

    /**
     * @param string $wrappingStyle
     * @throws InvalidArgumentException
     * @return $this
     */
    public function setWrappingStyle($wrappingStyle)
    {
        switch ($wrappingStyle) {
            case self::WRAPPING_STYLE_BEHIND:
            case self::WRAPPING_STYLE_INFRONT:
            case self::WRAPPING_STYLE_INLINE:
            case self::WRAPPING_STYLE_SQUARE:
            case self::WRAPPING_STYLE_TIGHT:
                $this->wrappingStyle = $wrappingStyle;
                break;
            default:
                throw new InvalidArgumentException('Wrapping style does not exists');
                break;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getWrappingStyle()
    {
        return $this->wrappingStyle;
    }
}