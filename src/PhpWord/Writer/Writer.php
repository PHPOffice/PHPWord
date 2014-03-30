<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Writer;

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\PhpWord;

/**
 * Abstract writer class
 *
 * @since 0.9.2
 */
abstract class Writer implements IWriter
{
    /**
     * PHPWord object
     *
     * @var PhpWord
     */
    protected $phpWord = null;

    /**
     * Individual writers
     *
     * @var mixed
     */
    protected $writerParts = array();

    /**
     * Use disk caching
     *
     * @var boolean
     */
    private $useDiskCaching = false;

    /**
     * Disk caching directory
     *
     * @var string
     */
    private $diskCachingDirectory = './';

    /**
     * Original file name
     *
     * @var string
     */
    private $originalFilename;

    /**
     * Temporary file name
     *
     * @var string
     */
    private $tempFilename;

    /**
     * Get PhpWord object
     *
     * @return PhpWord
     * @throws Exception
     */
    public function getPhpWord()
    {
        if (!is_null($this->phpWord)) {
            return $this->phpWord;
        } else {
            throw new Exception("No PhpWord assigned.");
        }
    }

    /**
     * Set PhpWord object
     *
     * @param PhpWord
     * @return $this
     */
    public function setPhpWord(PhpWord $phpWord = null)
    {
        $this->phpWord = $phpWord;
        return $this;
    }

    /**
     * Get writer part
     *
     * @param string $pPartName Writer part name
     * @return mixed
     */
    public function getWriterPart($pPartName = '')
    {
        if ($pPartName != '' && isset($this->writerParts[strtolower($pPartName)])) {
            return $this->writerParts[strtolower($pPartName)];
        } else {
            return null;
        }
    }

    /**
     * Get use disk caching status
     *
     * @return boolean
     */
    public function getUseDiskCaching()
    {
        return $this->useDiskCaching;
    }

    /**
     * Set use disk caching status
     *
     * @param boolean $pValue
     * @param string $pDirectory
     * @return $this
     */
    public function setUseDiskCaching($pValue = false, $pDirectory = null)
    {
        $this->useDiskCaching = $pValue;

        if (!is_null($pDirectory)) {
            if (is_dir($pDirectory)) {
                $this->diskCachingDirectory = $pDirectory;
            } else {
                throw new Exception("Directory does not exist: $pDirectory");
            }
        }

        return $this;
    }

    /**
     * Get disk caching directory
     *
     * @return string
     */
    public function getDiskCachingDirectory()
    {
        return $this->diskCachingDirectory;
    }

    /**
     * Get temporary file name
     *
     * If $pFilename is php://output or php://stdout, make it a temporary file
     *
     * @param string $pFilename
     * @return string
     */
    protected function getTempFile($pFilename)
    {
        $this->originalFilename = $pFilename;
        if (strtolower($pFilename) == 'php://output' || strtolower($pFilename) == 'php://stdout') {
            $pFilename = @tempnam(sys_get_temp_dir(), 'phpword_');
            if ($pFilename == '') {
                $pFilename = $this->originalFilename;
            }
        }
        $this->tempFilename = $pFilename;

        return $this->tempFilename;
    }

    /**
     * Cleanup temporary file
     *
     * If a temporary file was used, copy it to the correct file stream
     */
    protected function cleanupTempFile()
    {
        if ($this->originalFilename != $this->tempFilename) {
            if (copy($this->tempFilename, $this->originalFilename) === false) {
                throw new Exception("Could not copy temporary zip file {$this->tempFilename} to {$this->originalFilename}.");
            }
            @unlink($this->tempFilename);
        }
    }
}
