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

/**
 * Class PHPWord_Style_Table
 */
class PHPWord_Style_Table
{
    /**
     * Cell margin top
     *
     * @var int
     */
    private $_cellMarginTop;

    /**
     * Cell margin left
     *
     * @var int
     */
    private $_cellMarginLeft;

    /**
     * Cell margin right
     *
     * @var int
     */
    private $_cellMarginRight;

    /**
     * Cell margin bottom
     *
     * @var int
     */
    private $_cellMarginBottom;

    /**
     * Constructor
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
     */
    public function getCellMarginBottom()
    {
        return $this->_cellMarginBottom;
    }

    /**
     * Get cell margin
     */
    public function getCellMargin()
    {
        return array($this->_cellMarginTop, $this->_cellMarginLeft, $this->_cellMarginRight, $this->_cellMarginBottom);
    }
}
