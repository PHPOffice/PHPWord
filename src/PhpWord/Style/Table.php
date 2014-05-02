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
 * Table style
 */
class Table
{
    /**
     * Style for first row
     *
     * @var \PhpOffice\PhpWord\Style\Table
     */
    private $_firstRow = null;

    /**
     * Cell margin top
     *
     * @var int
     */
    private $_cellMarginTop = null;

    /**
     * Cell margin left
     *
     * @var int
     */
    private $_cellMarginLeft = null;

    /**
     * Cell margin right
     *
     * @var int
     */
    private $_cellMarginRight = null;

    /**
     * Cell margin bottom
     *
     * @var int
     */
    private $_cellMarginBottom = null;

    /**
     * Background color
     *
     * @var string
     */
    private $_bgColor;

    /**
     * Border size top
     *
     * @var int
     */
    private $_borderTopSize;

    /**
     * Border color
     *
     * @var string top
     */
    private $_borderTopColor;

    /**
     * Border size left
     *
     * @var int
     */
    private $_borderLeftSize;

    /**
     * Border color left
     *
     * @var string
     */
    private $_borderLeftColor;

    /**
     * Border size right
     *
     * @var int
     */
    private $_borderRightSize;

    /**
     * Border color right
     *
     * @var string
     */
    private $_borderRightColor;

    /**
     * Border size bottom
     *
     * @var int
     */
    private $_borderBottomSize;

    /**
     * Border color bottom
     *
     * @var string
     */
    private $_borderBottomColor;

    /**
     * Border size inside horizontal
     *
     * @var int
     */
    private $_borderInsideHSize;

    /**
     * Border color inside horizontal
     *
     * @var string
     */
    private $_borderInsideHColor;

    /**
     * Border size inside vertical
     *
     * @var int
     */
    private $_borderInsideVSize;

    /**
     * Border color inside vertical
     *
     * @var string
     */
    private $_borderInsideVColor;

