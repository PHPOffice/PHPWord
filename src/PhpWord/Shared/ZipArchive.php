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
 * @see         https://github.com/PHPOffice/PHPWord
 * @copyright   2010-2017 PHPWord contributors
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
 * @method  string getNameIndex(int $index)
 * @method  int locateName(string $name)
 *
 * @since   0.10.0
 */
class ZipArchive
{
    /** @const int Flags for open method */
    const CREATE = 1; // Emulate \ZipArchive::CREATE
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
                define('PCLZIP_TEMPORARY_DIR', Settings::getTempDir() . '/');
            }
            require_once 'PCLZip/pclzip.lib.php';
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
        $result = false;
        if (method_exists($zipObject, $zipFunction)) {
            $result = @call_user_func_array(array($zipObject, $zipFunction), $args);
        }

        return $result;
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
            $zip = new \ZipArchive();
            $result = $zip->open($this->filename, $flags);

            // Scrutizer will report the property numFiles does not exist
            // See https://github.com/scrutinizer-ci/php-analyzer/issues/190
            $this->numFiles = $zip->numFiles;
        } else {
            $zip = new \PclZip($this->filename);
            $this->tempDir = Settings::getTempDir();
            $zipContent = $zip->listContent();
            $this->numFiles = is_array($zipContent) ? count($zipContent) : 0;
        }
        $this->zip = $zip;

        return $result;
    }

    /**
     * Close the active archive
     *
     * @throws \PhpOffice\PhpWord\Exception\Exception
     *
     * @return bool
     *
     * @codeCoverageIgnore Can't find any test case. Uncomment when found.
     */
    public function close()
    {
        if (!$this->usePclzip) {
            if ($this->zip->close() === false) {
                throw new Exception("Could not close zip file {$this->filename}: ");
            }
        }

        return true;
    }

    /**
     * Extract the archive contents (emulate \ZipArchive)
     *
     * @param string $destination
     * @param string|array $entries
     * @return bool
     * @since 0.10.0
     */
    public function extractTo($destination, $entries = null)
    {
        if (!is_dir($destination)) {
            return false;
        }

        if (!$this->usePclzip) {
            return $this->zip->extractTo($destination, $entries);
        }

        return $this->pclzipExtractTo($destination, $entries);
    }

    /**
     * Extract file from archive by given file name (emulate \ZipArchive)
     *
     * @param  string $filename Filename for the file in zip archive
     * @return string $contents File string contents
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
        /** @var \PclZip $zip Type hint */
        $zip = $this->zip;

        // Bugfix GH-261 https://github.com/PHPOffice/PHPWord/pull/261
        $realpathFilename = realpath($filename);
        if ($realpathFilename !== false) {
            $filename = $realpathFilename;
        }

        $filenameParts = pathinfo($filename);
        $localnameParts = pathinfo($localname);

        // To Rename the file while adding it to the zip we
        //   need to create a temp file with the correct name
        $tempFile = false;
        if ($filenameParts['basename'] != $localnameParts['basename']) {
            $tempFile = true; // temp file created
            $temppath = $this->tempDir . DIRECTORY_SEPARATOR . $localnameParts['basename'];
            copy($filename, $temppath);
            $filename = $temppath;
            $filenameParts = pathinfo($temppath);
        }

        $pathRemoved = $filenameParts['dirname'];
        $pathAdded = $localnameParts['dirname'];

        $res = $zip->add($filename, PCLZIP_OPT_REMOVE_PATH, $pathRemoved, PCLZIP_OPT_ADD_PATH, $pathAdded);

        if ($tempFile) {
            // Remove temp file, if created
            unlink($this->tempDir . DIRECTORY_SEPARATOR . $localnameParts['basename']);
        }

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
        /** @var \PclZip $zip Type hint */
        $zip = $this->zip;
        $filenameParts = pathinfo($localname);

        // Write $contents to a temp file
        $handle = fopen($this->tempDir . DIRECTORY_SEPARATOR . $filenameParts['basename'], 'wb');
        fwrite($handle, $contents);
        fclose($handle);

        // Add temp file to zip
        $filename = $this->tempDir . DIRECTORY_SEPARATOR . $filenameParts['basename'];
        $pathRemoved = $this->tempDir;
        $pathAdded = $filenameParts['dirname'];

        $res = $zip->add($filename, PCLZIP_OPT_REMOVE_PATH, $pathRemoved, PCLZIP_OPT_ADD_PATH, $pathAdded);

        // Remove temp file
        @unlink($this->tempDir . DIRECTORY_SEPARATOR . $filenameParts['basename']);

        return ($res == 0) ? false : true;
    }

    /**
     * Extract the archive contents (emulate \ZipArchive)
     *
     * @param string $destination
     * @param string|array $entries
     * @return bool
     * @since 0.10.0
     */
    public function pclzipExtractTo($destination, $entries = null)
    {
        /** @var \PclZip $zip Type hint */
        $zip = $this->zip;

        // Extract all files
        if (is_null($entries)) {
            $result = $zip->extract(PCLZIP_OPT_PATH, $destination);

            return ($result > 0) ? true : false;
        }

        // Extract by entries
        if (!is_array($entries)) {
            $entries = array($entries);
        }
        foreach ($entries as $entry) {
            $entryIndex = $this->locateName($entry);
            $result = $zip->extractByIndex($entryIndex, PCLZIP_OPT_PATH, $destination);
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
     * @return string $contents File string contents
     */
    public function pclzipGetFromName($filename)
    {
        /** @var \PclZip $zip Type hint */
        $zip = $this->zip;
        $listIndex = $this->pclzipLocateName($filename);
        $contents = false;

        if ($listIndex !== false) {
            $extracted = $zip->extractByIndex($listIndex, PCLZIP_OPT_EXTRACT_AS_STRING);
        } else {
            $filename = substr($filename, 1);
            $listIndex = $this->pclzipLocateName($filename);
            $extracted = $zip->extractByIndex($listIndex, PCLZIP_OPT_EXTRACT_AS_STRING);
        }
        if ((is_array($extracted)) && ($extracted != 0)) {
            $contents = $extracted[0]['content'];
        }

        return $contents;
    }

    /**
     * Returns the name of an entry using its index (emulate \ZipArchive)
     *
     * @param int $index
     * @return string|bool
     * @since 0.10.0
     */
    public function pclzipGetNameIndex($index)
    {
        /** @var \PclZip $zip Type hint */
        $zip = $this->zip;
        $list = $zip->listContent();
        if (isset($list[$index])) {
            return $list[$index]['filename'];
        }

        return false;
    }

    /**
     * Returns the index of the entry in the archive (emulate \ZipArchive)
     *
     * @param string $filename Filename for the file in zip archive
     * @return int
     */
    public function pclzipLocateName($filename)
    {
        /** @var \PclZip $zip Type hint */
        $zip = $this->zip;
        $list = $zip->listContent();
        $listCount = count($list);
        $listIndex = -1;
        for ($i = 0; $i < $listCount; ++$i) {
            if (strtolower($list[$i]['filename']) == strtolower($filename) ||
                strtolower($list[$i]['stored_filename']) == strtolower($filename)) {
                $listIndex = $i;
                break;
            }
        }

        return ($listIndex > -1) ? $listIndex : false;
    }
}
