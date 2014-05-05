<?php
/**
 * This file is part of PHPWord - A pure PHP library for reading and writing
 * word processing documents.
 *
 * PHPWord is free software distributed under the terms of the GNU Lesser
 * General Public License version 3 as published by the Free Software Foundation.
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code. For the full list of
 * contributors, visit https://github.com/PHPOffice/PHPWord/contributors.
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2014 PHPWord contributors
 * @license     http://www.gnu.org/licenses/lgpl.txt LGPL version 3
 */

namespace PhpOffice\PhpWord\Reader;

use PhpOffice\PhpWord\Exception\Exception;

/**
 * Reader abstract class
 *
 * @codeCoverageIgnore Abstract class
 */
abstract class AbstractReader implements ReaderInterface
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
    public function isReadDataOnly()
    {
        // return $this->readDataOnly;
        return true;
    }

    /**
     * Set read data only
     *
     * @param bool $pValue
     * @return self
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
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    protected function openFile($pFilename)
    {
        // Check if file exists
        if (!file_exists($pFilename) || !is_readable($pFilename)) {
            throw new Exception("Could not open " . $pFilename . " for reading! File does not exist.");
        }

        // Open file
        $this->fileHandle = fopen($pFilename, 'r');
        if ($this->fileHandle === false) {
            throw new Exception("Could not open file " . $pFilename . " for reading.");
        }
    }

    /**
     * Can the current ReaderInterface read the file?
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
        if (is_resource($this->fileHandle)) {
            fclose($this->fileHandle);
        }

        return true;
    }

    /**
     * Read data only?
     *
     * @deprecated 0.10.0
     * @codeCoverageIgnore
     */
    public function getReadDataOnly()
    {
        return $this->isReadDataOnly();
    }
}
