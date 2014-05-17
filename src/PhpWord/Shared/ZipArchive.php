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

namespace PhpOffice\PhpWord\Shared;

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\Settings;

/**
 * ZipArchive wrapper
 *
 * Wraps zip archive functionality of PHP ZipArchive and PCLZip. PHP ZipArchive
 * properties and methods are bypassed and used as the model for the PCLZip
 * emulation. Only needed PHP ZipArchive features are implemented.
 *
 * @method  bool addFile(string $filename, string $localname = null)
 * @method  bool addFromString(string $localname, string $contents)
 * @method  bool close()
 * @method  bool extractTo(string $destination, mixed $entries = null)
 * @method  bool getFromName(string $name)
 * @method  bool getNameIndex(int $index)
 * @method  bool locateName (string $name)
 * @method  bool open(string $filename, int $flags = null)
 * @since   0.10.0
 */
class ZipArchive
{
    /** @const int Flags for open method */
    const CREATE    = 1; // Emulate \ZipArchive::CREATE
    const OVERWRITE = 8; // Emulate \ZipArchive::OVERWRITE

    /**
     * Number of files (emulate ZipArchive::$numFiles)
     *
     * @var int
     */
    public $numFiles = 0;

    /**
     * Archive filename (emulate ZipArchive::$filename)
     *
     * @var string
     */
    public $filename;

    /**
     * Temporary storage directory
     *
     * @var string
     */
    private $tempDir;

    /**
     * Internal zip archive object
     *
     * @var \ZipArchive|\PclZip
     */
    private $zip;

    /**
     * Use PCLZip (default behaviour)
     *
     * @var bool
     */
    private $usePclzip = true;

    /**
     * Create new instance
     */
    public function __construct()
    {
        $this->usePclzip = (Settings::getZipClass() != 'ZipArchive');
        if ($this->usePclzip) {
            if (!defined('PCLZIP_TEMPORARY_DIR')) {
                define('PCLZIP_TEMPORARY_DIR', sys_get_temp_dir() . '/');
            }
            require_once 'PCLZip/pclzip.lib.php';
        } else {
            $this->zip = new \ZipArchive();
        }
    }

    /**
     * Catch function calls: pass to ZipArchive or PCLZip
     *
     * `call_user_func_array` can only used for public function, hence the `public` in all `pcl...` methods
     *
     * @param mixed $function
     * @param mixed $args
     * @return mixed
     */
    public function __call($function, $args)
    {
        // Set object and function
        $zipFunction = $function;
        if (!$this->usePclzip) {
            $zipObject = $this->zip;
        } else {
            $zipObject = $this;
            $zipFunction = "pclzip{$zipFunction}";
        }

        // Run function
        $result = @call_user_func_array(array($zipObject, $zipFunction), $args);

        return $result;
    }

    /**
     * Close the active archive
     *
     * @return bool
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function close()
    {
        if (!$this->usePclzip) {
            if ($this->zip->close() === false) {
                throw new Exception("Could not close zip file $this->filename.");
            }
        }

        return true;
    }

    /**
     * Extract the archive contents (emulate \ZipArchive)
     *
     * @param string $destination
     * @param string|array $entries
     * @return boolean
     * @since 0.10.0
     */
    public function extractTo($destination, $entries = null)
    {
        if (!is_dir($destination)) {
            return false;
        }

        if (!$this->usePclzip) {
            return $this->zip->extractTo($destination, $entries);
        } else {
            return $this->pclzipExtractTo($destination, $entries);
        }
    }

    /**
     * Open a new zip archive
     *
     * @param string $filename The file name of the ZIP archive to open
     * @param int $flags The mode to use to open the archive
     * @return bool
     */
    public function open($filename, $flags = null)
    {
        $result = true;
        $this->filename = $filename;

        if (!$this->usePclzip) {
            $result = $this->zip->open($this->filename, $flags);
            $this->numFiles = $this->zip->numFiles;
        } else {
            $this->tempDir = sys_get_temp_dir();
            $this->zip = new \PclZip($this->filename);
            $this->numFiles = count($this->zip->listContent());
        }

        return $result;
    }

    /**
     * Extract file from archive by given file name (emulate \ZipArchive)
     *
     * @param  string $filename Filename for the file in zip archive
     * @return string|false $contents File string contents
     */
    public function getFromName($filename)
    {
        if (!$this->usePclzip) {
            $contents = $this->zip->getFromName($filename);
            if ($contents === false) {
                $filename = substr($filename, 1);
                $contents = $this->zip->getFromName($filename);
            }
        } else {
            $contents = $this->pclzipGetFromName($filename);
        }

        return $contents;
    }

