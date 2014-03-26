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
 * Table cell style
 */
class Cell
{
    const TEXT_DIR_BTLR = 'btLr';
    const TEXT_DIR_TBRL = 'tbRl';

    /**
     * Vertical align (top, center, both, bottom)
     *
     * @var string
     */
    private $_valign;

    /**
     * Text Direction
     *
     * @var string
     */
    private $_textDirection;

    /**
     * Background-Color
     *
     * @var string
     */
    private $_bgColor;

    /**
     * Border Top Size
     *
     * @var int
     */
    private $_borderTopSize;

    /**
     * Border Top Color
     *
     * @var string
     */
    private $_borderTopColor;

    /**
     * Border Left Size
     *
     * @var int
     */
    private $_borderLeftSize;

    /**
     * Border Left Color
     *
     * @var string
     */
    private $_borderLeftColor;

    /**
     * Border Right Size
     *
     * @var int
     */
    private $_borderRightSize;

    /**
     * Border Right Color
     *
     * @var string
     */
    private $_borderRightColor;

    /**
     * Border Bottom Size
     *
     * @var int
     */
    private $_borderBottomSize;

    /**
     * Border Bottom Color
     *
     * @var string
     */
    private $_borderBottomColor;

    /**
     * Border Default Color
     *
     * @var string
     */
    private $_defaultBorderColor;

    /**
     * colspan
     *
     * @var integer
     */
    private $_gridSpan = null;

    /**
     * rowspan (restart, continue)
     *
     * - restart: Start/restart merged region
     * - continue: Continue merged region
     *
     * @var string
     */
    private $_vMerge = null;

    /**
     * Create a new Cell Style
     */
    public function __construct()
    {
        $this->_valign = null;
        $this->_textDirection = null;
        $this->_bgColor = null;
        $this->_borderTopSize = null;
        $this->_borderTopColor = null;
        $this->_borderLeftSize = null;
        $this->_borderLeftColor = null;
        $this->_borderRightSize = null;
        $this->_borderRightColor = null;
        $this->_borderBottomSize = null;
        $this->_borderBottomColor = null;
        $this->_defaultBorderColor = '000000';
    }

    /**
     * Set style value
     *
     * @param string $key
     * @param mixed $value
     */
    public function setStyleValue($key, $value)
    {
        if ($key == '_borderSize') {
            $this->setBorderSize($value);
        } elseif ($key == '_borderColor') {
            $this->setBorderColor($value);
        } else {
            $this->$key = $value;
        }
    }

    /**
     * Get vertical align
     */
    public function getVAlign()
    {
        return $this->_valign;
    }

    /**
     * Set vertical align
     *
     * @param string $pValue
     */
    public function setVAlign($pValue = null)
    {
        $this->_valign = $pValue;
    }

    /**
     * Get text direction
     */
    public function getTextDirection()
    {
        return $this->_textDirection;
    }

    /**
     * Set text direction
     *
     * @param string $pValue
     */
    public function setTextDirection($pValue = null)
    {
        $this->_textDirection = $pValue;
    }

    /**
     * Get background color
     */
    public function getBgColor()
    {
        return $this->_bgColor;
    }

    /**
     * Set background color
     *
     * @param string $pValue
     */
    public function setBgColor($pValue = null)
    {
        $this->_bgColor = $pValue;
    }

    /**
     * Set border size
     *
     * @param int $pValue
     */
    public function setBorderSize($pValue = null)
    {
        $this->_borderTopSize = $pValue;
        $this->_borderLeftSize = $pValue;
        $this->_borderRightSize = $pValue;
        $this->_borderBottomSize = $pValue;
    }

    /**
     * Get border size
     */
    public function getBorderSize()
    {
        $t = $this->getBorderTopSize();
        $l = $this->getBorderLeftSize();
        $r = $this->getBorderRightSize();
        $b = $this->getBorderBottomSize();

        return array($t, $l, $r, $b);
    }

