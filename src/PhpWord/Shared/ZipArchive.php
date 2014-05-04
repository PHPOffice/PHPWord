<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Shared;

// PCLZIP needs the temp path to end in a back slash
// @codeCoverageIgnoreStart
if (!defined('PCLZIP_TEMPORARY_DIR')) {
    define('PCLZIP_TEMPORARY_DIR', sys_get_temp_dir() . '/');
}
require_once 'PCLZip/pclzip.lib.php';
// @codeCoverageIgnoreEnd

/**
 * PCLZip wrapper
 *
 * @since   0.10.0
 */
class ZipArchive
{
    /** constants */
    const OVERWRITE = 'OVERWRITE';
    const CREATE    = 'CREATE';

    /**
     * Number of files (emulate ZipArchive::$numFiles)
     *
     * @var string
     */
    public $numFiles = 0;

    /**
     * Temporary storage directory
     *
     * @var string
     */
    private $tempDir;

    /**
     * Zip Archive Stream Handle
     *
     * @var string
     */
    private $zip;

    /**
     * Open a new zip archive
     *
     * @param  string  $filename Filename for the zip archive
     * @return boolean
     */
    public function open($filename)
    {
        $this->tempDir = sys_get_temp_dir();
        $this->zip = new \PclZip($filename);
        $this->numFiles = count($this->zip->listContent());

        return true;
    }

    /**
     * Close this zip archive (emulate \ZipArchive)
     *
     * @codeCoverageIgnore
     */
    public function close()
    {
    }

    /**
     * Add a new file to the zip archive (emulate \ZipArchive)
     *
     * @param string $filename  Directory/Name of the file to add to the zip archive
     * @param string $localname Directory/Name of the file added to the zip
     */
    public function addFile($filename, $localname = null)
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

        $res = $this->zip->add(
            $filename,
            PCLZIP_OPT_REMOVE_PATH,
            $filenameParts['dirname'],
            PCLZIP_OPT_ADD_PATH,
            $localnameParts["dirname"]
        );

        return ($res == 0) ? false : true;
    }

    /**
     * Add a new file to the zip archive from a string of raw data (emulate \ZipArchive)
     *
     * @param string $localname Directory/Name of the file to add to the zip archive
     * @param string $contents  String of data to add to the zip archive
     */
    public function addFromString($localname, $contents)
    {
        $filenameParts = pathinfo($localname);

        // Write $contents to a temp file
        $handle = fopen($this->tempDir . '/' . $filenameParts["basename"], "wb");
        fwrite($handle, $contents);
        fclose($handle);

        // Add temp file to zip
        $res = $this->zip->add(
            $this->tempDir . '/' . $filenameParts["basename"],
            PCLZIP_OPT_REMOVE_PATH,
            $this->tempDir,
            PCLZIP_OPT_ADD_PATH,
            $filenameParts["dirname"]
        );

        // Remove temp file
        @unlink($this->tempDir . '/' . $filenameParts["basename"]);

        return ($res == 0) ? false : true;
    }

    /**
     * Returns the index of the entry in the archive (emulate \ZipArchive)
     *
     * @param string $filename Filename for the file in zip archive
     * @return integer|false
     */
    public function locateName($filename)
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

    /**
     * Extract file from archive by given file name (emulate \ZipArchive)
     *
     * @param  string $filename Filename for the file in zip archive
     * @return string|false $contents File string contents
     */
    public function getFromName($filename)
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
    public function getNameIndex($index)
    {
        $list = $this->zip->listContent();
        if (isset($list[$index])) {
            return $list[$index]['filename'];
        } else {
            return false;
        }
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
}
