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
 * @copyright  Copyright (c) 2013 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    0.8.0
 */

/**
 * PHPWord_Section_Table_Row
 */
class PHPWord_Section_Table_Row
{

    /**
     * Row height
     *
     * @var int
     */
    private $_height = null;

    /**
     * Row style
     *
     * @var PHPWord_Style_Row
     */
    private $_style;

    /**
     * Row cells
     *
     * @var array
     */
    private $_cells = array();

    /**
     * Table holder
     *
     * @var string
     */
    private $_insideOf;

    /**
     * Section/Header/Footer count
     *
     * @var int
     */
    private $_pCount;


    /**
     * Create a new table row
     *
     * @param string $insideOf
     * @param int $pCount
     * @param int $height
     * @param mixed $style
     */
    public function __construct($insideOf, $pCount, $height = null, $style = null)
    {
        $this->_insideOf = $insideOf;
        $this->_pCount = $pCount;
        $this->_height = $height;
        $this->_style = new PHPWord_Style_Row();

        if (!is_null($style)) {
            if (is_array($style)) {

                foreach ($style as $key => $value) {
                    if (substr($key, 0, 1) != '_') {
                        $key = '_' . $key;
                    }
                    $this->_style->setStyleValue($key, $value);
                }
            }
        }
    }

    /**
     * Add a cell
     *
     * @param int $width
     * @param mixed $style
     * @return PHPWord_Section_Table_Cell
     */
    public function addCell($width = null, $style = null)
    {
        $cell = new PHPWord_Section_Table_Cell($this->_insideOf, $this->_pCount, $width, $style);
        $this->_cells[] = $cell;
        return $cell;
    }

    /**
     * Get all cells
     *
     * @return array
     */
    public function getCells()
    {
        return $this->_cells;
    }

    /**
     * Get row style
     *
     * @return PHPWord_Style_Row
     */
    public function getStyle()
    {
        return $this->_style;
    }

    /**
     * Get row height
     *
     * @return int
     */
    public function getHeight()
    {
        return $this->_height;
    }
}
