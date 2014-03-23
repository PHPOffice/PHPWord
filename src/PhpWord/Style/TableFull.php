<?php
/**
 * PhpWord
 *
 * Copyright (c) 2014 PhpWord
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
 * @copyright  Copyright (c) 2014 PhpWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.8.0
 */

namespace PhpOffice\PhpWord\Style;

class TableFull
{
    /**
     * Style for first row
     *
     * @var \PhpOffice\PhpWord\Style\TableFull
     */
    private $_firstRow = null;

    /**
     * @var int
     */
    private $_cellMarginTop = null;

    /**
     * @var int
     */
    private $_cellMarginLeft = null;

    /**
     * @var int
     */
    private $_cellMarginRight = null;

    /**
     * @var int
     */
    private $_cellMarginBottom = null;

    /**
     * @var string
     */
    private $_bgColor;

    /**
     * @var int
     */
    private $_borderTopSize;

    /**
     * @var string
     */
    private $_borderTopColor;

    /**
     * @var int
     */
    private $_borderLeftSize;

    /**
     * @var string
     */
    private $_borderLeftColor;

    /**
     * @var int
     */
    private $_borderRightSize;

    /**
     * @var string
     */
    private $_borderRightColor;

    /**
     * @var int
     */
    private $_borderBottomSize;

    /**
     * @var string
     */
    private $_borderBottomColor;

    /**
     * @var int
     */
    private $_borderInsideHSize;

    /**
     * @var string
     */
    private $_borderInsideHColor;

    /**
     * @var int
     */
    private $_borderInsideVSize;

    /**
     * @var string
     */
    private $_borderInsideVColor;

