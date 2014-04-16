<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
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
    public function getReadDataOnly()
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
}
