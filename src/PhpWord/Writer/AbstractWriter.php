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
use PhpOffice\PhpWord\Settings;

/**
 * Abstract writer class
 *
 * @since 0.10.0
 */
abstract class AbstractWriter implements WriterInterface
{
    /**
     * PHPWord object
     *
     * @var \PhpOffice\PhpWord\PhpWord
     */
    protected $phpWord = null;

    /**
     * Individual writers
     *
     * @var array
     */
    protected $writerParts = array();

    /**
     * Paths to store media files
     *
     * @var array
     */
    protected $mediaPaths = array('image' => '', 'object' => '');

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
     * Temporary directory
     *
     * @var string
     */
    private $tempDir = '';

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
     * @return \PhpOffice\PhpWord\PhpWord
     * @throws \PhpOffice\PhpWord\Exception\Exception
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
     * @param \PhpOffice\PhpWord\PhpWord
     * @return self
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
     * @return self
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
     * Get temporary directory
     *
     * @return string
     */
    public function getTempDir()
    {
        return $this->tempDir;
    }

    /**
     * Set temporary directory
     *
     * @param string $value
     * @return self
     */
    public function setTempDir($value)
    {
        if (!is_dir($value)) {
            mkdir($value);
        }
        $this->tempDir = $value;

        return $this;
    }

    /**
     * Get temporary file name
     *
     * If $filename is php://output or php://stdout, make it a temporary file
     *
     * @param string $filename
     * @return string
     */
    protected function getTempFile($filename)
    {
        // Temporary directory
        $this->setTempDir(sys_get_temp_dir() . '/PHPWordWriter/');

        // Temporary file
        $this->originalFilename = $filename;
        if (strtolower($filename) == 'php://output' || strtolower($filename) == 'php://stdout') {
            $filename = @tempnam(sys_get_temp_dir(), 'phpword_');
            if ($filename == '') {
                $filename = $this->originalFilename;
            }
        }
        $this->tempFilename = $filename;

        return $this->tempFilename;
    }

    /**
     * Cleanup temporary file
     */
    protected function cleanupTempFile()
    {
        if ($this->originalFilename != $this->tempFilename) {
            if (copy($this->tempFilename, $this->originalFilename) === false) {
                throw new Exception("Could not copy temporary zip file {$this->tempFilename} to {$this->originalFilename}.");
            }
            @unlink($this->tempFilename);
        }

        $this->clearTempDir();
    }

    /**
     * Clear temporary directory
     */
    protected function clearTempDir()
    {
        if (is_dir($this->tempDir)) {
            $this->deleteDir($this->tempDir);
        }
    }

    /**
     * Get ZipArchive object
     *
     * @param string $filename
     * @return mixed ZipArchive object
     */
    protected function getZipArchive($filename)
    {
        // Create new ZIP file and open it for writing
        $zipClass = Settings::getZipClass();
        $objZip = new $zipClass();

        // Retrieve OVERWRITE and CREATE constants from the instantiated zip class
        // This method of accessing constant values from a dynamic class should work with all appropriate versions of PHP
        $reflection = new \ReflectionObject($objZip);
        $zipOverWrite = $reflection->getConstant('OVERWRITE');
        $zipCreate = $reflection->getConstant('CREATE');

        // Remove any existing file
        if (file_exists($filename)) {
            unlink($filename);
        }

        // Try opening the ZIP file
        if ($objZip->open($filename, $zipOverWrite) !== true) {
            if ($objZip->open($filename, $zipCreate) !== true) {
                throw new Exception("Could not open " . $filename . " for writing.");
            }
        }

        return $objZip;
    }

    /**
     * Add files to package
     *
     * @param mixed $objZip
     * @param mixed $elements
     */
    protected function addFilesToPackage($objZip, $elements)
    {
        foreach ($elements as $element) {
            $type = $element['type']; // image|object|link

            // Skip nonregistered types and set target
            if (!array_key_exists($type, $this->mediaPaths)) {
                continue;
            }
            $target = $this->mediaPaths[$type] . $element['target'];

            // Retrive GD image content or get local media
            if (isset($element['isMemImage']) && $element['isMemImage']) {
                $image = call_user_func($element['createFunction'], $element['source']);
                ob_start();
                call_user_func($element['imageFunction'], $image);
                $imageContents = ob_get_contents();
                ob_end_clean();
                $objZip->addFromString($target, $imageContents);
                imagedestroy($image);
            } else {
                $this->addFileToPackage($objZip, $element['source'], $target);
            }
        }
    }

    /**
     * Add file to package
     *
     * Get the actual source from an archive image
     *
     * @param mixed $objZip
     * @param string $source
     * @param string $target
     */
    protected function addFileToPackage($objZip, $source, $target)
    {
        $isArchive = strpos($source, 'zip://') !== false;
        $actualSource = null;
        if ($isArchive) {
            $source = substr($source, 6);
            list($zipFilename, $imageFilename) = explode('#', $source);

            $zipClass = \PhpOffice\PhpWord\Settings::getZipClass();
            $zip = new $zipClass();
            if ($zip->open($zipFilename) !== false) {
                if ($zip->locateName($imageFilename)) {
                    $zip->extractTo($this->getTempDir(), $imageFilename);
                    $actualSource = $this->getTempDir() . DIRECTORY_SEPARATOR . $imageFilename;
                }
            }
            $zip->close();
        } else {
            $actualSource = $source;
        }

        if (!is_null($actualSource)) {
            $objZip->addFile($actualSource, $target);
        }
    }

    /**
     * Delete directory
     *
     * @param string $dir
     */
    private function deleteDir($dir)
    {
        foreach (scandir($dir) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            } elseif (is_file($dir . "/" . $file)) {
                unlink($dir . "/" . $file);
            } elseif (is_dir($dir . "/" . $file)) {
                $this->deleteDir($dir . "/" . $file);
            }
        }

        rmdir($dir);
    }
}
