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
 * PHPWord_Reader_Abstract
 *
 * @codeCoverageIgnore  Abstract class
 */
abstract class PHPWord_Reader_Abstract implements PHPWord_Reader_IReader
{
    /**
     * Read data only?
     *
     * @var boolean
     */
    protected $readDataOnly = true;

    protected $fileHandle = true;


    /**
     * Read data only?
     *
     * @return  boolean
     */
    public function getReadDataOnly()
    {
        // return $this->readDataOnly;
        return true;
    }

    /**
     * Set read data only
     *
     * @param   boolean $pValue
     * @return  PHPWord_Reader_IReader
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
     * @throws  PHPWord_Exception
     * @return resource
     */
    protected function openFile($pFilename)
    {
        // Check if file exists
        if (!file_exists($pFilename) || !is_readable($pFilename)) {
            throw new PHPWord_Exception("Could not open " . $pFilename . " for reading! File does not exist.");
        }

        // Open file
        $this->fileHandle = fopen($pFilename, 'r');
        if ($this->fileHandle === false) {
            throw new PHPWord_Exception("Could not open file " . $pFilename . " for reading.");
        }
    }

    /**
     * Can the current PHPWord_Reader_IReader read the file?
     *
     * @param   string      $pFilename
     * @return boolean
     * @throws PHPWord_Exception
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
        return $readable;
    }
}
