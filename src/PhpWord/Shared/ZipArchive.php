<?php
/**
 * PHPWord
 *
 * @link        https://github.com/PHPOffice/PHPWord
 * @copyright   2014 PHPWord
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt LGPL
 */

namespace PhpOffice\PhpWord\Shared;

use PhpOffice\PhpWord\Exceptions\Exception;

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
 * @since   0.9.2
 */
class ZipArchive
{

    /** constants */
    const OVERWRITE = 'OVERWRITE';
    const CREATE    = 'CREATE';

    /**
     * Temporary storage directory
     *
     * @var string
     */
    private $_tempDir;

    /**
     * Zip Archive Stream Handle
     *
     * @var string
     */
    private $_zip;

    /**
     * Open a new zip archive
     *
     * @param  string  $fileName Filename for the zip archive
     * @return boolean
     */
    public function open($fileName)
    {
        $this->_tempDir = sys_get_temp_dir();
        $this->_zip = new \PclZip($fileName);

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
            $temppath = $this->_tempDir . '/' . $localnameParts['basename'];
            copy($filename, $temppath);
            $filename = $temppath;
            $filenameParts = pathinfo($temppath);
        }

        $res = $this->_zip->add(
            $filename,
            PCLZIP_OPT_REMOVE_PATH,
            $filenameParts['dirname'],
            PCLZIP_OPT_ADD_PATH,
            $localnameParts["dirname"]
        );

        if ($res == 0) {
            throw new Exception("Error zipping files : " . $this->_zip->errorInfo(true));
            return false;
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
        $handle = fopen($this->_tempDir . '/' . $filenameParts["basename"], "wb");
        fwrite($handle, $contents);
        fclose($handle);

        // Add temp file to zip
        $res = $this->_zip->add(
            $this->_tempDir . '/' . $filenameParts["basename"],
            PCLZIP_OPT_REMOVE_PATH,
            $this->_tempDir,
            PCLZIP_OPT_ADD_PATH,
            $filenameParts["dirname"]
        );
        if ($res == 0) {
            throw new Exception("Error zipping files : " . $this->_zip->errorInfo(true));
            return false;
        }

        // Remove temp file
        unlink($this->_tempDir . '/' . $filenameParts["basename"]);

        return true;
    }

    /**
     * Find if given fileName exist in archive (Emulate ZipArchive locateName())
     *
     * @param  string  $fileName Filename for the file in zip archive
     * @return boolean
     */
    public function locateName($fileName)
    {
        $list = $this->_zip->listContent();
        $listCount = count($list);
        $list_index = -1;
        for ($i = 0; $i < $listCount; ++$i) {
            if (strtolower($list[$i]["filename"]) == strtolower($fileName) ||
                strtolower($list[$i]["stored_filename"]) == strtolower($fileName)) {
                $list_index = $i;
                break;
            }
        }

        return ($list_index > -1);
    }

    /**
     * Extract file from archive by given fileName (Emulate ZipArchive getFromName())
     *
     * @param  string $fileName Filename for the file in zip archive
     * @return string $contents File string contents
     */
    public function getFromName($fileName)
    {
        $list = $this->_zip->listContent();
        $listCount = count($list);
        $list_index = -1;
        for ($i = 0; $i < $listCount; ++$i) {
            if (strtolower($list[$i]["filename"]) == strtolower($fileName) ||
                strtolower($list[$i]["stored_filename"]) == strtolower($fileName)) {
                $list_index = $i;
                break;
            }
        }

        $extracted = "";
        if ($list_index != -1) {
            $extracted = $this->_zip->extractByIndex($list_index, PCLZIP_OPT_EXTRACT_AS_STRING);
        } else {
            $filename = substr($fileName, 1);
            $list_index = -1;
            for ($i = 0; $i < $listCount; ++$i) {
                if (strtolower($list[$i]["filename"]) == strtolower($fileName) ||
                    strtolower($list[$i]["stored_filename"]) == strtolower($fileName)) {
                    $list_index = $i;
                    break;
                }
            }
            $extracted = $this->_zip->extractByIndex($list_index, PCLZIP_OPT_EXTRACT_AS_STRING);
        }
        if ((is_array($extracted)) && ($extracted != 0)) {
            $contents = $extracted[0]["content"];
        }

        return $contents;
    }
}
