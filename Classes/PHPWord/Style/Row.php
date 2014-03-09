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
 * @version    0.7.0
 */

/**
 * PHPWord_Style_Row
 */
class PHPWord_Style_Row
{

    /**
     * Repeat table row on every new page
     *
     * @var bool
     */
    private $_tblHeader;

    /**
     * Table row cannot break across pages
     *
     * @var bool
     */
    private $_cantSplit;

    /**
     * Create a new row style
     */
    public function __construct()
    {
        $this->_tblHeader = null;
        $this->_cantSplit = null;
    }

    /**
     * Set style value
     */
    public function setStyleValue($key, $value)
    {
        $this->$key = $value;
    }

    public function setTblHeader($pValue = null)
    {
        $this->_tblHeader = $pValue;
    }

    public function getTblHeader()
    {
        return $this->_tblHeader ? 1 : 0;
    }

    public function setCantSplit($pValue = null)
    {
        $this->_cantSplit = $pValue;
    }

    public function getCantSplit()
    {
        return $this->_cantSplit ? 1 : 0;
    }

}