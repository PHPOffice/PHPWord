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

namespace PhpOffice\PhpWord\Writer\ODText;

use PhpOffice\PhpWord\Exceptions\Exception;
use PhpOffice\PhpWord\Writer\IWriter;

/**
 * ODText writer part abstract
 */
abstract class WriterPart
{
    /**
     * Parent IWriter object
     *
     * @var \PhpOffice\PhpWord\Writer\IWriter
     */
    private $_parentWriter;

    /**
     * Set parent IWriter object
     *
     * @param \PhpOffice\PhpWord\Writer\IWriter $pWriter
     */
    public function setParentWriter(IWriter $pWriter = null)
    {
        $this->_parentWriter = $pWriter;
    }

    /**
     * Get parent IWriter object
     *
     * @return \PhpOffice\PhpWord\Writer\IWriter
     * @throws \PhpOffice\PhpWord\Exceptions\Exception
     */
    public function getParentWriter()
    {
        if (!is_null($this->_parentWriter)) {
            return $this->_parentWriter;
        } else {
            throw new Exception("No parent IWriter assigned.");
        }
    }
}