    public function __construct($styleTable = null, $styleFirstRow = null)
    {
        if (!is_null($styleFirstRow) && is_array($styleFirstRow)) {
            $this->_firstRow = clone $this;

            unset($this->_firstRow->_firstRow);
            unset($this->_firstRow->_cellMarginBottom);
            unset($this->_firstRow->_cellMarginTop);
            unset($this->_firstRow->_cellMarginLeft);
            unset($this->_firstRow->_cellMarginRight);
            unset($this->_firstRow->_borderInsideVColor);
            unset($this->_firstRow->_borderInsideVSize);
            unset($this->_firstRow->_borderInsideHColor);
            unset($this->_firstRow->_borderInsideHSize);
            foreach ($styleFirstRow as $key => $value) {
                if (substr($key, 0, 1) != '_') {
                    $key = '_' . $key;
                }

                $this->_firstRow->setStyleValue($key, $value);
            }
        }

        if (!is_null($styleTable) && is_array($styleTable)) {
            foreach ($styleTable as $key => $value) {
                if (substr($key, 0, 1) != '_') {
                    $key = '_' . $key;
                }
                $this->setStyleValue($key, $value);
            }
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function setStyleValue($key, $value)
    {
        if ($key == '_borderSize') {
            $this->setBorderSize($value);
        } elseif ($key == '_borderColor') {
            $this->setBorderColor($value);
        } elseif ($key == '_cellMargin') {
            $this->setCellMargin($value);
        } else {
            $this->$key = $value;
        }
    }

    /**
     * Get First Row Style
     *
     * @return \PhpOffice\PhpWord\Style\TableFull
     */
    public function getFirstRow()
    {
        return $this->_firstRow;
    }

    /**
     * Get Last Row Style
     *
     * @return \PhpOffice\PhpWord\Style\TableFull
     */
    public function getLastRow()
    {
        return $this->_lastRow;
    }

    public function getBgColor()
    {
        return $this->_bgColor;
    }

    public function setBgColor($pValue = null)
    {
        $this->_bgColor = $pValue;
    }

    /**
     * Set TLRBVH Border Size
     *
     * @param   int     $pValue Border size in eighths of a point (1/8 point)
     */
    public function setBorderSize($pValue = null)
    {
        $this->_borderTopSize = $pValue;
        $this->_borderLeftSize = $pValue;
        $this->_borderRightSize = $pValue;
        $this->_borderBottomSize = $pValue;
        $this->_borderInsideHSize = $pValue;
        $this->_borderInsideVSize = $pValue;
    }

    /**
     * Get TLRBVH Border Size
     *
     * @return array
     */
    public function getBorderSize()
    {
        $t = $this->getBorderTopSize();
        $l = $this->getBorderLeftSize();
        $r = $this->getBorderRightSize();
        $b = $this->getBorderBottomSize();
        $h = $this->getBorderInsideHSize();
        $v = $this->getBorderInsideVSize();

        return array($t, $l, $r, $b, $h, $v);
    }

    /**
     * Set TLRBVH Border Color
     */
    public function setBorderColor($pValue = null)
    {
        $this->_borderTopColor = $pValue;
        $this->_borderLeftColor = $pValue;
        $this->_borderRightColor = $pValue;
        $this->_borderBottomColor = $pValue;
        $this->_borderInsideHColor = $pValue;
        $this->_borderInsideVColor = $pValue;
    }

    /**
     * Get TLRB Border Color
     *
     * @return array
     */
    public function getBorderColor()
    {
        $t = $this->getBorderTopColor();
        $l = $this->getBorderLeftColor();
        $r = $this->getBorderRightColor();
        $b = $this->getBorderBottomColor();
        $h = $this->getBorderInsideHColor();
        $v = $this->getBorderInsideVColor();

        return array($t, $l, $r, $b, $h, $v);
    }

    public function setBorderTopSize($pValue = null)
    {
        $this->_borderTopSize = $pValue;
    }

    public function getBorderTopSize()
    {
        return $this->_borderTopSize;
    }

    public function setBorderTopColor($pValue = null)
    {
        $this->_borderTopColor = $pValue;
    }

    public function getBorderTopColor()
    {
        return $this->_borderTopColor;
    }

    public function setBorderLeftSize($pValue = null)
    {
        $this->_borderLeftSize = $pValue;
    }

    public function getBorderLeftSize()
    {
        return $this->_borderLeftSize;
    }

    public function setBorderLeftColor($pValue = null)
    {
        $this->_borderLeftColor = $pValue;
    }

    public function getBorderLeftColor()
    {
        return $this->_borderLeftColor;
    }

    public function setBorderRightSize($pValue = null)
    {
        $this->_borderRightSize = $pValue;
    }

    public function getBorderRightSize()
    {
        return $this->_borderRightSize;
    }

    public function setBorderRightColor($pValue = null)
    {
        $this->_borderRightColor = $pValue;
    }

    public function getBorderRightColor()
    {
        return $this->_borderRightColor;
    }

    public function setBorderBottomSize($pValue = null)
    {
        $this->_borderBottomSize = $pValue;
    }

    public function getBorderBottomSize()
    {
        return $this->_borderBottomSize;
    }

    public function setBorderBottomColor($pValue = null)
    {
        $this->_borderBottomColor = $pValue;
    }

    public function getBorderBottomColor()
    {
        return $this->_borderBottomColor;
    }

    public function setBorderInsideHColor($pValue = null)
    {
        $this->_borderInsideHColor = $pValue;
    }

    public function getBorderInsideHColor()
    {
        return (isset($this->_borderInsideHColor)) ? $this->_borderInsideHColor : null;
    }

    public function setBorderInsideVColor($pValue = null)
    {
        $this->_borderInsideVColor = $pValue;
    }

    public function getBorderInsideVColor()
    {
        return (isset($this->_borderInsideVColor)) ? $this->_borderInsideVColor : null;
    }

    public function setBorderInsideHSize($pValue = null)
    {
        $this->_borderInsideHSize = $pValue;
    }

    public function getBorderInsideHSize()
    {
        return (isset($this->_borderInsideHSize)) ? $this->_borderInsideHSize : null;
    }

    public function setBorderInsideVSize($pValue = null)
    {
        $this->_borderInsideVSize = $pValue;
    }

    public function getBorderInsideVSize()
    {
        return (isset($this->_borderInsideVSize)) ? $this->_borderInsideVSize : null;
    }

    public function setCellMarginTop($pValue = null)
    {
        $this->_cellMarginTop = $pValue;
    }

    public function getCellMarginTop()
    {
        return $this->_cellMarginTop;
    }

    public function setCellMarginLeft($pValue = null)
    {
        $this->_cellMarginLeft = $pValue;
    }

    public function getCellMarginLeft()
    {
        return $this->_cellMarginLeft;
    }

    public function setCellMarginRight($pValue = null)
    {
        $this->_cellMarginRight = $pValue;
    }

    public function getCellMarginRight()
    {
        return $this->_cellMarginRight;
    }

    public function setCellMarginBottom($pValue = null)
    {
        $this->_cellMarginBottom = $pValue;
    }

    public function getCellMarginBottom()
    {
        return $this->_cellMarginBottom;
    }

    /**
     * Set TLRB cell margin
     *
     * @param   int     $pValue Margin in twips
     */
    public function setCellMargin($pValue = null)
    {
        $this->_cellMarginTop = $pValue;
        $this->_cellMarginLeft = $pValue;
        $this->_cellMarginRight = $pValue;
        $this->_cellMarginBottom = $pValue;
    }

    public function getCellMargin()
    {
        return array($this->_cellMarginTop, $this->_cellMarginLeft, $this->_cellMarginRight, $this->_cellMarginBottom);
    }
}