    /**
     * Set border color
     *
     * @param string $pValue
     */
    public function setBorderColor($pValue = null)
    {
        $this->_borderTopColor = $pValue;
        $this->_borderLeftColor = $pValue;
        $this->_borderRightColor = $pValue;
        $this->_borderBottomColor = $pValue;
    }

    /**
     * Get border color
     */
    public function getBorderColor()
    {
        $t = $this->getBorderTopColor();
        $l = $this->getBorderLeftColor();
        $r = $this->getBorderRightColor();
        $b = $this->getBorderBottomColor();

        return array($t, $l, $r, $b);
    }

    /**
     * Set border top size
     *
     * @param int $pValue
     */
    public function setBorderTopSize($pValue = null)
    {
        $this->_borderTopSize = $pValue;
    }

    /**
     * Get border top size
     */
    public function getBorderTopSize()
    {
        return $this->_borderTopSize;
    }

    /**
     * Set border top color
     *
     * @param string $pValue
     */
    public function setBorderTopColor($pValue = null)
    {
        $this->_borderTopColor = $pValue;
    }

    /**
     * Get border top color
     */
    public function getBorderTopColor()
    {
        return $this->_borderTopColor;
    }

    /**
     * Set border left size
     *
     * @param int $pValue
     */
    public function setBorderLeftSize($pValue = null)
    {
        $this->_borderLeftSize = $pValue;
    }

    /**
     * Get border left size
     */
    public function getBorderLeftSize()
    {
        return $this->_borderLeftSize;
    }

    /**
     * Set border left color
     *
     * @param string $pValue
     */
    public function setBorderLeftColor($pValue = null)
    {
        $this->_borderLeftColor = $pValue;
    }

    /**
     * Get border left color
     */
    public function getBorderLeftColor()
    {
        return $this->_borderLeftColor;
    }

    /**
     * Set border right size
     *
     * @param int $pValue
     */
    public function setBorderRightSize($pValue = null)
    {
        $this->_borderRightSize = $pValue;
    }

    /**
     * Get border right size
     */
    public function getBorderRightSize()
    {
        return $this->_borderRightSize;
    }

    /**
     * Set border right color
     *
     * @param string $pValue
     */
    public function setBorderRightColor($pValue = null)
    {
        $this->_borderRightColor = $pValue;
    }

    /**
     * Get border right color
     */
    public function getBorderRightColor()
    {
        return $this->_borderRightColor;
    }

    /**
     * Set border bottom size
     *
     * @param int $pValue
     */
    public function setBorderBottomSize($pValue = null)
    {
        $this->_borderBottomSize = $pValue;
    }

    /**
     * Get border bottom size
     */
    public function getBorderBottomSize()
    {
        return $this->_borderBottomSize;
    }

    /**
     * Set border bottom color
     *
     * @param string $pValue
     */
    public function setBorderBottomColor($pValue = null)
    {
        $this->_borderBottomColor = $pValue;
    }

    /**
     * Get border bottom color
     */
    public function getBorderBottomColor()
    {
        return $this->_borderBottomColor;
    }

    /**
     * Get default border color
     */
    public function getDefaultBorderColor()
    {
        return $this->_defaultBorderColor;
    }

    /**
     * Set grid span (colspan)
     *
     * @param int $pValue
     */
    public function setGridSpan($pValue = null)
    {
        $this->_gridSpan = $pValue;
    }

    /**
     * Get grid span (colspan)
     */
    public function getGridSpan()
    {
        return $this->_gridSpan;
    }

    /**
     * Set vertical merge (rowspan)
     *
     * @param string $pValue
     */
    public function setVMerge($pValue = null)
    {
        $this->_vMerge = $pValue;
    }

    /**
     * Get vertical merge (rowspan)
     */
    public function getVMerge()
    {
        return $this->_vMerge;
    }
}