    /**
     * Create new table style
     *
     * @param mixed $styleTable
     * @param mixed $styleFirstRow
     */
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
        } elseif ($key == '_cellMargin') {
            $this->setCellMargin($value);
        } else {
            $this->$key = $value;
        }
    }

    /**
     * Get First Row Style
     *
     * @return \PhpOffice\PhpWord\Style\Table
     */
    public function getFirstRow()
    {
        return $this->_firstRow;
    }

    /**
     * Get Last Row Style
     *
     * @return \PhpOffice\PhpWord\Style\Table
     */
    public function getLastRow()
    {
        return $this->_lastRow;
    }

    /**
     * Get background
     *
     * @return \PhpOffice\PhpWord\Style\Table
     */
    public function getBgColor()
    {
        return $this->_bgColor;
    }

    /**
     * Set background
     *
     * @param string $pValue
     * @return \PhpOffice\PhpWord\Style\Table
     */
    public function setBgColor($pValue = null)
    {
        $this->_bgColor = $pValue;
    }

    /**
     * Set TLRBVH Border Size
     *
     * @param int $pValue Border size in eighths of a point (1/8 point)
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
     * @param string $pValue
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

    /**
     * Set border size top
     *
     * @param $pValue
     */
    public function setBorderTopSize($pValue = null)
    {
        $this->_borderTopSize = $pValue;
    }

    /**
     * Get border size top
     *
     * @return
     */
    public function getBorderTopSize()
    {
        return $this->_borderTopSize;
    }

    /**
     * Set border color top
     *
     * @param $pValue
     */
    public function setBorderTopColor($pValue = null)
    {
        $this->_borderTopColor = $pValue;
    }

    /**
     * Get border color top
     *
     * @return
     */
    public function getBorderTopColor()
    {
        return $this->_borderTopColor;
    }

    /**
     * Set border size left
     *
     * @param $pValue
     */
    public function setBorderLeftSize($pValue = null)
    {
        $this->_borderLeftSize = $pValue;
    }

    /**
     * Get border size left
     *
     * @return
     */
    public function getBorderLeftSize()
    {
        return $this->_borderLeftSize;
    }

    /**
     * Set border color left
     *
     * @param $pValue
     */
    public function setBorderLeftColor($pValue = null)
    {
        $this->_borderLeftColor = $pValue;
    }

    /**
     * Get border color left
     *
     * @return
     */
    public function getBorderLeftColor()
    {
        return $this->_borderLeftColor;
    }

    /**
     * Set border size right
     *
     * @param $pValue
     */
    public function setBorderRightSize($pValue = null)
    {
        $this->_borderRightSize = $pValue;
    }

    /**
     * Get border size right
     *
     * @return
     */
    public function getBorderRightSize()
    {
        return $this->_borderRightSize;
    }

    /**
     * Set border color right
     *
     * @param $pValue
     */
    public function setBorderRightColor($pValue = null)
    {
        $this->_borderRightColor = $pValue;
    }

    /**
     * Get border color right
     *
     * @return
     */
    public function getBorderRightColor()
    {
        return $this->_borderRightColor;
    }

    /**
     * Set border size bottom
     *
     * @param $pValue
     */
    public function setBorderBottomSize($pValue = null)
    {
        $this->_borderBottomSize = $pValue;
    }

    /**
     * Get border size bottom
     *
     * @return
     */
    public function getBorderBottomSize()
    {
        return $this->_borderBottomSize;
    }

    /**
     * Set border color bottom
     *
     * @param $pValue
     */
    public function setBorderBottomColor($pValue = null)
    {
        $this->_borderBottomColor = $pValue;
    }

    /**
     * Get border color bottom
     *
     * @return
     */
    public function getBorderBottomColor()
    {
        return $this->_borderBottomColor;
    }

    /**
     * Set border color inside horizontal
     *
     * @param $pValue
     */
    public function setBorderInsideHColor($pValue = null)
    {
        $this->_borderInsideHColor = $pValue;
    }

    /**
     * Get border color inside horizontal
     *
     * @return
     */
    public function getBorderInsideHColor()
    {
        return (isset($this->_borderInsideHColor)) ? $this->_borderInsideHColor : null;
    }

    /**
     * Set border color inside vertical
     *
     * @param $pValue
     */
    public function setBorderInsideVColor($pValue = null)
    {
        $this->_borderInsideVColor = $pValue;
    }

    /**
     * Get border color inside vertical
     *
     * @return
     */
    public function getBorderInsideVColor()
    {
        return (isset($this->_borderInsideVColor)) ? $this->_borderInsideVColor : null;
    }

    /**
     * Set border size inside horizontal
     *
     * @param $pValue
     */
    public function setBorderInsideHSize($pValue = null)
    {
        $this->_borderInsideHSize = $pValue;
    }

    /**
     * Get border size inside horizontal
     *
     * @return
     */
    public function getBorderInsideHSize()
    {
        return (isset($this->_borderInsideHSize)) ? $this->_borderInsideHSize : null;
    }

    /**
     * Set border size inside vertical
     *
     * @param $pValue
     */
    public function setBorderInsideVSize($pValue = null)
    {
        $this->_borderInsideVSize = $pValue;
    }

    /**
     * Get border size inside vertical
     *
     * @return
     */
    public function getBorderInsideVSize()
    {
        return (isset($this->_borderInsideVSize)) ? $this->_borderInsideVSize : null;
    }

    /**
     * Set cell margin top
     *
     * @param int $pValue
     */
    public function setCellMarginTop($pValue = null)
    {
        $this->_cellMarginTop = $pValue;
    }

    /**
     * Get cell margin top
     *
     * @return int
     */
    public function getCellMarginTop()
    {
        return $this->_cellMarginTop;
    }

    /**
     * Set cell margin left
     *
     * @param int $pValue
     */
    public function setCellMarginLeft($pValue = null)
    {
        $this->_cellMarginLeft = $pValue;
    }

    /**
     * Get cell margin left
     *
     * @return int
     */
    public function getCellMarginLeft()
    {
        return $this->_cellMarginLeft;
    }

    /**
     * Set cell margin right
     *
     * @param int $pValue
     */
    public function setCellMarginRight($pValue = null)
    {
        $this->_cellMarginRight = $pValue;
    }

    /**
     * Get cell margin right
     *
     * @return int
     */
    public function getCellMarginRight()
    {
        return $this->_cellMarginRight;
    }

    /**
     * Set cell margin bottom
     *
     * @param int $pValue
     */
    public function setCellMarginBottom($pValue = null)
    {
        $this->_cellMarginBottom = $pValue;
    }

    /**
     * Get cell margin bottom
     *
     * @return int
     */
    public function getCellMarginBottom()
    {
        return $this->_cellMarginBottom;
    }

    /**
     * Set TLRB cell margin
     *
     * @param int $pValue Margin in twips
     */
    public function setCellMargin($pValue = null)
    {
        $this->_cellMarginTop = $pValue;
        $this->_cellMarginLeft = $pValue;
        $this->_cellMarginRight = $pValue;
        $this->_cellMarginBottom = $pValue;
    }

    /**
     * Get cell margin
     *
     * @return array
     */
    public function getCellMargin()
    {
        return array($this->_cellMarginTop, $this->_cellMarginLeft, $this->_cellMarginRight, $this->_cellMarginBottom);
    }
}
