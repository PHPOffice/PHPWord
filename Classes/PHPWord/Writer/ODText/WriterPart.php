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
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    0.7.0
 */

/**
 * Class PHPWord_Writer_ODText_WriterPart
 */
abstract class PHPWord_Writer_ODText_WriterPart
{
    /**
     * Parent IWriter object
     *
     * @var PHPWord_Writer_IWriter
     */
    private $_parentWriter;

    /**
     * Set parent IWriter object
     *
     * @param PHPWord_Writer_IWriter $pWriter
     * @throws Exception
     */
    public function setParentWriter(PHPWord_Writer_IWriter $pWriter = null)
    {
        $this->_parentWriter = $pWriter;
    }

    /**
     * Get parent IWriter object
     *
     * @return PHPWord_Writer_IWriter
     * @throws Exception
     */
    public function getParentWriter()
    {
        if (!is_null($this->_parentWriter)) {
            return $this->_parentWriter;
        } else {
            throw new Exception("No parent PHPWord_Writer_IWriter assigned.");
        }
    }
}
