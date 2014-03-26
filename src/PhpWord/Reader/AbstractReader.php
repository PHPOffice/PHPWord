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

namespace PhpOffice\PhpWord\Reader;

use PhpOffice\PhpWord\Exceptions\Exception;

/**
 * Reader abstract class
 *
 * @codeCoverageIgnore Abstract class
 */
abstract class AbstractReader implements IReader
{
    /**
     * Read data only?
     *
     * @var bool
     */
    protected $readDataOnly = true;

    /**
     * File pointer
     *
     * @var bool|resource
     */
    protected $fileHandle = true;

    /**
     * Read data only?
     *
     * @return bool
     */
    public function getReadDataOnly()
    {
        // return $this->readDataOnly;
        return true;
    }

    /**
     * Set read data only
     *
     * @param bool $pValue
     * @return \PhpOffice\PhpWord\Reader\IReader
     */
    public function setReadDataOnly($pValue = true)
    {
        $this->readDataOnly = $pValue;
        return $this;
    }

    /**
     * Open file for reading
     *
     * @param string $pFilename
     * @return resource
     * @throws \PhpOffice\PhpWord\Exceptions\Exception
     */
    protected function openFile($pFilename)
    {
        // Check if file exists
        if (!\file_exists($pFilename) || !is_readable($pFilename)) {
            throw new Exception("Could not open " . $pFilename . " for reading! File does not exist.");
        }

        // Open file
        $this->fileHandle = fopen($pFilename, 'r');
        if ($this->fileHandle === false) {
            throw new Exception("Could not open file " . $pFilename . " for reading.");
        }
    }

    /**
     * Can the current IReader read the file?
     *
     * @param string $pFilename
     * @return bool
     */
    public function canRead($pFilename)
    {
        // Check if file exists
        try {
            $this->openFile($pFilename);
        } catch (Exception $e) {
            return false;
        }
        fclose($this->fileHandle);
        return true;
    }
}
