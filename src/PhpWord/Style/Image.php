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

namespace PhpOffice\PhpWord\Style;

/**
 * Image and memory image style
 */
class Image
{
    const WRAPPING_STYLE_INLINE = 'inline';
    const WRAPPING_STYLE_SQUARE = 'square';
    const WRAPPING_STYLE_TIGHT = 'tight';
    const WRAPPING_STYLE_BEHIND = 'behind';
    const WRAPPING_STYLE_INFRONT = 'infront';

    /**
     * Image width
     *
     * @var int
     */
    private $_width;

    /**
     * Image width
     *
     * @var int
     */
    private $_height;

    /**
     * Alignment
     *
     * @var string
     */
    private $_align;

    /**
     * Wrapping style
     *
     * @var string
     */
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

    /**
     * Create new image style
     */
    public function __construct()
    {
        $this->_width = null;
        $this->_height = null;
        $this->_align = null;
        $this->_marginTop = null;
        $this->_marginLeft = null;
        $this->setWrappingStyle(self::WRAPPING_STYLE_INLINE);
    }

    /**
     * Set style value
     *
     * @param string $key
     * @param mixed $value
     */
    public function setStyleValue($key, $value)
    {
        $this->$key = $value;
    }

    /**
     * Get width
     */
    public function getWidth()
    {
        return $this->_width;
    }

    /**
     * Set width
     *
     * @param int $pValue
     */
    public function setWidth($pValue = null)
    {
        $this->_width = $pValue;
    }

    /**
     * Get height
     */
    public function getHeight()
    {
        return $this->_height;
    }

    /**
     * Set height
     *
     * @param int $pValue
     */
    public function setHeight($pValue = null)
    {
        $this->_height = $pValue;
    }

    /**
     * Get alignment
     */
    public function getAlign()
    {
        return $this->_align;
    }

    /**
     * Set alignment
     *
     * @param string $pValue
     */
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
     * Set wrapping style
     *
     * @param string $wrappingStyle
     * @throws \InvalidArgumentException
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
                throw new \InvalidArgumentException('Wrapping style does not exists');
                break;
        }
        return $this;
    }

    /**
     * Get wrapping style
     *
     * @return string
     */
    public function getWrappingStyle()
    {
        return $this->wrappingStyle;
    }
}
