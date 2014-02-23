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
 * Class PHPWord_Style_Table
 */
class PHPWord_Style_Table
{
    const WIDTH_TYPE_NIL = 'nil'; // No Width
    const WIDTH_TYPE_PERCENT = 'pct'; // Width in Fiftieths of a Percent
    const WIDTH_TYPE_POINT = 'dxa'; // Width in Twentieths of a Point
    const WIDTH_TYPE_AUTO = 'auto'; // Automatically Determined Width

    private $_cellMarginTop;
    private $_cellMarginLeft;
    private $_cellMarginRight;
    private $_cellMarginBottom;

    public function __construct()
    {
        $this->_cellMarginTop = null;
        $this->_cellMarginLeft = null;
        $this->_cellMarginRight = null;
        $this->_cellMarginBottom = null;
    }

    public function setStyleValue($key, $value)
    {
        $this->$key = $value;
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

    public function getCellMargin()
    {
        return array($this->_cellMarginTop, $this->_cellMarginLeft, $this->_cellMarginRight, $this->_cellMarginBottom);
    }
}