    /**
     * Add a new file to the zip archive (emulate \ZipArchive)
     *
     * @param string $filename Directory/Name of the file to add to the zip archive
     * @param string $localname Directory/Name of the file added to the zip
     * @return bool
     */
    public function pclzipAddFile($filename, $localname = null)
    {
        $filename = realpath($filename);
        $filenameParts = pathinfo($filename);
        $localnameParts = pathinfo($localname);

        // To Rename the file while adding it to the zip we
        //   need to create a temp file with the correct name
        if ($filenameParts['basename'] != $localnameParts['basename']) {
            $temppath = $this->tempDir . '/' . $localnameParts['basename'];
            copy($filename, $temppath);
            $filename = $temppath;
            $filenameParts = pathinfo($temppath);
        }

        $pathRemoved = $filenameParts['dirname'];
        $pathAdded = $localnameParts['dirname'];
        $res = $this->zip->add($filename, PCLZIP_OPT_REMOVE_PATH, $pathRemoved, PCLZIP_OPT_ADD_PATH, $pathAdded);

        return ($res == 0) ? false : true;
    }

    /**
     * Add a new file to the zip archive from a string of raw data (emulate \ZipArchive)
     *
     * @param string $localname Directory/Name of the file to add to the zip archive
     * @param string $contents String of data to add to the zip archive
     * @return bool
     */
    public function pclzipAddFromString($localname, $contents)
    {
        // PCLZip emulation
        $filenameParts = pathinfo($localname);

        // Write $contents to a temp file
        $handle = fopen($this->tempDir . '/' . $filenameParts["basename"], "wb");
        fwrite($handle, $contents);
        fclose($handle);

        // Add temp file to zip
        $filename = $this->tempDir . '/' . $filenameParts["basename"];
        $pathRemoved = $this->tempDir;
        $pathAdded = $filenameParts['dirname'];
        $res = $this->zip->add($filename, PCLZIP_OPT_REMOVE_PATH, $pathRemoved, PCLZIP_OPT_ADD_PATH, $pathAdded);

        // Remove temp file
        @unlink($this->tempDir . '/' . $filenameParts["basename"]);

        return ($res == 0) ? false : true;
    }

    /**
     * Extract the archive contents (emulate \ZipArchive)
     *
     * @param string $destination
     * @param string|array $entries
     * @return boolean
     * @since 0.10.0
     */
    public function pclzipExtractTo($destination, $entries = null)
    {
        // Extract all files
        if (is_null($entries)) {
            $result = $this->zip->extract(PCLZIP_OPT_PATH, $destination);
            return ($result > 0) ? true : false;
        }

        // Extract by entries
        if (!is_array($entries)) {
            $entries = array($entries);
        }
        foreach ($entries as $entry) {
            $entryIndex = $this->locateName($entry);
            $result = $this->zip->extractByIndex($entryIndex, PCLZIP_OPT_PATH, $destination);
            if ($result <= 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * Extract file from archive by given file name (emulate \ZipArchive)
     *
     * @param  string $filename Filename for the file in zip archive
     * @return string|false $contents File string contents
     */
    public function pclzipGetFromName($filename)
    {
        $listIndex = $this->locateName($filename);
        $contents = false;

        if ($listIndex !== false) {
            $extracted = $this->zip->extractByIndex($listIndex, PCLZIP_OPT_EXTRACT_AS_STRING);
        } else {
            $filename = substr($filename, 1);
            $listIndex = $this->locateName($filename);
            $extracted = $this->zip->extractByIndex($listIndex, PCLZIP_OPT_EXTRACT_AS_STRING);
        }
        if ((is_array($extracted)) && ($extracted != 0)) {
            $contents = $extracted[0]["content"];
        }

        return $contents;
    }

    /**
     * Returns the name of an entry using its index (emulate \ZipArchive)
     *
     * @param integer $index
     * @return string|false
     * @since 0.10.0
     */
    public function pclzipGetNameIndex($index)
    {
        $list = $this->zip->listContent();
        if (isset($list[$index])) {
            return $list[$index]['filename'];
        } else {
            return false;
        }
    }

    /**
     * Returns the index of the entry in the archive (emulate \ZipArchive)
     *
     * @param string $filename Filename for the file in zip archive
     * @return integer|false
     */
    public function pclzipLocateName($filename)
    {
        $list = $this->zip->listContent();
        $listCount = count($list);
        $listIndex = -1;
        for ($i = 0; $i < $listCount; ++$i) {
            if (strtolower($list[$i]["filename"]) == strtolower($filename) ||
                strtolower($list[$i]["stored_filename"]) == strtolower($filename)) {
                $listIndex = $i;
                break;
            }
        }

        return ($listIndex > -1) ? $listIndex : false;
    }
}
