<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Shared;

use PhpOffice\PhpWord\Exception\Exception;

// @codeCoverageIgnoreStart
if (!defined('PCLZIP_TEMPORARY_DIR')) {
    // PCLZIP needs the temp path to end in a back slash
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
     * Close this zip archive
     *
     * @codeCoverageIgnore
     */
    public function close()
    {
    }

    /**
     * Add a new file to the zip archive.
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

        if ($res == 0) {
            throw new Exception("Error zipping files : " . $this->zip->errorInfo(true));
        }

        return true;
    }

    /**
     * Add a new file to the zip archive from a string of raw data.
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
        if ($res == 0) {
            throw new Exception("Error zipping files : " . $this->zip->errorInfo(true));
        }

        // Remove temp file
        unlink($this->tempDir . '/' . $filenameParts["basename"]);

        return true;
    }

    /**
     * Find if given file name exist in archive (Emulate ZipArchive locateName())
     *
     * @param  string  $filename Filename for the file in zip archive
     * @return boolean
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

        return ($listIndex > -1);
    }

    /**
     * Extract file from archive by given file name (Emulate ZipArchive getFromName())
     *
     * @param  string $filename Filename for the file in zip archive
     * @return string $contents File string contents
     */
    public function getFromName($filename)
    {
        $list = $this->zip->listContent();
        $listCount = count($list);
        $listIndex = -1;
        $contents = null;

        for ($i = 0; $i < $listCount; ++$i) {
            if (strtolower($list[$i]["filename"]) == strtolower($filename) ||
                strtolower($list[$i]["stored_filename"]) == strtolower($filename)) {
                $listIndex = $i;
                break;
            }
        }

        if ($listIndex != -1) {
            $extracted = $this->zip->extractByIndex($listIndex, PCLZIP_OPT_EXTRACT_AS_STRING);
        } else {
            $filename = substr($filename, 1);
            $listIndex = -1;
            for ($i = 0; $i < $listCount; ++$i) {
                if (strtolower($list[$i]["filename"]) == strtolower($filename) ||
                    strtolower($list[$i]["stored_filename"]) == strtolower($filename)) {
                    $listIndex = $i;
                    break;
                }
            }
            $extracted = $this->zip->extractByIndex($listIndex, PCLZIP_OPT_EXTRACT_AS_STRING);
        }
        if ((is_array($extracted)) && ($extracted != 0)) {
            $contents = $extracted[0]["content"];
        }

        return $contents;
    }

    /**
     * Returns the name of an entry using its index
     *
     * @param integer $index
     * @return string|false
     */
    public function getNameIndex($index)
    {
        $list = $this->zip->listContent();
        $listCount = count($list);
        if ($index <= $listCount) {
            return $list[$index]['filename'];
        } else {
            return false;
        }
    }
}
