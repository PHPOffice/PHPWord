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

/**
 * Table style
 */
class Table
{
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
     * Create new table style
     */
    public function __construct()
    {
        $this->_cellMarginTop = null;
        $this->_cellMarginLeft = null;
        $this->_cellMarginRight = null;
        $this->_cellMarginBottom = null;
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
